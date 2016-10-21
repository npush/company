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

class Dropfin_Autosearch_IndexController extends Mage_Core_Controller_Front_Action {

    public function IndexAction() {

        $ic = Mage::helper('dropfin_autosearch')->getSearchItemCount();
        $limit = 5;
        if ((int) $ic != 0) {
            $limit = (int) $ic;
        }

        $query = Mage::helper('catalogsearch')->getQuery();
        $query->setStoreId(Mage::app()->getStore()->getId());
        if ($query->getRedirect()) {
            $query->save();
        } else {
            $query->prepare();
        }
        Mage::helper('catalogsearch')->checkNotes();

        $catalogSearchModel = $query->getResultCollection();
        $catalogSearchModel->addAttributeToFilter('visibility', array('neq' => 1));
        $catalogSearchModel->addAttributeToSelect('short_description');
        $catalogSearchModel->addAttributeToSelect('name');
        $catalogSearchModel->addAttributeToSelect('thumbnail');
        $catalogSearchModel->addAttributeToSelect('small_image');
        $catalogSearchModel->addAttributeToSelect('url_key');

        $resultText = '<div class="auto-suggest-content">';
        if (count($catalogSearchModel) > 0) {
            $resultText .= '<div class="suggest-word-content">';
            $resultText .= '<ul class="suggest-products">';
            $suggestionWord = (int) Mage::helper('dropfin_autosearch')->getSuggestionWord();
            if ($suggestionWord == 1) {

                $suggests = Mage::helper('catalogsearch')->getSuggestCollection();
                if (count($suggests) > 0) {

                    $slimit = 5;
                    $swc = Mage::helper('dropfin_autosearch')->getSuggestionWordCount();
                    if ((int) $swc != 0) {
                        $slimit = (int) $swc;
                    }

                    $sc = 0;
                    $resultText .= '<li class="list-title">' . Mage::helper('core')->__('Popular suggestions: ') . '</li>';
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
            
            $model = Mage::getModel('catalog/product');
            $searchBox = Mage::helper('dropfin_autosearch')->getSearchBoxSettings();
            $searchBoxSettings = array();
            if ($searchBox != '') {
                $searchBoxSettings = explode(",", $searchBox);
            }

            $resultText .= '<li class="list-title">' . Mage::helper('core')->__('Products: ') . '</li>';
            $i = 0;
            foreach ($catalogSearchModel as $product) {
                
                $i++;
                if ($limit < $i) {
                    break;
                }
                
                $_product = $model->load($product->getId());
                $productName = $_product->getName();

                $resultText .= '<li class="item">';
                $resultText .= '<a href="' . $product->getProductUrl() . '">';

                if (in_array(2, $searchBoxSettings)) {
                    $resultText .= '<span class="item-thumbnail">';
                    $resultText .= '<img src="' . Mage::helper('catalog/image')->init($_product, 'image')->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(70, 70) . '" alt="' . $productName . '" />';
                    $resultText .= '</span>';
                }

                $resultText .= '<span class="productinfo">';
                if (in_array(1, $searchBoxSettings)) {
                    $resultText .= '<span class="title">' . $productName . '</span>';
                }

                if (in_array(3, $searchBoxSettings)) {
                    $short_description = $_product->getShortDescription();
                    if(strlen($short_description) > 65){
                        $resultText .= '<span class="description">' . substr($short_description, 0, 65) . '...</span>';
                    } else{
                        $resultText .= '<span class="description">' . $short_description . '</span>';
                    }
                    
                }

                if (in_array(4, $searchBoxSettings)) {
                    $resultText .= '<span class="item-price">' . Mage::helper('core')->currency($product->getFinalPrice()) . '</span>';
                }    
                $resultText .= '</span>';

                $resultText .= '</a>';
                $resultText .= '</li>';                
            }            

            if ($limit < count($catalogSearchModel)) {
                $resultText .= '<li class="all-product"><a href="javascript:value(0)" onclick="document.getElementById(\'search_mini_form\').submit()">' . Mage::helper('core')->__('View all products') . '</a></li>';
            }
            $resultText .= '</ul>';
            $resultText .= '</div>';
        } else {
            $resultText .= '<h4>' . Mage::helper('core')->__('No product match') . '</h4>';
        }
        $resultText .= '</div>';
        echo $resultText;
        die;
    }

}