<?php

class Eltrino_PrintOrder_Block_Checkout_Onepage_Success extends Mage_Checkout_Block_Onepage_Success
{
    /**
     * Disable native print order functionality
     *
     * @return bool
     */
    public function getCanPrintOrder()
    {
        return false;
    }
}
