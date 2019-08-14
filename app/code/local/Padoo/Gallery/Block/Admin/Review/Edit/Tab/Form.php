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
 
class Padoo_Gallery_Block_Admin_Review_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('review_form', array('legend'=>Mage::helper('gallery')->__('Review information')));
     
	  $gallerys = array(array('value' => '', 'label' => 'Select an photo'));
	  $collection = Mage::getModel('gallery/gallery')->getCollection();
	  foreach ($collection as $album) {
		 $gallerys[] = array('value' => $album->getId(), 'label' => $album->getTitle());
	  }

	   $fieldset->addField('review_name', 'text', array(
          'label'     => Mage::helper('gallery')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'review_name',
      ));
	  
      $fieldset->addField('review_email', 'text', array(
          'label'     => Mage::helper('gallery')->__('Email'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'review_email',
      ));
	  
      $fieldset->addField('gallery_id', 'select', array(
          'label'     => Mage::helper('gallery')->__('Photo'),
          'name'      => 'gallery_id',
          'required'  => true,
          'values'    => $gallerys,
      ));

		
      $fieldset->addField('review_content', 'editor', array(
          'name'      => 'review_content',
          'label'     => Mage::helper('gallery')->__('Content'),
          'title'     => Mage::helper('gallery')->__('Content'),
          'style'     => 'width:700px; height:200px;',
          'wysiwyg'   => false
      ));
	  
/*     $fieldset->addField('url_key', 'text', array(
          'label'     => Mage::helper('gallery')->__('Url key'),
          'required'  => false,
          'name'      => 'url_key',
      ));*/
      $fieldset->addField('review_rate', 'select', array(
          'label'     => Mage::helper('gallery')->__('Rate'),
          'name'      => 'review_rate',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('gallery')->__('1'),
              ),
              array(
                  'value'     => 2,
                  'label'     => Mage::helper('gallery')->__('2'),
              ),
              array(
                  'value'     => 3,
                  'label'     => Mage::helper('gallery')->__('3'),
              ),
              array(
                  'value'     => 4,
                  'label'     => Mage::helper('gallery')->__('4'),
              ),
              array(
                  'value'     => 5,
                  'label'     => Mage::helper('gallery')->__('5'),
              ),
          ),
      ));
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('gallery')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('gallery')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('gallery')->__('Disabled'),
              ),
          ),
      ));
      
      $fieldset->addField('order', 'text', array(
          'label'     => Mage::helper('gallery')->__('Order'),
          'class'     => 'validate-zero-or-greater input-text validation-failed',
          'required'  => false,
          'name'      => 'order',
      ));
      if ( Mage::getSingleton('adminhtml/session')->getReviewData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getReviewData());
          Mage::getSingleton('adminhtml/session')->setReviewData(null);
      } elseif ( Mage::registry('review_data') ) {
          $form->setValues(Mage::registry('review_data')->getData());
      }
      return parent::_prepareForm();
  }
}