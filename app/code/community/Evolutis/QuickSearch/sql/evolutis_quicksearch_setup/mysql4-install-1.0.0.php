<?php

/**
 * Evolutis_QuickSearch
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@evolutis.fr so we can send you a copy immediately.
 *
 * @category    QuickSearch
 * @package     Evolutis_QuickSearch
 * @copyright  Copyright (c) 2001-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
$installer->startSetup();

/*
 * Ligne de base propre à Magento (CatalogSearch_fulltext) et reproduit dans notre table : 
 * product_id, store_id, data_index
 * 
 * data_index = tous les champs concaténés sans soucis de poids
 * data_index_1 = tous les champs cacaténés ayant le poids d'Id 1
 * data_index_2 = tous les champs cacaténés ayant le poids d'Id 2
 * data_index_3 = tous les champs cacaténés ayant le poids d'Id 3
 * 
 * 
 * ALTER TABLE afin de créer un nouvel attribut dans catalog_eav_attribut.
 * Nom => "weight_id", référence l'Id d'un poids dans le XML ou BDD (core_config_data), default à 2 qui est l'id du poids dit "normal"
 */
$installer->run("
	CREATE TABLE `{$this->getTable('evolutis_quicksearch/fulltext')}` (
		 `product_id` int(10) unsigned NOT NULL,
		 `store_id` smallint(5) unsigned NOT NULL,
		 `data_index` longtext NOT NULL,
		 `data_index_1` longtext NOT NULL,
		 `data_index_2` longtext NOT NULL,
		 `data_index_3` longtext NOT NULL,
		 PRIMARY KEY (`product_id`,`store_id`),
		 FULLTEXT KEY `data_index` (`data_index`),
		 FULLTEXT KEY `data_index_1` (`data_index_1`),
		 FULLTEXT KEY `data_index_2` (`data_index_2`),
		 FULLTEXT KEY `data_index_3` (`data_index_3`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
	ALTER TABLE `{$installer->getTable('catalog/eav_attribute')}` ADD `evolutis_weight_id` INT(10) NOT NULL DEFAULT '2';
");

/*
 * Arès la création de notre Fulltext, il faut le générer afin que la recherche refonctionne !
 * 
 * Signalement à l'utilisateur par un FLAG
 */
Mage::getSingleton('index/indexer')->getProcessByCode('catalogsearch_fulltext')->changeStatus(Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX);

$installer->endSetup();