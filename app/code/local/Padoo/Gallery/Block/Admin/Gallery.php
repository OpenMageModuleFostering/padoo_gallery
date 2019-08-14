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
 
class Padoo_Gallery_Block_Admin_Gallery extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'admin_gallery';
    $this->_blockGroup = 'gallery';
    $this->_headerText = Mage::helper('gallery')->__('Photo Manager');
    $this->_addButtonLabel = Mage::helper('gallery')->__('Add Photo');
	$this->_addButton('sort', array(
    	'label'     => Mage::helper('gallery')->__('Save order'),
    	'onclick'   => 'saveSort();',
    	'class'     => 'button',
    ));
    parent::__construct();
  }
}