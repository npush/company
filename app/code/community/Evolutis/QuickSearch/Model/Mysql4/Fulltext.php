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

class Evolutis_QuickSearch_Model_Mysql4_Fulltext extends Mage_CatalogSearch_Model_Mysql4_Fulltext {
	
	protected function _construct() {
		$this->_init('evolutis_quicksearch/fulltext', 'product_id');
       	$this->_engine = Mage::helper('catalogsearch')->getEngine();
       	$this->_helper = Mage::helper('evolutis_quicksearch');
	}
				
	public function prepareResult($object, $queryText, $query) {
		//Récupération des poids
		$weights = $this->_helper->getIdWithWeight();
		$nbWeight = count($weights);
		$andSearch = $this->_helper->getTypeSearch(); //false = or ; true = and
		
		/*
		 * Requête générée pour recherches (mode débug):
		 * 
		 * Ici nous recherchons les termes "1234 4567 coucou" en mode "ET"
		 * 
		 * Le Match de MySQL retourne un nombre décimal correspondant à la pertinence de la recherche passée dans AGAINST pour la colonne passé dans MATCH
		 * Ce résultat est multiplié par le poids afin d'éviter de nous retrouvé avec des relevances (pertinences = la valeur) en doubles. Ceci pour mettre des priorités.
		 * Le Match en mode ET est utilisé avec des + devant chaque mots.
		 * Le Match ne fonctionne pas avec moins de 4 caractères (limitation MySQL) donc il faut se servir du LIKE à la fin.
		 * Nous décomposons dont nos 3 termes que nous lions avec des AND dans notre cas (OR sinon).
		 * Afin que la recherche retourne des résultats avec le mode ET alors qu'ils ne sont pas tous présents dans le même champs "poids", 
		 * nous appliquons un Match sur le champs data_index qui les comporte tous. 
		 * 
		 * data_index = tous les champs concaténés sans soucis de poids
		 * data_index_1 = tous les champs cacaténés ayant le poids d'Id 1
		 * data_index_2 = tous les champs cacaténés ayant le poids d'Id 2
		 * data_index_3 = tous les champs cacaténés ayant le poids d'Id 3
		
		 	set @query = '+"1234" +"4567" +"coucou"';

			SELECT *,
			
			match(`data_index`) against(@query in boolean mode) as relevance,
			
			match(`data_index_1`) against(@query in boolean mode) as relevance_1,
			match(`data_index_2`) against(@query in boolean mode) as relevance_2,
			match(`data_index_3`) against(@query in boolean mode) as relevance_3,
			
			(
			    match(`data_index_1`) against(@query in boolean mode) * 1 + 
			
			    match(`data_index_2`) against(@query in boolean mode) * 2 +
			    match(`data_index_3`) against(@query in boolean mode) * 3
			) * match(`data_index`) against(@query in boolean mode)as relevanceTotal
			
			FROM `evolutis_quicksearch_fulltext`
			
			WHERE
			
			match(`data_index`) against(@query in boolean mode) OR 
			(`data_index` LIKE '%1234%' AND `data_index` LIKE '%4567%' AND `data_index` LIKE '%coucou%'))
			
			ORDER BY relevanceTotal DESC
		*/
		
		
		
		/*
		 * Code original Magento recopié
		 */
		$adapter = $this->_getWriteAdapter();
        if (!$query->getIsProcessed()) {
            $searchType = $object->getSearchType($query->getStoreId());

            $preparedTerms = Mage::getResourceHelper('catalogsearch')
                ->prepareTerms($queryText, $query->getMaxQueryWords());
            
            $bind = array();
            $like = array();
            $likeCond  = '';
            if ($searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_LIKE
                || $searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_COMBINE
            ) {
                $helper = Mage::getResourceHelper('core');
                $words = Mage::helper('core/string')->splitWords($queryText, true, $query->getMaxQueryWords());
                foreach ($words as $word) {
                    $like[] = $helper->getCILike('s.data_index', $word, array('position' => 'any'));
                }
                if ($like) {
                	/*
                	 * Modification pour le type de recherce
                	 */
                	$operator = ($andSearch ? ' AND ' : ' OR ');
                    $likeCond = '(' . join($operator, $like) . ')';
                }
            }
            $mainTableAlias = 's';
            $fields = array(
                'query_id' => new Zend_Db_Expr($query->getId()),
                'product_id',
            );
            $select = $adapter->select()
                ->from(array($mainTableAlias => $this->getMainTable()), $fields)
                ->joinInner(array('e' => $this->getTable('catalog/product')),
                    'e.entity_id = s.product_id',
                    array())
                ->where($mainTableAlias.'.store_id = ?', (int)$query->getStoreId());

            if ($searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_FULLTEXT
	                || $searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_COMBINE) {
            	$operator = ($andSearch ? ' +' : ' ');
            	//enlever les " pour que match autocomplete les mots
                $bind[':query'] = implode($operator, $preparedTerms[0]);
                
                //pour le where on match sur la première colonne data_index afin que les résultats ressortent
                $where = 'MATCH ('.$mainTableAlias.'.data_index) AGAINST (:query IN BOOLEAN MODE)';
                
                //ici match sql
	        	$match = "";
	            for ($i = 1; $i <= $nbWeight; $i++) {                
	            	$match .= 'MATCH ('.$mainTableAlias.'.data_index_' . $i . ') AGAINST (:query IN BOOLEAN MODE) * ' . $weights[$i];
	                if ($i != $nbWeight) {
	                	$match .= ' + ';
	                }
	            }
	            $relevance = new Zend_Db_Expr('(' . $match . ') * ' . $where);
       			$select->columns(array('relevance' => $relevance));  
			}

            if ($likeCond != '' && $searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_COMBINE) {
                    $where .= ($where ? ' OR ' : '') . $likeCond;
            } elseif ($likeCond != '' && $searchType == Mage_CatalogSearch_Model_Fulltext::SEARCH_TYPE_LIKE) {
                $select->columns(array('relevance'  => new Zend_Db_Expr(0)));
                $where = $likeCond;
            }

            if ($where != '') {
                $select->where($where);
            }

           	$sql = $adapter->insertFromSelect($select,
                $this->getTable('catalogsearch/result'),
                array(),
                Varien_Db_Adapter_Interface::INSERT_ON_DUPLICATE);
            $adapter->query($sql, $bind);
            $query->setIsProcessed(1);
        }
        
        return $this;
	}
		
