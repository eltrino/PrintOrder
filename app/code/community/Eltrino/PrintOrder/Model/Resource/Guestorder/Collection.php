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


class Eltrino_PrintOrder_Model_Resource_Guestorder_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        parent::_construct();
        $this->_init('eltrino_printorder/guestorder');
    }

    /**
     * Clean expired guests orders
     *
     * @return Eltrino_PrintOrder_Model_Resource_Guestorder_Collection
     */
    public function cleanExpiredGuestsOrders()
    {
        $this->addFieldToFilter('expired_at', array('to' => date('Y-m-d H:i:s', time())));
        Zend_Debug::dump($this->getSelect()->__toString());
        $this->walk('delete');
        return $this;
    }
}
