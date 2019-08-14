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
 
class Padoo_Gallery_Block_Page_Pager extends Mage_Core_Block_Template
{
    protected $_collection = null;
    protected $_pageVarName     = 'page';
    protected $_limitVarName    = 'limit';
    protected $_availableLimit  = array(5=>5);
    protected $_dispersion      = 3;
    protected $_displayPages    = 5;
    protected $_showPerPage		= true;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('gallery/photo/pager.phtml');
    }   
    
    public function getCurrentPage()
    {
        if ($page = (int) $this->getRequest()->getParam($this->getPageVarName())) {
            return $page;
        }
        return 1;
    }

    public function getLimit()
    {
        $limits = $this->getAvailableLimit();
        if ($limit = $this->getRequest()->getParam($this->getLimitVarName())) {
            if (isset($limits[$limit])) {
                return $limit;
            }
        }
        $limits = array_keys($limits);
        return Mage::getStoreConfig('gallery/info/images_per_page_default_value') ? Mage::getStoreConfig('gallery/info/images_per_page_default_value'):4;
    }

    public function setCollection($collection)
    {
    	//var_dump($this->_collection); exit;
        $this->_collection = $collection
            ->setCurPage($this->getCurrentPage());
        // If not int - then not limit
        if ((int) $this->getLimit()) {
            $this->_collection->setPageSize($this->getLimit());
        }
        return $this;
    }

    /**
     * @return Mage_Core_Model_Mysql4_Collection_Abstract
     */
    public function getCollection()
    {
    	//Mage_Core_Model_Mysql4_Collection_Abstract
    	return $this->_collection;
    }

    public function setPageVarName($varName)
    {
        $this->_pageVarName = $varName;
        return $this;
    }

    public function getPageVarName()
    {
        return $this->_pageVarName;
    }

    public function setShowPerPage($varName)
    {
    	$this->_showPerPage=$varName;
    }

    public function getShowPerPage()
    {
		if( Mage::getStoreConfig('gallery/info/enabled_pagination')){
			if(sizeof($this->getAvailableLimit())<=1) {
				return false;
			}
			return $this->_showPerPage;
		}else{
			return false;
		}
    }

    public function setLimitVarName($varName)
    {
        $this->_limitVarName = $varName;
        return $this;
    }

    public function getLimitVarName()
    {
        return $this->_limitVarName;
    }

    public function setAvailableLimit(array $limits)
    {
    	$this->_availableLimit = array();
    	$limits = Mage::getStoreConfig('gallery/info/images_per_page_allowed') ? Mage::getStoreConfig('gallery/info/images_per_page_allowed'):'4,8,12';
    	$limits = explode(',',$limits);
    	foreach($limits as $limit){
    		if(is_numeric($limit)) { $this->_availableLimit[$limit] = $limit;} 
    	}
       // $this->_availableLimit = $limits;
    }

    public function getAvailableLimit()
    {
    	$this->_availableLimit = array();
    	$limits = Mage::getStoreConfig('gallery/info/images_per_page_allowed') ? Mage::getStoreConfig('gallery/info/images_per_page_allowed'):'4,8,12';
    	$limits = explode(',',$limits);
    	foreach($limits as $limit){
    		$this->_availableLimit[$limit] = $limit; 
    	}
        return $this->_availableLimit;
    }

    public function getFirstNum()
    {
        $collection = $this->getCollection();
        return $collection->getPageSize()*($collection->getCurPage()-1)+1;
    }

    public function getLastNum()
    {
        $collection = $this->getCollection();
        return $collection->getPageSize()*($collection->getCurPage()-1)+$collection->count();
    }

    public function setKeyword($keyword){
		$this->_searchWord = $keyword;
		return $this;
	}
    public function getTotalNum()
    {
		$_keyword = $_GET['keyword'];
		$albumId  = $this->getRequest()->getParam('id');
		$collection = Mage::getResourceModel('gallery/gallery_collection');
		if(!empty($_keyword)){
			$exceptstrangesign = preg_replace('#[^0-9a-z]+#i', ' ', $_keyword);
			$inputarrayword= array();
			$inputarrayword=explode(' ',$exceptstrangesign);
			$where ="";
			for($i=0;$i<count($inputarrayword);$i++)
			{
				$where .= "title like '%".$inputarrayword[$i]."%' OR content like '%".$inputarrayword[$i]."%' OR ";
				/* $where .= "title like '%".$inputarrayword[$i]."%' OR "; */
			}
			$where = substr($where,0,strlen($where)-4); 
			$collection->getSelect()->where($where);
		}else{
			$collection->getSelect()->where('album_id = ?', $albumId);	
		}
		$collection->getSelect()->where('status = ?', 1);	
		$storeId = Mage::app()->getStore(true)->getId();
	    $data = array();
		foreach ($collection as $record) {
			$stores 	= $record->getStoreId();
			if(strpos($stores,$storeId) !== false || strpos($stores,0) !== false || $stores == 0 ){
				$data[$record->getId()] = $record;
			}
		}
        if($data){
        	return sizeof($data);
        }else{
        	return 0;
        }
    }

    public function isFirstPage()
    {
        return $this->getCollection()->getCurPage() == 1;
    }

    public function getLastPageNum()
    {
        return $this->getCollection()->getLastPageNumber();
    }

    public function isLastPage()
    {
        return $this->getCollection()->getCurPage() >= $this->getLastPageNum();
    }

    public function isLimitCurrent($limit)
    {
        return $limit == $this->getLimit();
    }

    public function isPageCurrent($page)
    {
        return $page == $this->getCurrentPage();
    }

    public function getPages()
    {
        $collection = $this->getCollection();

        $pages = array();
        if ($collection->getLastPageNumber() <= $this->_displayPages) {
            $pages = range(1, $collection->getLastPageNumber());
        }
        else {
            $half = ceil($this->_displayPages / 2);
            if ($collection->getCurPage() >= $half && $collection->getCurPage() <= $collection->getLastPageNumber() - $half) {
                $start  = ($collection->getCurPage() - $half) + 1;
                $finish = ($start + $this->_displayPages) - 1;
            }
            elseif ($collection->getCurPage() < $half) {
                $start  = 1;
                $finish = $this->_displayPages;
            }
            elseif ($collection->getCurPage() > ($collection->getLastPageNumber() - $half)) {
                $finish = $collection->getLastPageNumber();
                $start  = $finish - $this->_displayPages + 1;
            }

            $pages = range($start, $finish);
        }

        return $pages;

//        $pages = array();
//        for ($i=$this->getCollection()->getCurPage(-$this->_dispersion); $i <= $this->getCollection()->getCurPage(+($this->_dispersion-1)); $i++)
//        {
//
//            $pages[] = $i;
//        }
//
//        return $pages;
    }

    public function getFirstPageUrl()
    {
        return $this->getPageUrl(1);
    }

    public function getPreviousPageUrl()
    {
        return $this->getPageUrl($this->getCollection()->getCurPage(-1));
    }

    public function getNextPageUrl()
    {
        return $this->getPageUrl($this->getCollection()->getCurPage(+1));
    }

    public function getLastPageUrl()
    {
        return $this->getPageUrl($this->getCollection()->getLastPageNumber());
    }

    public function getPageUrl($page)
    {
        return $this->getPagerUrl(array($this->getPageVarName()=>$page));
    }

    public function getLimitUrl($limit)
    {
        return $this->getPagerUrl(array($this->getLimitVarName()=>$limit));
    }

    public function getPagerUrl($params=array())
    {
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = $params;
        return $this->getUrl('*/*/*', $urlParams);
    }
    
}