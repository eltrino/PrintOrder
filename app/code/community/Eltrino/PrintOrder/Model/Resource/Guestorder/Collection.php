<?php

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
