<?php

class Eltrino_PrintOrder_Model_Resource_Guestorder extends Mage_Core_Model_Mysql4_Abstract
{

    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('eltrino_printorder/guests_orders', 'guests_order_id');
    }
}
