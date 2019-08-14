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
 
class Padoo_Gallery_Admin_AlbumController extends Mage_Adminhtml_Controller_action
{
	protected function _isAllowed()
	{
		return Mage::getSingleton('admin/session')->isAllowed('gallery/albums');
	}
	
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('gallery/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Album Manager'), Mage::helper('adminhtml')->__('Album Manager'));
		
		return $this;
	}  
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('gallery/album')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('album_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('gallery/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('gallery/admin_album_edit'))
				->_addLeft($this->getLayout()->createBlock('gallery/admin_album_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gallery')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			//save store view
			$storeView = $data['stores'];
			$dataStore = "";
			foreach($storeView as $store){
				if($dataStore != "") $dataStore .=",";
				$dataStore .= $store;
			} 
			$data['store_id'] = $dataStore;
			
			if($_FILES['filename']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS . 'gallery' . DS;
					$uploader->save($path, $_FILES['filename']['name'] );
					
				} catch (Exception $e) {
					
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename'] = 'gallery/'.$_FILES['filename']['name'];
			} else {
				if(isset($data['filename']['delete']) && $data['filename']['delete'] == 1) {
					 $data['filename'] = '';
				} else {
					unset($data['filename']);
				}
			}
	  		
			$model = Mage::getModel('gallery/album');
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			

			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				
				// insert new url rewrite
				/* $suffix = ".html";
				if(!strlen($model->getUrlKey()))
				{
					$rq_path = trim(strtolower($model->getTitle())). $suffix;
				}else
				{
					$rq_path = $model->getUrlKey().$suffix;
				}
				while( is_int(strpos($rq_path,'  ')))
				{
					$rq_path = str_replace('  ',' ',$rq_path);
				}
				$rq_path = str_replace(' ','-',$rq_path);

				$url_rewrite_id = $data['url_rewrite_id']?$data['url_rewrite_id']:null;
				$rewriteModel = Mage::getModel('core/url_rewrite');
				$data = array(
					'url_rewrite_id'=> $url_rewrite_id,
					'is_system'		=> 1,
					'id_path'		=> 'gallery/album/'.$model->getId(),
					'request_path'	=> "gallery/".$rq_path,
					'target_path'	=> 'gallery/view/album/id/'.$model->getId(),
				);					
					$rewriteModel->setData($data);
                    $rewriteModel->save();
                    $data = array('album_id'=>$model->getId());
                    $data['url_key'] = str_replace($suffix,'',$rq_path);
                    $data['url_rewrite_id'] = $rewriteModel->getId(); */

				//Mage::getModel('gallery/album')->setData($data)->save();
            					
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('gallery')->__('Photo was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('gallery')->__('Unable to find photo to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('gallery/album');
				 
				$model->load($this->getRequest()->getParam('id'));
				$rewriteModel = Mage::getModel('core/url_rewrite')->load($model->getUrlRewriteId())->delete();
				$model->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Photo was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $albumIds = $this->getRequest()->getParam('album');
        if(!is_array($albumIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select photo(s)'));
        } else {
            try {
                foreach ($albumIds as $albumId) {
					$model = Mage::getModel('gallery/album')->load($albumId);
					$rewriteModel = Mage::getModel('core/url_rewrite')->load($model->getUrlRewriteId())->delete();
					$model->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($albumIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $albumIds = $this->getRequest()->getParam('album');
        if(!is_array($albumIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select photo(s)'));
        } else {
            try {
                foreach ($albumIds as $albumId) {
                    $album = Mage::getSingleton('gallery/album')
                        ->load($albumId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($albumIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'album.csv';
        $content    = $this->getLayout()->createBlock('gallery/admin_album_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'album.xml';
        $content    = $this->getLayout()->createBlock('gallery/admin_album_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
    
    public function saveOrderAction()
    {
    	$items = explode('|',$this->getRequest()->getParam('items'));
    	$model = Mage::getModel('gallery/album');
    	foreach($items as $item)
    	{
    		$_item = explode('_',$item);	//$_item[0] => id, $_item[1]=>order
    		$model->load($_item[0]);
    		$model->setOrder($_item[1]);
    		$model->save();
    	}
    	$this->_redirect('*/*/index');
    }
}
