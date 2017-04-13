<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 4/13/17
 * Time: 2:51 PM
 */

class Stableflow_Company_Model_Company_Attribute_Backend_Urlkey extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract{

    /**
     * Before save
     *
     * @access public
     * @param Varien_Object $object
     * @return Stabloflow_Company_Model_Company_Attribute_Backend_Urlkey
     */
    public function beforeSave($object){
        $attributeName = $this->getAttribute()->getName();
        $urlKey = $object->getData($attributeName);
        if($urlKey == ''){
            $urlKey = $object->getName();
        }
        $urlKey = $this->formatUrlKey($urlKey);
        $validKey = false;
        while(!$validKey){
            $entityId = Mage::getResourceModel('company/company')
                ->checkUrlKey($urlKey, $object->getStoreId(), false);
            if($entityId == $object->getId() || empty($entityId)){
                $validKey = true;
            }else{
                $parts = explode('-', $urlKey);
                $last = $parts[count($parts) - 1];
                if(!is_numeric($last)){
                    $urlKey = $urlKey.'-1';
                }else{
                    $suffix = '-'.($last + 1);
                    unset($parts[count($parts) - 1]);
                    $urlKey = implode('-', $parts).$suffix;
                }
            }
        }
        $object->setData($attributeName, $urlKey);
        return $this;
    }

    /**
     * format url key
     *
     * @access public
     * @param string $str
     * @return string
     */
    public function formatUrlKey($str){
        $urlKey = preg_replace('#[^0-9a-z]+#i', '-', Mage::helper('catalog/product_url')->format($str));
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');
        return $urlKey;
    }
}