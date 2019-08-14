<?php
/**
 * Padoosoft Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0).
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you are unable to obtain it through the world-wide-web, please send
 * an email to support@mage-addons.com so we can send you a copy immediately.
 *
 * @category   Padoo
 * @package    Padoo_Gallery
 * @author     PadooSoft Team
 * @copyright  Copyright (c) 2010-2012 Padoo Co. (http://mage-addons.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Padoo_Gallery_Model_Project extends Varien_Object
{
    const PROJECT_OTHERS	= 'others';
    const PROJECT_JEEP_TJ	= 'jeep_tj';
    const PROJECT_TACOMA	= 'tacoma';
    const PROJECT_JEEP_JK	= 'jeep_jk';

    static public function getOptionArray()
    {
        return array(
            self::PROJECT_OTHERS    => Mage::helper('gallery')->__('Others'),
            self::PROJECT_JEEP_TJ    => Mage::helper('gallery')->__('Jeep TJ'),
            self::PROJECT_TACOMA    => Mage::helper('gallery')->__('Tacoma'),
            self::PROJECT_JEEP_JK    => Mage::helper('gallery')->__('Jeep JK')
        );
    }
}