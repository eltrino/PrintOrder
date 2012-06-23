<?php

class Eltrino_PrintOrder_Block_Checkout_Onepage_Success extends Mage_Checkout_Block_Onepage_Success
{
    protected function _construct()
    {
        parent::_construct();
    }

    protected function _toHtml()
    {
        $this->_template = 'eltrino/printorder/success.phtml';
        return parent::_toHtml();
    }

    /**
     * Get last order ID from session, fetch it and check whether it can be viewed, printed etc
     *
     * @return void
     */
    protected function _prepareLastOrder()
    {
        parent::_prepareLastOrder();
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        if ($orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            if ($order->getId()) {
                $guestOrder = Mage::getModel('eltrino_printorder/guestorder')
                    ->load($order->getId(), 'order_id');
                if ($guestOrder->getId()) {
                    $guestOrderHash = $guestOrder->getHash();
                    $guestPrintOrderUrl = $this->getUrl(
                        'guest/order/print',
                        array(
                            '_current' => true,
                            '_secure' => true,
                            'order_hash' => $guestOrderHash
                        ));
                    $this->addData(array('guest_print_order_url' => $guestPrintOrderUrl));
                }
            }
        }
    }

    /**
     * Verify that customer is guest
     *
     * @return bool
     */
    public function getIsGuestOrder()
    {
        $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
        if ($orderId) {
            $order = Mage::getModel('sales/order')->load($orderId);
            if ($order->getId()) {
                return $order->getCustomerIsGuest();
            }
        }
        return false;
    }
}
