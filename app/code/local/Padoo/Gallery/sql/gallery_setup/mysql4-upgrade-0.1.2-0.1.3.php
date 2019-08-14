<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('gallery_album'), 'identifier',
    'varchar(255) NOT NULL AFTER album_id'
);

/*$installer->getConnection()->addColumn($installer->getTable('gallery'), 'url_key',
    'VARCHAR( 255 ) NOT NULL AFTER url_rewrite_id'
);
$installer->getConnection()->addColumn($installer->getTable('gallery'), 'url_rewrite_id',
    'int( 11 ) NOT NULL AFTER album_id'
);
*/
$installer->endSetup();
$installer = $this;