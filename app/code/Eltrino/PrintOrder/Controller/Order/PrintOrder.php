<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
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
