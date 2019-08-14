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
 
class Padoo_Gallery_Block_Admin_Album_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('album_form', array('legend'=>Mage::helper('gallery')->__('Album information')));
      
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('gallery')->__('Album Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
	  
		if (!Mage::app()->isSingleStoreMode()) {
			$fieldset->addField('store_id', 'multiselect', array(
				'name' => 'stores[]',
				'label' => Mage::helper('gallery')->__('Store View'),
				'title' => Mage::helper('gallery')->__('Store View'),
				'required' => true,
				'values' => Mage::getSingleton('adminhtml/system_store')
							 ->getStoreValuesForForm(false, true),
			));
		}
		else {
			$fieldset->addField('store_id', 'hidden', array(
				'name' => 'stores[]',
				'value' => Mage::app()->getStore(true)->getId()
			));
		}
      $fieldset->addField('filename', 'image', array(
          'label'     => Mage::helper('gallery')->__('Photo'),
          'required'  => true,
          'name'      => 'filename',
	  ));
     $fieldset->addField('url_key', 'text', array(
          'label'     => Mage::helper('gallery')->__('Url key'),
          'required'  => false,
          'name'      => 'url_key',
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
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('gallery')->__('Description'),
          'title'     => Mage::helper('gallery')->__('Description'),
          'style'     => 'width:700px; height:200px;',
          'wysiwyg'   => false,
      ));
      $fieldset->addField('url_rewrite_id', 'hidden', array(
          'name'      => 'url_rewrite_id',
      ));
      if ( Mage::getSingleton('adminhtml/session')->getGalleryData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getGalleryData());
          Mage::getSingleton('adminhtml/session')->setGalleryData(null);
      } elseif ( Mage::registry('album_data') ) {
          $form->setValues(Mage::registry('album_data')->getData());
      }
      return parent::_prepareForm();
  }
}