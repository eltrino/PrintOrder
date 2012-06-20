<?php

class Eltrino_PrintOrder_Model_Guestorder extends Mage_Core_Model_Abstract
{
    /**
     * Initial model constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('eltrino_printorder/guestorder');
    }
}
