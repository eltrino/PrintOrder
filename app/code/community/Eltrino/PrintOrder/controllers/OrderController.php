<?php

class Eltrino_PrintOrder_OrderController extends Mage_Core_Controller_Front_Action
{
    /**
     * @var Eltrino_PrintOrder_Helper_Data
     */
    protected $_helper;

    /**
     * Pre dispatch event
     *
     * @return Eltrino_PrintOrder_OrderController|Mage_Core_Controller_Front_Action
     */
    public function preDispatch()
    {
        parent::preDispatch();
        $this->_helper = Mage::helper('eltrino_printorder');
        return $this;
    }

    /**
     * Initialization of GuestOrder
     *
     * @param string $guestOrderHash
     * @return Mage_Core_Model_Abstract
     * @throws Eltrino_PrintOrder_Exception
     */
    protected function _initGuestOrderByHash($guestOrderHash)
    {
        $guestOrder = Mage::getSingleton('eltrino_printorder/guestorder')
            ->load($guestOrderHash, 'hash');

        if (!$guestOrder->getId() || !$guestOrder->getOrderId()
            || false == $this->_helper->getIsGuestOrderActive($guestOrder)
        ) {
            throw new Eltrino_PrintOrder_Exception('Corrupted Guest Order');
        }
        return $guestOrder;
    }

    /**
     * Initialization of Order
     *
     * @param int $orderId
     * @return Mage_Core_Model_Abstract
     * @throws Eltrino_PrintOrder_Exception
     */
    protected function _initOrderById($orderId)
    {
        $order = Mage::getModel('sales/order')->load($orderId);
        if (!$order->getId() || false == Mage::helper('eltrino_printorder')->canViewOrder($order)) {
            throw new Eltrino_PrintOrder_Exception('Corrupted Order');
        }
        return $order;
    }

    /**
     * Print Action for Guests
     *
     * @return void
     * @throws Eltrino_PrintOrder_Exception
     */
    public function printAction()
    {
        $guestOrderHash = $this->getRequest()->getParam('order_hash');
        try {
            $guestOrder = $this->_initGuestOrderByHash($guestOrderHash);
            $order = $this->_initOrderById((int) $guestOrder->getId());

            Mage::register('current_order', $order);

            $this->loadLayout(array('print', 'sales_order_print'));
            $this->renderLayout();
        } catch (Eltrino_PrintOrder_Exception $e) {
            if (true == Mage::getIsDeveloperMode()) {
                throw $e;
            }
            $this->_forward('noRoute');
        }
    }

    /**
     * Post dispatch event
     *
     * @return Eltrino_PrintOrder_OrderController|Mage_Core_Controller_Front_Action
     */
    public function postDispatch()
    {
        parent::postDispatch();
        Mage::unregister('current_order');
        return $this;
    }
}
