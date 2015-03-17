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

namespace Eltrino\PrintOrder\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const DEFAULT_GUESTORDER_AVAILABILITY_PERIOD = 3600; // 1 hour (60 seconds * 60 minutes)

    /**
     * @var \Eltrino\PrintOrder\Model\GuestOrder
     */
    protected $_guestOrder;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_config;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;

    /**
     * @param \Eltrino\PrintOrder\Model\GuestOrder $guestOrder
     */
    public function __construct(
        \Eltrino\PrintOrder\Model\GuestOrder $guestOrder,
        \Magento\Sales\Model\Order\Config $config,
        \Magento\Sales\Model\Order $order
    ) {
        $this->_guestOrder = $guestOrder;
        $this->_config = $config;
        $this->_order = $order;

        if (!$this->_guestOrder) {
            $this->_guestOrder = \Mage::getModel('Eltrino\PrintOrder\Model\GuestOrder');
        }

        if (!$this->_config) {
            $this->_config = \Mage::getModel('Magento\Sales\Model\Order\Config');
        }

        if (!$this->_order) {
            $this->_order = \Mage::getModel('Magento\Sales\Model\Order');
        }
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     *
     * @return mixed
     *
     * @throws \Eltrino\PrintOrder\Model\Exception
     */
    public function createFromOrder(\Magento\Sales\Model\Order $order)
    {
        if (!$order->getId()) {
            throw new \Eltrino\PrintOrder\Model\Exception('Can not create GuestOrder from given Order');
        }

        $this->_guestOrder->setHash($this->_generateHashForGuestOrder($order))
            ->setOrderId($order->getId())
            ->setExpiredAt($this->_generateExpiredAtForGuestOrder());

        return $this->_guestOrder;
    }

    /**
     * Generate hash string for GuestOrder using Order values.
     *
     * @param \Magento\Sales\Model\Order $order
     *
     * @return string
     */
    protected function _generateHashForGuestOrder(\Magento\Sales\Model\Order $order)
    {
        return md5($order->getIncrementId());
    }

    /**
     * Generate expired at date for GuestOrder.
     *
     * @return int
     */
    protected function _generateExpiredAtForGuestOrder()
    {
        return date('Y-m-d H:i:s', (time() + self::DEFAULT_GUESTORDER_AVAILABILITY_PERIOD));
    }

    /**
     * @param $guestOrderHash
     *
     * @return $this
     *
     * @throws \Eltrino\PrintOrder\Model\Exception
     */
    public function getGuestOrderByHash($guestOrderHash)
    {
        $guestOrder = $this->_guestOrder->load($guestOrderHash, 'hash');

        if (!$guestOrder->getId() || !$guestOrder->getOrderId()
            || false == $this->getIsGuestOrderActive($guestOrder)
        ) {
            throw new \Eltrino\PrintOrder\Model\Exception('Corrupted Guest Order');
        }

        return $guestOrder;
    }

    /**
     * Check if GuestOrder is still active for guest.
     *
     * @param \Eltrino\PrintOrder\Model\GuestOrder $guestOrder
     *
     * @return bool
     */
    public function getIsGuestOrderActive(\Eltrino\PrintOrder\Model\GuestOrder $guestOrder)
    {
        if ($guestOrder->getId() && strtotime($guestOrder->getExpiredAt()) > time()) {
            return true;
        }

        return false;
    }

    /**
     * @param $orderId
     *
     * @return $this
     *
     * @throws \Eltrino\PrintOrder\Model\Exception
     */
    public function getOrderById($orderId)
    {
        $order = $this->_order->load($orderId);
        if (!$order->getId() || false == $this->canViewOrder($order)) {
            throw new \Eltrino\PrintOrder\Model\Exception('Corrupted Guest Order');
        }

        return $order;
    }

    /**
     * Check if order can be viewed.
     *
     * @param \Magento\Sales\Model\Order $order
     *
     * @return bool
     */
    public function canViewOrder(\Magento\Sales\Model\Order $order)
    {
        $availableStatuses = $this->_config->getVisibleOnFrontStatuses();
        if (in_array($order->getStatus(), $availableStatuses, true)) {
            return true;
        }

        return false;
    }
}
