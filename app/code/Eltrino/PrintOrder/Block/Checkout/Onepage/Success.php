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
namespace Eltrino\PrintOrder\Block\Checkout\Onepage;

class Success extends \Magento\Checkout\Block\Onepage\Success
{

    /**
     * @return string
     */
    public function _beforeToHtml()
    {
        // For magento1 compatibility
        if ($this->getModuleName()) {
            // For correct template rendering
            $this->setModuleName(self::extractModuleName(get_parent_class()));
        }

        return parent::_beforeToHtml();
    }

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
