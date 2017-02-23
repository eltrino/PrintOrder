<?php

/**
 * The MIT License (MIT).
 *
 * Copyright (c) 2015 Eltrino LLC (http://eltrino.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Eltrino\PrintOrder\Block;

class PrintOrder extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_config;

    /**
     * @var \Eltrino\PrintOrder\Model\Guestorder
     */
    protected $_guestOrder;

    protected $_helper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Checkout\Model\Session                  $checkoutSession
     * @param \Magento\Customer\Model\Session                  $customerSession
     * @param \Magento\Sales\Model\Order\Config                $config
     * @param \Eltrino\PrintOrder\Model\GuestOrder             $guestOrder
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order\Config $config,
        \Eltrino\PrintOrder\Model\GuestOrder $guestOrder,
        \Eltrino\PrintOrder\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_checkoutSession = $checkoutSession;
        $this->_customerSession = $customerSession;
        $this->_config = $config;
        $this->_guestOrder = $guestOrder;
        $this->_helper = $helper;

        if (!$this->_checkoutSession) {
            $this->_checkoutSession = \Mage::getModel('Magento\Checkout\Model\Session');
        }

        if (!$this->_customerSession) {
            $this->_customerSession = \Mage::getModel('Magento\Customer\Model\Session');
        }

        if (!$this->_config) {
            $this->_config = \Mage::getModel('Magento\Sales\Model\Order\Config');
        }

        if (!$this->_guestOrder) {
            $this->_guestOrder = \Mage::getModel('Eltrino\PrintOrder\Model\GuestOrder');
        }
        if (!$this->_helper) {
            $this->_helper = \Mage::getModel('Eltrino\PrintOrder\Helper\Data');
        }
    }

    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $this->_preparePrintOrderData();

        return $this;
    }

    /**
     * Generate print order url for proper kind of order.
     *
     * @return PrintOrder
     */
    protected function _preparePrintOrderData()
    {
        $order = $this->_initOrder();
        $printOrderUrl = "empty";
        if ($order) {
            $isVisible = in_array($order->getState(),
                $this->_config->getVisibleOnFrontStatuses());

            $canPrintOrder = (bool) (($this->_customerSession->isLoggedIn() && $isVisible)
                || $order->getCustomerIsGuest());

            if ($order->getCustomerIsGuest()) {
                $guestOrder = $this->_guestOrder->load($order->getId(), 'order_id');                
                if ($guestOrder->getId()) {
                    $guestOrderHash = $guestOrder->getHash();
                    $printOrderUrl = $this->getUrl('guest/order/printorder', array('order_hash' => $guestOrderHash));
                }else{
                    $guestOrder = $this->_helper->createFromOrder($order);                    
                    $guestOrder->save();
                    $guestOrderHash = $guestOrder->getHash();
                    $printOrderUrl = $this->getUrl('guest/order/printorder', array('order_hash' => $guestOrderHash));
                }
            } else {
                $printOrderUrl = $this->getUrl('sales/order/print', array('order_id' => $order->getId()));
            }            
            $this->setCanPrintOrder($canPrintOrder)
                ->setPrintOrderUrl($printOrderUrl);
        }

        return $this;
    }

    /**
     * Load order by last order id stored in session.
     *
     * @return \Magento\Sales\Model\Order|null
     */
    protected function _initOrder()
    {
        $order = $this->_checkoutSession->getLastRealOrder();
        if ($order->getId()) {
            return $order;
        }

        return;
    }
}
