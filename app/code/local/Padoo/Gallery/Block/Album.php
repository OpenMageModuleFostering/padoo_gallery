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
 
class Padoo_Gallery_Block_Album extends Mage_Core_Block_Template
{
	protected $_banner = false;

	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getAlbum()     
     { 
		if (!$this->_album) {
			$album_id = $this->getAlbumId();
			if ($album_id) {
				$album = Mage::getModel('gallery/album')->load($album_id);
				if ($album->getId()==0) {
					$album = Mage::getModel('gallery/album')->load($album_id, 'identifier');
				}
				$this->_album = $album;
			}
		}
        return $this->_album;       
    }

	public function getPhotos() {
		$album = $this->getAlbum();
		if($album){
			$show_total = $this->getShowTotal() ? $this->getShowTotal():3;
			$collection = Mage::getModel('gallery/gallery')->getCollection()
				->addFieldToFilter('status', true)
				->addFieldToFilter('album_id', $album->getAlbumId())
				->addOrder('order','ASC')
				->setPageSize($show_total);
			if($collection->count()>0){
				return $collection;
			}
		}
		return false;
	}
}