	protected function _saveProductIndexes($storeId, $productIndexes) {
		$nbWeight = $this->_helper->getNbWeight(); //Nombre de poids maximum
		
		$valuesIntero = array(); //Les ? pour la requête SQL
		$fulltextValues = array(); //Les valeurs à enregistrer dans les champs de la table evolutis_quicksearch_fulltext
		
		//Pour chaque produits on veut son Id et son tableau d'attributs => valeurs
		foreach ($productIndexes as $productId => $index) {
			//Ligne de base propre à Magento (cuicksearch_fulltext) et reproduit dans notre table : product_id, store_id, data_index
			$rowBase = array($productId, $storeId, '?');
			
			for ($i = 0; $i < $nbWeight; $i++) {
				//Les ? en plus pour chaque poids
				$rowBase[] = '?';
			}

			//La chaîne temporaire qui contiendra les attributs concaténés à enregistrer dans les champs
			$all = "";
			//Tableau de regroupement des attributs par poids tableau[poids_id][attribut_id]
			$data_index = array();
			
			//Pour notre tableau d'attributs propre à notre produit (qu'on a dans la main), on veut son id => sa valeur
			foreach ($index as $entity_id => $value) {
				//On prend la valeur au premier l'indice (qui est une chaîne d'où le reset() qui pointe sur la première valeur) + '|' pour la séparation (arbitraire)
				$all .= reset($value) . '|';
				
				//Ce tableau est utilisé plus loin mais est initialisé ici
				//Pour la valeur de l'attribut poids nous enregistrons dans une "nouvelle case" la valeur de l'attribut
				$data_index[$value["weight_id"]][$entity_id] = reset($value);
			}
			//data_index full   On enlève le dernier |
			$fulltextValues[] = substr($all, 0, -1); 
			
			//Pour chaque poids
			for ($i = 1; $i <= $nbWeight; $i++) {
				if ($data_index[$i] == null) {
					$data_index[$i][] = "";
				}
				$fulltextValues[] = implode('|', $data_index[$i]);
			}
			
			//On crée des "rangés" de ? qui définissent chaque valeurs à rajouter dans la table
			$valuesIntero[] = '('.implode(',', $rowBase).')';
		}
		
		//On remplace ou insert dans notre table les champs de bases
		$sql = "REPLACE INTO `{$this->getMainTable()}`(product_id, store_id, data_index";
		//puis les data_index_i
		for ($i = 1; $i <= $nbWeight; $i++) {
			$sql .= ", data_index_" . $i;
		}
		//on ajoute les ? (autant qu'il en faut séparés par des ,)
		$sql .= ") VALUES" . join(',', $valuesIntero);

		//On lance avec notre tableau de valeurs généré juste avant :)
		$this->_getWriteAdapter()->query($sql, $fulltextValues);

		return $this;
	}
	
