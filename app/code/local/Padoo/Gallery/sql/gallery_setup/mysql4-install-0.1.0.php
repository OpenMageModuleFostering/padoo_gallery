<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('gallery_album')};
CREATE TABLE {$this->getTable('gallery_album')} (
  `album_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `content` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('gallery')};
CREATE TABLE {$this->getTable('gallery')} (
  `gallery_id` int(11) unsigned NOT NULL auto_increment,
  `album_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `content` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL, 
  `update_time` datetime NULL,
  PRIMARY KEY (`gallery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('gallery_review')};
CREATE TABLE {$this->getTable('gallery_review')} (
  `review_id` int(11) unsigned NOT NULL auto_increment,
  `gallery_id` int(11) unsigned NOT NULL,
  `review_name` varchar(255) NOT NULL default '',
  `review_email` varchar(255) NOT NULL default '',
  `review_content` text NOT NULL default '',
  `review_rate` smallint(6) NOT NULL default '0',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL, 
  `update_time` datetime NULL,
  `order` tinyint(4) NOT NULL default '0',
  PRIMARY KEY (`review_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup(); 