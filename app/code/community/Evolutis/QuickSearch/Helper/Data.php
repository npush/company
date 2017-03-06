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

class Evolutis_QuickSearch_Helper_Data extends Mage_Core_Helper_Abstract {
	
	public function getNbWeight() {
		return 3; //au lieu d'une const car la classe fulltext réclame cette valeur
	}
	
	/*
	 * Retourne le type de recherche depuis la BDD core_config_data
	 * false = OR ; true = AND
	 * 
	 * Si pas trouvé dans la BDD alors les valeurs par défaut du XML sont utilisés
	 * 
	 * Utilisée dans la classe Fulltext->prepareResult
	 */
	public function getTypeSearch() {
		return (bool) Mage::getStoreConfig('evolutis_quicksearch/search/type', Mage::app()->getStore());
	}
	
	/*
	 * Retourne les valeurs des poids pour chaque Id de poids depuis la BDD core_config_data
	 * Id => poids
	 *
	 * Si pas trouvé dans la BDD alors les valeurs par défaut du XML sont utilisés
	 * 
	 * Utilisée dans la classe Fulltext->prepareResult
	 */
	public function getIdWithWeight() {
		$weights = array();

		for ($i = 1; $i <= $this->getNbWeight(); $i++) {
			$weight = Mage::getStoreConfig('evolutis_quicksearch/weight/weight_' . $i, Mage::app()->getStore());
			$weights[$i] = $weight;
		}
		return $weights;
	}

	/*
	 * Retourne les noms des poids pour chaque Id de poids depuis la BDD core_config_data
	 * Id => nom
	 *
	 * Si pas trouvé dans la BDD alors les valeurs par défaut du XML sont utilisés
	 * 
	 * Utilisée dans la classe AdminObserver->addFieldToAttributeEditForm
	 */
	public function getNameWithId() {
		$weights = array();

		for ($i = 1; $i <= $this->getNbWeight(); $i++) {
			$weightName = $this->__(Mage::getStoreConfig('evolutis_quicksearch/weight_name/weight_' . $i, Mage::app()->getStore()));
			$weights[$i] = $weightName;
		}
		
		return $weights;
	}
}
