<?php

/**
 * Print Order Confirmation as Guest
 *
 * LICENSE
 *
 * This source file is subject to the Eltrino LLC EULA
 * that is bundled with this package in the file LICENSE_EULA.txt.
 * It is also available through the world-wide-web at this URL:
 * http://eltrino.com/license-eula.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eltrino.com so we can send you a copy immediately.
 *
 * @category    Eltrino
 * @package     Eltrino_PrintOrder
 * @copyright   Copyright (c) 2012 Eltrino LLC. (http://eltrino.com)
 * @license     http://eltrino.com/license-eula.txt  Eltrino LLC EULA
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

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\Order\Config $config
     * @param \Eltrino\PrintOrder\Model\GuestOrder $guestOrder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order\Config $config,
        \Eltrino\PrintOrder\Model\GuestOrder $guestOrder,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_checkoutSession = $checkoutSession;
        $this->_customerSession = $customerSession;
        $this->_config = $config;
        $this->_guestOrder = $guestOrder;
    }

    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $this->_preparePrintOrderData();

        return $this;
    }

    /**
     * Generate print order url for proper kind of order
     *
     * @return PrintOrder
     */
    protected function _preparePrintOrderData()
    {
        $order = $this->_initOrder();
        if ($order) {

            $isVisible = !in_array($order->getState(),
                $this->_config->getInvisibleOnFrontStatuses());

            $canPrintOrder = (bool)(($this->_customerSession->isLoggedIn() && $isVisible)
                || $order->getCustomerIsGuest());

            if ($order->getCustomerIsGuest()) {
                $guestOrder = $this->_guestOrder->load($order->getId(), 'order_id');
                if ($guestOrder->getId()) {
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
     * Load order by last order id stored in session
     *
     * @return \Magento\Sales\Model\Order|null
     */
    protected function _initOrder()
    {
        $order = $this->_checkoutSession->getLastRealOrder();
        if ($order->getId()) {
            return $order;
        }

        return null;
    }
}
