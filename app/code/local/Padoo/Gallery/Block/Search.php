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
 
class Padoo_Gallery_Block_Search extends Mage_Core_Block_Template
{
	protected $_defaultToolbarBlock = 'gallery/page_pager';
	
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
	public function getPhotoResults() {
		$_keyword= $_GET['keyword'];
		$exceptstrangesign = preg_replace('#[^0-9a-z]+#i', ' ', $_keyword);
		$inputarrayword= array();
		$inputarrayword=explode(' ',$exceptstrangesign);
		$collection = Mage::getResourceModel('gallery/gallery_collection');
		$where ="";
		for($i=0;$i<count($inputarrayword);$i++)
		{
			$where .= "title like '%".$inputarrayword[$i]."%' OR content like '%".$inputarrayword[$i]."%' OR ";
		}
		$where = substr($where,0,strlen($where)-4); 
		$collection->getSelect()->where($where)->where('status = ?', 1);	
		
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
		$storeId = Mage::app()->getStore(true)->getId();
		foreach ($collection as $record) {
			$stores 	= $record->getStoreId();
			if(!(strpos($stores,$storeId) !== false || strpos($stores,0) !== false || $stores == 0 )){
				$collection->removeItemByKey($record->getId());
			}
		}
	    return $collection;
	}

     public function getMode(){
        return $this->getChild('toolbar')->getCurrentMode();
    }
    
    protected function _beforeToHtml(){
        $toolbar = $this->getToolbarBlock();
		
        // called prepare sortable parameters
        $collection = $this->getPhotoResults();
        
        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);
		
        $this->setChild('toolbar', $toolbar);
        
        $this->getPhotoResults()->load();
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