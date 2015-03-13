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

namespace Eltrino\PrintOrder\Model;

class Observer
{
    /**
     * @var \Eltrino\PrintOrder\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Eltrino\PrintOrder\Model\GuestOrder
     */
    protected $_guestOrder;

    /**
     * @var \Magento\Framework\App\State $_appState
     */
    protected $_appState;

    /**
     * @param GuestOrder $guestOrder
     * @param \Eltrino\PrintOrder\Helper\Data $helper
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(
        \Eltrino\PrintOrder\Model\GuestOrder $guestOrder,
        \Eltrino\PrintOrder\Helper\Data $helper,
        \Magento\Framework\App\State $state
    ) {
        $this->_guestOrder = $guestOrder;
        $this->_helper = $helper;
        $this->_appState = $state;

        if (!$this->_guestOrder) {
            $this->_guestOrder = \Mage::getModel('Eltrino\PrintOrder\Model\GuestOrder');
        }

        if (!$this->_helper) {
            $this->_helper = \Mage::getModel('Eltrino\PrintOrder\Helper\Data');
        }
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     * @throws Exception
     */
    public function fillGuestsOrdersTable(\Magento\Framework\Event\Observer $observer)
    {
        /** @var $order \Magento\Sales\Model\Order */
        $order = $observer->getEvent()->getOrder();
        try {
            /** @var $guestOrder \Eltrino\PrintOrder\Model\GuestOrder */
            $guestOrder = $this->_helper->createFromOrder($order);
            $guestOrder->save();
        } catch (\Eltrino\PrintOrder\Model\Exception $e) {
            // if DeveloperMode enabled throw Exception, otherwise skip saving of such object
            //if ($this->_appState->getMode() == \Magento\Framework\App\State::MODE_DEVELOPER) {
            //    throw new \Eltrino\PrintOrder\Model\Exception(__($e->getMessage()));
            //}
        }

        return $this;
    }

    /**
     * Clean expired guests orders
     *
     * @param \Magento\Cron\Model\Schedule $schedule
     * @return \Eltrino\PrintOrder\Model\Observer
     */
    public function cleanExpiredGuestsOrders($schedule)
    {
        /** @var \Eltrino\PrintOrder\Model\Resource\GuestOrder\Collection $collection */
        $collection = $this->_guestOrder->getCollection();

        $collection->cleanExpiredGuestsOrders();

        return $this;
    }
}
