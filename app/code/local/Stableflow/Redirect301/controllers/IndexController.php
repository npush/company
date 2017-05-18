<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 5/17/17
 * Time: 12:30 PM
 */
class Stableflow_Redirect301_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction(){
        $request = Mage::app()->getRequest()->getRequestUri();;
        echo Mage::app()->getRequest()->getModuleName();
        echo Mage::app()->getRequest()->getControllerName();
        echo Mage::app()->getRequest()->getActionName();

        echo $request;
        die();
    }

    /**
     * Redirect from old url
     */
    public function redirectAction(){
        if($old_id = Mage::app()->getRequest()->getParam('old_product_id')){
            /** @var  $resource Mage_Core_Model_Resource*/
            $resource = Mage::getSingleton('core/resource');
            /** @var $readConnection Varien_Db_Adapter_Pdo_Mysql */
            $readConnection = $resource->getConnection('core_read');
            $refTable = $resource->getTableName('redirect301/ref_table');
            $query = 'SELECT entity_id FROM ' . $refTable . ' WHERE product_id = ' . $old_id . ';';
            $data = $readConnection->fetchOne($query);

            /*$url = Mage::getUrl('catalog/product/view',array('id' => $data ));
            $this->getResponse()->setRedirect($url, 301)->sendResponse();
            return $this;*/

            $rewrite = Mage::getModel('core/url_rewrite')
                ->getResource()
                ->getRequestPathByIdPath("product/$data", Mage::app()->getStore()->getId());
            $this->getResponse()->setRedirect(Mage::getBaseUrl() . $rewrite, 301)->sendResponse();
            return;
        }
        if($old_id = Mage::app()->getRequest()->getParam('old_catalog_id')){
            $rewrite = Mage::getModel('core/url_rewrite')
                ->getResource()
                ->getRequestPathByIdPath("category/$old_id", Mage::app()->getStore()->getId());
            $this->getResponse()->setRedirect(Mage::getBaseUrl() . $rewrite, 301)->sendResponse();
            return;
        }
        if($old_id = Mage::app()->getRequest()->getParam('old_product_type_id')){
            $rewrite = Mage::getModel('core/url_rewrite')
                ->getResource()
                ->getRequestPathByIdPath("category/1$old_id", Mage::app()->getStore()->getId());
            $this->getResponse()->setRedirect(Mage::getBaseUrl() . $rewrite, 301)->sendResponse();
            return;
        }
        $this->_redirect('/');
    }

    /**
     * Convert product SKU to old_product_id
     * Fill ref table based on:
     * old_product_id and product entity_id
     */
    public function convertAction(){
        /** @var  $resource Mage_Core_Model_Resource*/
        $resource = Mage::getSingleton('core/resource');
        /** @var $readConnection Varien_Db_Adapter_Pdo_Mysql */
        $readConnection = $resource->getConnection('core_read');
        /** @var  $writeConnection Varien_Db_Adapter_Pdo_Mysql */
        $writeConnection = $resource->getConnection('core_write');

        $productTable = $resource->getTableName('catalog/product');

        $query = 'SELECT `sku`,  `entity_id` FROM ' . $productTable ;
        $data = $readConnection->fetchPairs($query);

        $refTable = $resource->getTableName('redirect301/ref_table');
        $query = 'INSERT INTO ' . $refTable . ' (product_id, entity_id) VALUES(:old_product_id, :new_product_id)';
        foreach($data as $sku => $entity_id) {
            $old_id = substr($sku, strpos($sku, 't') + 1);
            $bind = array(
                ':old_product_id' => (int)$old_id,
                ':new_product_id' => (int)$entity_id
            );
            $writeConnection->query($query, $bind);
        }
    }
}