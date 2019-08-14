<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('gallery_album'), 'store_id',
    'varchar(255) NOT NULL AFTER update_time'
);

$installer->getConnection()->addColumn($installer->getTable('gallery'), 'store_id',
    'VARCHAR( 255 ) NOT NULL AFTER update_time'
);
$installer->getConnection()->addColumn($installer->getTable('gallery_review'), 'store_id',
    'VARCHAR( 255 ) NOT NULL AFTER update_time'
);

$installer->endSetup();
$installer = $this;