<?php

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
