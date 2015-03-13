<?php

/**
 * Print Order Confirmation as Guest
 *
 * Copyright (c) 2012 Eltrino LLC. (http://eltrino.com). All rights reserved.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */
namespace Eltrino\PrintOrder\Controller\Order;

class PrintOrder extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\App\Action\Context
     */
    protected $_context;

    /**
     * @var \Eltrino\PrintOrder\Helper\Data
     */
    protected $_helper;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Registry $registry,
        \Eltrino\PrintOrder\Helper\Data $helper
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_helper = $helper;
        $this->_context = $context;
        $this->_coreRegistry = $registry;

        if (!$this->_helper) {
            $this->_helper = \Mage::getModel('Eltrino\PrintOrder\Helper\Data');
        }

        if (!$registry) {
            $this->_coreRegistry = \Mage::getModel('Magento\Framework\Registry');
        }

        if (!$context) {
            $this->_context =\Mage::getModel('Magento\Framework\Registry', $this);
        }

        parent::__construct($context);
    }

    public function execute()
    {
        $guestOrderHash = $this->getRequest()->getParam('order_hash');
        try {
            $guestOrder = $this->_helper->getGuestOrderByHash($guestOrderHash);
            $order = $this->_helper->getOrderById((int)$guestOrder->getOrderId());

            $this->_coreRegistry->register('current_order', $order);

            $this->_context->getView()->loadLayout('print');
            $this->_context->getView()->loadLayout('sales_order_print');
            $this->_context->getView()->renderLayout();

        } catch (\Eltrino\PrintOrder\Model\Exception $e) {
            $this->_forward('noRoute');
        }
    }
}
