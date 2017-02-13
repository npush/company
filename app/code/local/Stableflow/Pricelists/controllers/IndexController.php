<?php

class Stableflow_Pricelists_IndexController extends Mage_Core_Controller_Front_Action
{
     public function indexAction()
     {
        $this->loadLayout()->renderLayout();

     }

     public function viewAction()
     {
         $pricelist_id = (int)$this->getRequest()->getParam('id');
         if (!$pricelist_id) {
             $this->_forward('noRoute');
         }
         $this->loadLayout();
         $this->getLayout()->getBlock('pricelist.item')->setPricelistId($pricelist_id);
        try {
            $this->renderLayout();
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_forward('noRoute');
        }
     }
}
