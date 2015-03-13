<?php

/**
 * Print Order Confirmation as Guest
 *
 * Copyright (c) 2012 Eltrino LLC. (http://eltrino.com). All rights reserved.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 */
namespace Eltrino\PrintOrder\Model\Resource\GuestOrder;

class Collection extends \Magento\Framework\Model\Resource\Db\Collection\AbstractCollection
{

    /**
     * Clean expired guests orders
     *
     * @return \Eltrino\PrintOrder\Model\Resource\GuestOrder\Collection
     */
    public function cleanExpiredGuestsOrders()
    {
        $this->addOrder('expired_at')
            ->addFieldToFilter(
                'expired_at',
                ['to' => date('Y-m-d H:i:s', time())]
            );
        \Zend_Debug::dump($this->getSelect()->__toString());
        $this->walk('delete');

        return $this;
    }

    /**
     * @return void
     */

    protected function _construct()
    {
        $this->_init('Eltrino\PrintOrder\Model\GuestOrder', 'Eltrino\PrintOrder\Model\Resource\GuestOrder');
    }


}