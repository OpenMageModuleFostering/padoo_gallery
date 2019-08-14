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
 
class Padoo_Gallery_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function checkEmailDuplicationAjaxUrl()
    {
        return $this->_getUrl('gallery/account/checkemailduplication');
    } 
    
	public function getDescription(){
		return Mage::getStoreConfig('gallery/info/show_description') ? Mage::getStoreConfig('gallery/info/show_description'):0;
	}
	
	public function getEnabledPagination(){
		if(Mage::getStoreConfig('gallery/info/enabled_pagination')){
			return Mage::getStoreConfig('gallery/info/enabled_pagination');
		}else{
			return 1;
		}
	}
	
	const MYCONFIG = "gallery/info/enabled";
	const MYNAME = "Padoo_Gallery";
	
	public function myConfig(){
    	return self::MYCONFIG;
    }
	
	function disableConfig()
	{
			Mage::getSingleton('core/config')->saveConfig($this->myConfig(),0); 			
			Mage::getModel('core/config')->saveConfig("advanced/modules_disable_output/".self::MYNAME,1);	
			 Mage::getConfig()->reinit();
	}
		
	public function getGallleryUrl(){
		return $this->_getUrl('gallery');
	}
}