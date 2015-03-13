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

$installer = $this;

$installer->startSetup();

$installer->run("
    CREATE TABLE IF NOT EXISTS `eltrino_guests_orders` (
      `guests_order_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Guests Order Entity Id',
      `hash` varchar(255) DEFAULT NULL COMMENT 'Hash of order',
      `order_id` int(10) unsigned NOT NULL COMMENT 'Order Id',
      `expired_at` datetime NOT NULL COMMENT 'Date when hash is expired',
    PRIMARY KEY (`guests_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Guests Orders with generated temporary hashes of orders';
");

$installer->endSetup();
