<?php

/**
 * The MIT License (MIT)
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
