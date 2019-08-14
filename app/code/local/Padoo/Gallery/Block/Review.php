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
 
class Padoo_Gallery_Block_Review extends Mage_Core_Block_Template
{
	protected $_defaultToolbarBlock = 'gallery/page_pager';
	
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getGallery()     
     { 
        if (!$this->hasData('gallery')) {
            $this->setData('gallery', Mage::registry('gallery'));
        }
        return $this->getData('gallery','asc');
        
    }
	public function getNewAlbums()
	{
		$total = $this->getTotalAlbum();

		$collection_new_album = Mage::getModel('gallery/album')->getCollection()	
		->addOrder('album_id','DESC')
		->setPageSize($total);

		return $collection_new_album;
	}
	
	public function getPhotos() {
		$collection = Mage::getModel('gallery/gallery')->getCollection()->addFieldToFilter('status', true);
		if (Mage::registry('current_album')) $collection->addFieldToFilter('album_id' , Mage::registry('current_album')->getId());
		else if ($this->getData('album_id')) {
			$collection->addFieldToFilter('album_id' , $this->getData('album_id'));
			$album = Mage::getModel('gallery/album')->load($this->getData('album_id'));
			if ($album) {
				Mage::register('current_album', $album);
			}
		}
		$collection
		->addOrder('gallery_id','DESC');
		
		$currentPage = (int)$this->getRequest()->getParam('page');
	    if(!$currentPage){
	        $currentPage = 1;
	    }
	    
	    $currentLimit = (int)$this->getRequest()->getParam('limit');
	    if(!$currentLimit){
	        $currentLimit = Mage::getStoreConfig('gallery/info/images_per_page_default_value') ? Mage::getStoreConfig('gallery/info/images_per_page_default_value'):4;
	    }
	    
	    $collection->setPageSize($currentLimit);
	    $collection->setCurPage($currentPage);
	    return $collection;
	}

	public function getDescription() {
		if (Mage::registry('current_album')) return Mage::registry('current_album')->getContent();
		return '';
	}

	public function getAlbums() {
		$collection = Mage::getModel('gallery/album')->getCollection()
						->addFieldToFilter('status', true)
						->addOrder('album_id', 'DESC');
		return $collection;
	}
	
	public function getTotalAlbumValue()
	{
		return $this->getTotalAlbum();
	}
	
	public function getUrlRewrite(Mage_Core_Model_Abstract $obj){
		$rewrite = Mage::getModel('core/url_rewrite')
		->load($obj->getUrlRewriteId());
		return trim(Mage::getBaseUrl(),'/')."/". $rewrite->getRequest_path();
	}
	

     public function getMode()
    {
        return $this->getChild('toolbar')->getCurrentMode();
    }
    
    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();
		
        // called prepare sortable parameters
        $collection = $this->getPhotos();
        
        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);
        
        $this->getPhotos()->load();
        return parent::_beforeToHtml();
    }
    
    public function getToolbarBlock()
    {
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, microtime());
        return $block;
    }

    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }	
}