	protected function _prepareProductIndex($indexData, $productData, $storeId) {
		/*
		 * Code original Magento recopié
		 * 
		 * Seulement ajouté notre champs "weight_id" à l'index renvoyé pour chaque boucle que fait Magento
		 */
		$index = array();
		
		foreach ($this->_getSearchableAttributes('static') as $attribute) {
			$attributeCode = $attribute->getAttributeCode();
		
			if (isset($productData[$attributeCode])) {
				$value = $this->_getAttributeValue($attribute->getId(), $productData[$attributeCode], $storeId);
				if ($value) {
					//For grouped products
					if (isset($index[$attributeCode])) {
						if (!is_array($index[$attributeCode])) {
							$index[$attributeCode] = array($index[$attributeCode]);
						}
						$index[$attributeCode][] = $value;
					}
					//For other types of products
					else {
						$index[$attributeCode][] = $value;
					}
					//On ajoute notre attribut dans l'index
					$index[$attributeCode]["weight_id"] = $attribute->getEvolutisWeightId();
				}
			}
		}
			
		foreach ($indexData as $entityId => $attributeData) {
			foreach ($attributeData as $attributeId => $attributeValue) {
				$value = $this->_getAttributeValue($attributeId, $attributeValue, $storeId);
				if (!is_null($value) && $value !== false) {
					$attributeCode = $this->_getSearchableAttribute($attributeId)->getAttributeCode();
		
					if (isset($index[$attributeCode])) {
						$index[$attributeCode][$entityId] = $value;
					} else {
						$index[$attributeCode] = array($entityId => $value);
					}
					//On ajoute notre attribut dans l'index
					$index[$attributeCode]["weight_id"] = $this->_getSearchableAttribute($attributeId)->getEvolutisWeightId();
				}
			}
		}
		
		if (!$this->_engine->allowAdvancedIndex()) {
			$product = $this->_getProductEmulator()
				->setId($productData['entity_id'])
				->setTypeId($productData['type_id'])
				->setStoreId($storeId);
			$typeInstance = $this->_getProductTypeInstance($productData['type_id']);
			if ($data = $typeInstance->getSearchableData($product)) {
				$index['options'] = $data;
			}
		}
		
		if (isset($productData['in_stock'])) {
			$index['in_stock'][] = $productData['in_stock'];
			//On ajoute notre attribut dans l'index
			$index['in_stock']["weight_id"] = $this->_getSearchableAttribute($attributeId)->getEvolutisWeightId();
		}
	
		/*
		 * On retourne le tableau pour que la fonction appelante (_rebuildStoreIndex) qui appelera ensuite notre fonction _saveProductIndexes (surchargée) l'insert dans notre table
		 * 
		 * Le code de base retournait un $index "string" avec toutes les valeurs des attributs concaténés ce qui empêchait de trvailler avec. 
		 */
		return $index;
	}
}