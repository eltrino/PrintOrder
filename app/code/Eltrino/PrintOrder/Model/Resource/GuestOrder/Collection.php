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