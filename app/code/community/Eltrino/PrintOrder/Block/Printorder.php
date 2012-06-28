<?php

class Eltrino_PrintOrder_Block_Printorder extends Mage_Core_Block_Template
{
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $this->_preparePrintOrderData();
        return $this;
    }

    /**
     * Load order by last order id stored in session
     *
     * @return Mage_Sales_Model_Order|null
     */
    protected function _initOrder()
    {
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        if ($orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            if ($order->getId()) {
                return $order;
            }
        }
        return null;
    }

    /**
     * Generate print order url for proper kind of order
     *
     * @return Eltrino_PrintOrder_Block_Printorder
     */
    protected function _preparePrintOrderData()
    {
        $order = $this->_initOrder();
        if ($order) {

            $isVisible = !in_array($order->getState(),
                Mage::getSingleton('sales/order_config')->getInvisibleOnFrontStates());

            $canPrintOrder = (bool)((Mage::getSingleton('customer/session')->isLoggedIn() && $isVisible)
                || $order->getCustomerIsGuest());

            if ($order->getCustomerIsGuest()) {
                $guestOrder = Mage::getModel('eltrino_printorder/guestorder')
                    ->load($order->getId(), 'order_id');
                if ($guestOrder->getId()) {
                    $guestOrderHash = $guestOrder->getHash();
                    $printOrderUrl = $this->getUrl('guest/order/print', array('order_hash' => $guestOrderHash));
                }
            } else {
                $printOrderUrl = $this->getUrl('sales/order/print', array('order_id'=> $order->getId()));
            }

            $this->setCanPrintOrder($canPrintOrder)
                ->setPrintOrderUrl($printOrderUrl);
        }
        return $this;
    }
}
