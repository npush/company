<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 1/25/17
 * Time: 1:25 PM
 */

class Stableflow_Autosuggest_Block_Autosuggest extends Mage_Core_Block_Template{

    protected $searchResult = null;

    protected $_suggestData = null;

    public function search(){

        $layer = Mage::getSingleton('catalogsearch/layer');
        $this->searchResult = $layer->getProductCollection();
        return;

        $catalogSearchModel = $this->_getQuery()->getResultCollection();
        $catalogSearchModel->addAttributeToFilter('visibility', array('neq' => 1));
        $catalogSearchModel->addAttributeToSelect('manufacturer_number');
        $catalogSearchModel->addAttributeToSelect('name');
        $catalogSearchModel->addAttributeToSelect('thumbnail');
        $catalogSearchModel->addAttributeToSelect('small_image');
        $catalogSearchModel->addAttributeToSelect('url_key');
        //$catalogSearchModel->addAttributeToSort('name', 'ASC');
        //$catalogSearchModel->setOrder(array('manufacturer_number', 'name'), 'asc');
        $catalogSearchModel->getSelect()->limit(Mage::helper('autosuggest/config')->getSearchItemCount());

        if(count($catalogSearchModel)){
            $this->searchResult = $catalogSearchModel;
        }
    }

    public function getSearchResult(){
        return $this->searchResult;
    }

    /**
     * Retrieve query model object
     *
     * @return Mage_CatalogSearch_Model_Query
     */
    protected function _getQuery()
    {
        return $this->helper('catalogsearch')->getQuery();
    }

    public function popularSuggestions(){
        $resultText = '';
        $suggestionWord = (int) Mage::helper('autosuggest')->getSuggestionWord();
        if ($suggestionWord == 1) {

            $suggests = Mage::helper('catalogsearch')->getSuggestCollection();
            if (count($suggests) > 0) {
                $slimit = Mage::helper('autosuggest')->getSuggestionWordCount();
                $sc = 0;
                $resultText .= '<li class="list-title">' . Mage::helper('autosuggest')->__('Popular suggestions:') . '</li>';
                foreach ($suggests as $_suggest) {
                    if ($sc < $slimit) {
                        $resultText .= "<li class=\"item\" onclick=\"document.getElementById('search').value='{$_suggest->getQueryText()}';document.getElementById('search_mini_form').submit()\">{$_suggest->getQueryText()} <span class=\"amount\">{$_suggest->getNumResults()}</span></li>";
                        $sc++;
                    } else {
                        break;
                    }
                }
            }
        }
    }

    public function getSuggestData()
    {
        if (!$this->_suggestData) {
            $collection = $this->helper('catalogsearch')->getSuggestCollection();
            $query = $this->helper('catalogsearch')->getQueryText();
            $counter = 0;
            $data = array();
            foreach ($collection as $item) {
                $_data = array(
                    'title' => $item->getQueryText(),
                    'row_class' => (++$counter)%2?'odd':'even',
                    'num_of_results' => $item->getNumResults()
                );

                if ($item->getQueryText() == $query) {
                    array_unshift($data, $_data);
                }
                else {
                    $data[] = $_data;
                }
            }
            $this->_suggestData = $data;
        }
    }

}