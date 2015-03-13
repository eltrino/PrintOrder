<?php

class Eltrino_PrintOrder_OrderController extends Mage_Core_Controller_Front_Action
{
    public function printOrderAction()
    {
        $action = new \Eltrino\PrintOrder\Controller\Order\PrintOrder();
        $action->execute();
    }
}