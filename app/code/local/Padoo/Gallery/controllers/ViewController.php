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
 
class Padoo_Gallery_ViewController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		if (!Mage::getStoreConfig('gallery/info/enabled')) $this->_redirect('no-route');
		$this->_redirect('gallery');
    }

	public function albumAction() {
		if (!Mage::getStoreConfig('gallery/info/enabled')) $this->_redirect('no-route');
		$album_id = $this->getRequest()->getParam('id');		
		if($album_id != null && $album_id != '')	{
			$album = Mage::getModel('gallery/album')->load($album_id);
		} else {
			$album = null;
		}	
		
		if ($album) {
			Mage::register('current_album', $album);
			$this->loadLayout();     
			$this->renderLayout();
		} else {
			$this->_forward('noRoute');
		}
	}
	
	public function detailAction() {
		$id = $this->getRequest()->getParam('id');
		if($id != null && $id != '')	{
			$details = Mage::getModel('gallery/gallery')->load($id);
		} else {
			$details = null;
		}	
		
		if ($details) {
			Mage::register('gallery_detail', $details);
			$this->loadLayout();     
			$this->renderLayout();
		} else {
			$this->_forward('noRoute');
		}
	}
}