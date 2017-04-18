<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 1/25/17
 * Time: 1:11 PM
 */

class Stableflow_Autosuggest_AjaxController extends Mage_Core_Controller_Front_Action{


    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();
    }

    public function _searchAction(){
        $this->loadLayout();

        $this->getLayout()->getBlock('root')->search();
        $this->renderLayout();
    }

    public function searchAction(){
        $query = Mage::helper('catalogsearch')->getQuery();
        /* @var $query Mage_CatalogSearch_Model_Query */

        $query->setStoreId(Mage::app()->getStore()->getId());

        if ($query->getQueryText() != '') {
            if (Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->setId(0)
                    ->setIsActive(1)
                    ->setIsProcessed(1);
            } else {
                if ($query->getId()) {
                    $query->setPopularity($query->getPopularity() + 1);
                } else {
                    $query->setPopularity(1);
                }

                if ($query->getRedirect()) {
                    $query->save();
                    $this->getResponse()->setRedirect($query->getRedirect());
                    return;
                } else {
                    $query->prepare();
                }
            }

            Mage::helper('catalogsearch')->checkNotes();

            $this->loadLayout();
            $this->getLayout()->getBlock('root')->search();
            $this->renderLayout();

            if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->save();
            }
        }
    }
}