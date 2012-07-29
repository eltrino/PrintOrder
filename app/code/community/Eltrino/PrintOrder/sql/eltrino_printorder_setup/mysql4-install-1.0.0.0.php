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


$installer = $this;

$installer->startSetup();

$installer->run("
    CREATE TABLE IF NOT EXISTS `{$installer->getTable('eltrino_printorder/guests_orders')}` (
      `guests_order_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Guests Order Entity Id',
      `hash` varchar(255) DEFAULT NULL COMMENT 'Hash of order',
      `order_id` int(10) unsigned NOT NULL COMMENT 'Order Id',
      `expired_at` datetime NOT NULL COMMENT 'Date when hash is expired',
    PRIMARY KEY (`guests_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Guests Orders with generated temporary hashes of orders';
");

$installer->endSetup();
