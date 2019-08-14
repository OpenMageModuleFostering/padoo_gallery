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
 
class Padoo_Gallery_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		if (!Mage::getStoreConfig('gallery/info/enabled')) $this->_redirect('no-route');	
		$this->loadLayout();     
		$this->renderLayout();

		//$this->_redirect('/');
    }
	
	public function searchAction()
    {
		if (!Mage::getStoreConfig('gallery/info/enabled')) $this->_redirect('no-route');	
		$this->loadLayout();     
		$this->renderLayout();
    }
	
    public function reviewAction(){
		if (!Mage::getStoreConfig('gallery/info/enabled')) $this->_redirect('no-route');
		$data = $this->getRequest()->getPost();
		$review = Mage::getModel('gallery/review');
		$session = Mage::getSingleton('core/session', array('name'=>'reviewfrontend'));
		try {
			$formId = 'gallery';
			$magentoVersion = Mage::getVersion();
			if (version_compare($magentoVersion, '1.7', '>=')){
				//version is 1.7 or greater
				$captchaModel = Mage::helper('captcha')->getCaptcha($formId);
				if ($captchaModel->isRequired()) {
					$word = $this->_getCaptchaString($this->getRequest(), $formId);
					if (!$captchaModel->isCorrect($word)) {
						Mage::getSingleton('core/session')->addError(Mage::helper('captcha')->__('Incorrect CAPTCHA.'));
						$this->_redirectReferer('');
						return;
					}
				}
			}
			
			$review->setGalleryId($data['gallery_id']);
			$review->setReviewName($data['name']);
			$review->setReviewEmail($data['email']);
			$review->setReviewContent($data['content']);
			$review->setReviewRate($data['gallery_rating']);
			$review->setCreatedTime(now());
			$review->setUpdateTime(now());
			$review->save();
			$session->addSuccess($this->__('Your review has been accepted'));
		}catch (Exception $e) {
			$session->setFormData($data);
			$session->addError($this->__('Unable to post review. Please, try again later !'));
		}
		
        if ($redirectUrl = Mage::getSingleton('core/session')->getRedirectUrl(true)) {
            $this->_redirectUrl($redirectUrl);
            return;
        }
        $this->_redirectReferer();		
    }
	
	protected function _getCaptchaString($request, $formId)
    {
        $captchaParams = $request->getPost(Mage_Captcha_Helper_Data::INPUT_NAME_FIELD_VALUE);
        return $captchaParams[$formId];
    }

}