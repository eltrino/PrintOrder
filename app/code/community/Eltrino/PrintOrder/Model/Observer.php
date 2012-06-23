<?php

class Eltrino_PrintOrder_Model_Observer
{
    /**
     * After successful order placement fill temporary orders table special for guests
     *
     * @param Varien_Event_Observer $observer
     * @return Eltrino_PrintOrder_Model_Observer
     * @throws Eltrino_PrintOrder_Exception
     */
    public function fillGuestsOrdersTable(Varien_Event_Observer $observer)
    {
        /** @var $order Mage_Sales_Model_Order */
        $order = $observer->getEvent()->getOrder();
        try {
            /** @var $guestOrder Eltrino_PrintOrder_Model_Guestorder */
            $guestOrder = Mage::helper('eltrino_printorder')->createFromOrder($order);
            $guestOrder->save();
        } catch (Eltrino_PrintOrder_Exception $e) {
            // if DeveloperMode enabled throw Exception, otherwise skip saving of such object
            if (true == Mage::getIsDeveloperMode()) {
                throw $e;
            }
        }
        return $this;
    }

    /**
     * Clean expired guests orders
     *
     * @param Mage_Cron_Model_Schedule $schedule
     * @return Eltrino_PrintOrder_Model_Observer
     */
    public function cleanExpiredGuestsOrders($schedule)
    {
        Mage::getModel('eltrino_printorder/guestorder')->getCollection()
            ->cleanExpiredGuestsOrders();
        return $this;
    }
}
