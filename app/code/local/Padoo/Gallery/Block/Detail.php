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
 
class Padoo_Gallery_Block_Detail extends Mage_Core_Block_Template
{
	protected $_banner = false;

	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getDetails(){ 
		if (Mage::registry('gallery_detail')) return Mage::registry('gallery_detail');      
    }
	
	/* public function getUrlRewrite(Mage_Core_Model_Abstract $obj){
		$rewrite = Mage::getModel('core/url_rewrite')
		->load($obj->getUrlRewriteId());
		return trim(Mage::getBaseUrl(),'/')."/". $rewrite->getRequest_path();
	} */
	
	public function getAlbumByGalleryId($album_id){
		if ($album_id) {
			$album = Mage::getModel('gallery/album')->load($album_id);
			if ($album->getId()==0) {
				$album = Mage::getModel('gallery/album')->load($album_id, 'identifier');
			}
		}
		return $album;   
	}
	
	public function getBreadcrumbs(){
		$item   = $this->getDetails();
		$album	= $this->getAlbumByGalleryId($item->getAlbumId());
		$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
		$breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('cms')->__('Home Page'), 'link'=>Mage::getBaseUrl()));
		$breadcrumbs->addCrumb('gallery', array('label'=>'gallery', 'title'=>'gallery', 'link'=>$this->getUrl('gallery')));
		$breadcrumbs->addCrumb('album', array('label'=>$album->getTitle(), 'title'=>$album->getTitle(), 'link'=>$this->getUrl('gallery/view/album',array('id'=>$album->getAlbumId()))) );
		$breadcrumbs->addCrumb('details', array('label'=>$item->getTitle(), 'title'=>$item->getTitle()));
		return $this->getLayout()->getBlock('breadcrumbs')->toHtml();
	}
	
    public function getReviews(){ 
		$item = $this->getDetails();
		$collection =  Mage::getResourceModel('gallery/review_collection');
		$collection->getSelect()->where('status = ?', 1)->where('gallery_id = ?', $item->getGalleryId());
		return $collection;
    }
	
    public function getTotalRates(){ 
		$item = $this->getDetails();
		$collection =  Mage::getResourceModel('gallery/review_collection');
		$collection->getSelect()->where('status = ?', 1)->where('gallery_id = ?', $item->getGalleryId());
		$rate=0;
		$arr = array();
		if(count($collection)){
			foreach($collection as $record){
				$rate += $record->getReviewRate();
			}
			$arr['rate'] = ( $rate/ (count($collection)* 5) ) *100;
			$arr['total'] = count($collection);
		}
		return $arr;
    }
	
    
}