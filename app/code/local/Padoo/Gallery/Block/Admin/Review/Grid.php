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
 
class Padoo_Gallery_Block_Admin_Review_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('reviewGrid');
      $this->setDefaultSort('review_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
      $this->setTemplate('gallery/grid.phtml');
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('gallery/review')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('review_id', array(
          'header'    => Mage::helper('gallery')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'review_id',
      ));
      $this->addColumn('review_name', array(
          'header'    => Mage::helper('gallery')->__('Name'),
          'align'     =>'left',
          'index'     => 'review_name',
      	  'width'	  => '100px',
      ));
      $this->addColumn('review_email', array(
          'header'    => Mage::helper('gallery')->__('Email'),
          'align'     =>'left',
          'index'     => 'review_email',
      ));

	  $gallerys = array();
	  $collection = Mage::getModel('gallery/gallery')->getCollection();
	  foreach ($collection as $gallery) {
		 $gallerys[$gallery->getId()] = $gallery->getTitle();
	  }
	  
	  $this->addColumn('gallery_id', array(
          'header'    => Mage::helper('gallery')->__('Photo'),
          'align'     =>'left',
		  'width'     => '100px',
          'index'     => 'gallery_id',
		  'type'      => 'options',
          'options'   => $gallerys,
      ));
	  $this->addColumn('review_rate', array(
          'header'    => Mage::helper('gallery')->__('Rate'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'review_rate',
          'type'      => 'options',
          'options'   => array(
              1 => '1',
              2 => '2',
              3 => '3',
              4 => '4',
              5 => '5',
          ),
      ));
	$this->addColumn('order', array(
          'header'    => Mage::helper('gallery')->__('Order'),
          'align'     =>'left',
          'index'     => 'order',
		  'width'     => '80px',
      ));
	  /*
      $this->addColumn('content', array(
			'header'    => Mage::helper('gallery')->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */

      $this->addColumn('status', array(
          'header'    => Mage::helper('gallery')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('gallery')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('gallery')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('gallery')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('gallery')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('gallery_id');
        $this->getMassactionBlock()->setFormFieldName('gallery');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('gallery')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('gallery')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('gallery/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('gallery')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('gallery')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }
	public function getThumbnailSize()
	{
			$size = trim(Mage::getStoreConfig('gallery/info/backend_thumbnail_size'),' ');
			$tmp = explode('-',$size);
			if(sizeof($tmp)==2)
				return array('width'=>is_numeric($tmp[0])?$tmp[0]:85,'height'=>is_numeric($tmp[1])?$tmp[1]:65);
			return array('width'=>85,'height'=>65);
	}
}
