<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 1/25/17
 * Time: 12:57 PM
 */
class Stableflow_Autosuggest_Helper_Config extends Mage_Core_Helper_Abstract{

    const XML_PATH_ENABLED = 'autosuggest/configurations/enabled';
    const XML_PATH_SEARCHBOX_TEXT = 'autosuggest/configurations/search_box_text';
    const XML_PATH_SEARCH_MIN_COUNT = 'autosuggest/configurations/search_min_count';
    const XML_PATH_SEARCH_ITEM_COUNT = 'autosuggest/configurations/search_item_count';
    const XML_PATH_SUGGESTION_WORD = 'autosuggest/configurations/suggestion_word';
    const XML_PATH_SUGGESTION_WORD_COUNT = 'autosuggest/configurations/suggestion_word_count';
    const XML_PATH_SUGGESTION_SEARCH_BOX_SETTINGS = 'autosuggest/configurations/search_box_settings';

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
        return $this->_getUrl('autosuggest/ajax/search', array(
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

    public function getRandomId(){
        return rand(100, 999);
    }
}