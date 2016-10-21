<?php

/**
 * Dropfin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade 
 * this extension to newer versions in the future. 
 *
 * @category    Dropfin
 * @package     Autosearch
 * @copyright   Copyright (c) Dropfin (http://www.dropfin.com)
 */

class Dropfin_Autosearch_Helper_Data extends Mage_Core_Helper_Abstract {

    const XML_PATH_ENABLED = 'dropfin_autosearch/configurations/enabled';
    const XML_PATH_SEARCHBOX_TEXT = 'dropfin_autosearch/configurations/search_box_text';
    const XML_PATH_SEARCH_MIN_COUNT = 'dropfin_autosearch/configurations/search_min_count';
    const XML_PATH_SEARCH_ITEM_COUNT = 'dropfin_autosearch/configurations/search_item_count';
    const XML_PATH_SUGGESTION_WORD = 'dropfin_autosearch/configurations/suggestion_word';
    const XML_PATH_SUGGESTION_WORD_COUNT = 'dropfin_autosearch/configurations/suggestion_word_count';
    const XML_PATH_SUGGESTION_SEARCH_BOX_SETTINGS = 'dropfin_autosearch/configurations/search_box_settings';

	public function getResultUrl() {
		return Mage::helper('catalogsearch')->getResultUrl();
	}

	public function getQueryParamName() {
		return Mage::helper('catalogsearch')->getQueryParamName();
	}

	public function getMaxQueryLength() {
		return Mage::helper('catalogsearch')->getMaxQueryLength();
	}

	public function getEscapedQueryText() {
		return Mage::helper('catalogsearch')->getEscapedQueryText();
	}

	public function getSuggestUrl() {
        return $this->_getUrl('autosearch', array(
            '_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()
        ));
    }

    public function getSearchBoxDefaultText() {
        return Mage::getStoreConfig(self::XML_PATH_SEARCHBOX_TEXT);
    }

    public function searchQueryLength() {
        $config = Mage::getStoreConfig(self::XML_PATH_SEARCH_MIN_COUNT);
        $wc = 2;
        if ((int) $config != 0) {
            $wc = (int) $config;
        }
        return $wc;
    }

    public function getSearchItemCount() {
        $config = Mage::getStoreConfig(self::XML_PATH_SEARCH_ITEM_COUNT);
        $ic = 5;
        if ((int) $config != 0) {
            $ic = (int) $config;
        }
        return $ic;
    }

    public function getSuggestionWord() {
        return Mage::getStoreConfig(self::XML_PATH_SUGGESTION_WORD);
    }

    public function getSuggestionWordCount() {
        $config = Mage::getStoreConfig(self::XML_PATH_SUGGESTION_WORD_COUNT);
        $swc = 5;
        if ((int) $config != 0) {
            $swc = (int) $config;
        }
        return $swc;
    }

    public function getSearchBoxSettings() {
        return Mage::getStoreConfig(self::XML_PATH_SUGGESTION_SEARCH_BOX_SETTINGS);
    }
}
