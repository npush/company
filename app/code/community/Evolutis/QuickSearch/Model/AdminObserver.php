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

class Evolutis_QuickSearch_Model_AdminObserver {
	
	/*
	 * Oberser qui se déclenche lors de la construction du formulaire d'édition d'attribut : adminhtml_catalog_product_attribute_edit_prepare_form
	 * On y ajoute le champs poids pour que l'utilisateur puisse choisir le niveau d'importante qu'il possède dans la recherche.
	 * 
	 * Ce poids n'est prit en compte que si l'attribut est utilisé dans la Quick Search.
	 */
	public function addFieldToAttributeEditForm($observer) {
		$fieldset = $observer->getForm()->getElement('front_fieldset');
		
		$helper = Mage::helper('evolutis_quicksearch');
		
		$fieldset->addField('evolutis_weight_id', 'select', array(
				'name' => 'evolutis_weight_id',
				'label' => $helper->__('Search weight'),
				'title' => $helper->__('Search weight'),
                'values'=> $helper->getNameWithId(),
				'class' => 'validate-digits'
		));
	}

	/*
	 * Oberser qui se déclenche avant de l'enregistrement d'un formulaire d'un model quelconque : model_save_before
	 * Correspond au formulaire de parametrage du module dans System > Configuration
	 * 
	 * Puisqu'il est appelé par toutes les sauvegardes de formulaire, on test si l'objet de données qu'on a dans la main est du type Mage_Core_Model_Config_Data (page configuration de system)
	 * Puis on test si on modifie notre module
	 * Enfin on test si la valeur du champs type est différente de l'ancienne.
	 * Si les conditions sont respectés, on efface les résultats de recherches pour qu'ils soient recréés lors d'une nouvelle. On informe l'utilisateur également.
	 */
	public function clearResultAfterChangeConfiguration($observer) {			
		try {
			$object = $observer->getEvent()->getData()['object'];
			$helper = Mage::helper('evolutis_quicksearch');

			if ($object instanceof Mage_Core_Model_Config_Data && $object->getData("path") == 'evolutis_quicksearch/search/type') {
				//tableau des anciens poids (actuels)
				$weightsOld = $helper->getIdWithWeight();
				//tableau des nouveaux poids provenant du formulaire
				$weightNew = $object->getData('groups')['weight']['fields'];
				
				$idWeight = 1;
				//on boucle tant que les poids sont identiques
				while ($idWeight <= count($weightsOld) && $weightsOld[$idWeight] == $weightNew['weight_' . $idWeight]['value']) {
					$idWeight++;
				}

				//on change le type de recherche donc on efface les résultats si ce n'était pas le même type ou un poids qui a changé
				if (($idWeight - 1) != count($weightNew) || (bool) $object->getData("value") != $helper->getTypeSearch()) {
					Mage::getModel('catalogsearch/fulltext')->resetSearchResults();
				}
			}
		} catch (Exception $e) {
		}
	}

	/*
	 * Oberser qui se déclenche avant l'enregistrement du formulaire d'édition d'attribut : catalog_entity_attribute_save_before
	 * 
	 * Si sa valeur a changé alors la table evolutis_quicksearch_fulltext doit être totalement recréée.
	 * Un FLAG reindex pour la Fulltext est levé afin que l'utilisateur sache ce qu'il doit faire.
	 */
	public function addFlagReindexAfterSaveAttribute($observer) {
		$attribute = $observer->getEvent()->getAttribute();
		
		if ($attribute->getOrigData('evolutis_weight_id') != $attribute->getData('evolutis_weight_id')) {
			//on change le poids d'un attribut donc on demande à reindexer si ce n'était pas le même
			Mage::getSingleton('index/indexer')->getProcessByCode('catalogsearch_fulltext')->changeStatus(Mage_Index_Model_Process::STATUS_REQUIRE_REINDEX);
		}
	}
}