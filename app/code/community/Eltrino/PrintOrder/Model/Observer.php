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
