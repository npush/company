<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 1:00 PM
 */
class Stableflow_Company_Model_Parser_Entity_Product extends Stableflow_Company_Model_Parser_Entity_Abstract
{
    const MANUFACTURER_ATTRIBUTE = 'manufacturer';
    const MANUFACTURER_CODE_ATTRIBUTE = 'manufacturer_number';
    const MANUFACTURER_CODE_DELIMITER = '|';

    const BEHAVIOR_ADD_NEW  = 1;
    const BEHAVIOR_UPDATE   = 2;
    const BEHAVIOR_DISABLE  = 3;
    const BEHAVIOR_DELETE   = 4;

    protected $_eventPrefix = 'company_parser_entity_product';
    protected $_eventObject = 'product';

    protected $_manufacturers = null;

    /**
     * Update / add new / delete Company Product
     * @param $data Varien_Object
     */
    public function update($data)
    {
        $message = null;
        Mage::dispatchEvent($this->_eventPrefix.'_update_before', array('update_data' => $data, 'message' => $message));
        $row = $data->getRawData();
        if(!$this->_isValidRow($row)){
            // empty code
            $message = Mage::getSingleton('company/parser_log_message')->error($data);
        }elseif($result = $this->findByCode($row['code'], $data->getCompanyId(), $row['manufacturer'])){
            // found product
            if($result['company_product_id']){
                //update company product
                $this->_productRoutine($data, self::BEHAVIOR_UPDATE, $data->getCompanyId(), $result['catalog_product_id'], $result['company_product_id']);
            }else{
                // add new company product
                $newProduct = $this->_productRoutine($data, self::BEHAVIOR_ADD_NEW, $data->getCompanyId(), $result['catalog_product_id']);
                $result['company_product_id'] = $newProduct->getId();
            }
            $data->setCatalogProductId($result['catalog_product_id']);
            $data->setCompanyProductId($result['company_product_id']);
            $message = Mage::getSingleton('company/parser_log_message')->success($data);
            }else{
            // code did not found
            $message = Mage::getSingleton('company/parser_log_message')->error($data);
        }
        Mage::dispatchEvent($this->_eventPrefix.'_update_after', array('update_data' => $data, 'message' => $message));
    }

    /**
     * Check row for valid data
     * @param $row array
     * @return bool
     */
    protected function _isValidRow(&$row)
    {
        if($row['code'] == ''){
            $row['code'] = 'empty code';
            return false;
        }
        return true;
    }

    /**
     * @param $row Varien_Object
     * @param $behavior int
     * @param $companyId int
     * @param $baseProductId int
     * @param $productId int
     * @return int
     */
    protected function _productRoutine($row, $behavior, $companyId, $baseProductId, $productId = null)
    {
        $product = Mage::getModel('company/product');
        switch($behavior){
            case self::BEHAVIOR_ADD_NEW :
                $_data = array(
                    'price' => $row->getRawData()['price'],
                    'price_int' => $row->getRawData()['price_internal'],
                    'price_wholesale' => $row->getRawData()['price_wholesale'],
                    'catalog_product_id' =>  $baseProductId,
                    'company_id'    => $companyId,
                    'store_id'  => 0,
                    'created_at' => Varien_Date::now(),
                    'updated_at' => Varien_Date::now(),
                );
                $product->addData($_data);
                //$productId = 654321;
                $productId = $product->save();
                break;
            case self::BEHAVIOR_UPDATE :
                $product->load($productId);
                $_data = array(
                    'price' => $row->getRawData()['price'],
                    'price_int' => $row->getRawData()['price_internal'],
                    'price_wholesale' => $row->getRawData()['price_wholesale'],
                    'updated_at' => Varien_Date::now(),
                );
                $product->addData($_data);
                $product->save();
                break;
            case self::BEHAVIOR_DISABLE :
                $product->load($productId);
                $product->setStatust(Stableflow_Company_Model_Product_Status::DISABLE);
                $product->save();
                break;
            case self::BEHAVIOR_DELETE :
                $product->load($productId);
                $product->delete();
                break;
        }
        return $productId;
    }

    /**
     * Find product by manufacture code
     * @param $code string
     * @param $companyId int
     * @param $manufacturer string
     * @return array
     */
    public function findByCode($code, $companyId, $manufacturer)
    {
        $companyProductId = null;
        $catalogProductId = null;
        $manufacturerId = $this->getManufacturerIdByName($manufacturer);
        $mfCodeAttribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_CODE_ATTRIBUTE);
        $mfNameAttribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_ATTRIBUTE);
        $productCollection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToFilter($mfCodeAttribute, array('like' => '%'.$code.'%'))
            ->addAttributeToFilter($mfNameAttribute, array('en' => $manufacturerId))
            ->addAttributeToSelect(array('entity_id',self::MANUFACTURER_CODE_ATTRIBUTE , self::MANUFACTURER_ATTRIBUTE))
            ->initCache(Mage::app()->getCache(),'parser_catalog_collection',array('SOME_TAGS'));
        foreach($productCollection as $_product) {
            $catalogProductId = $_product->getId();
            $manufacturerCode = $_product->getData(self::MANUFACTURER_CODE_ATTRIBUTE);
            $_codes = explode(self::MANUFACTURER_CODE_DELIMITER, $manufacturerCode);
            if(in_array($code, $_codes)){
                $companyProductId = Mage::getResourceModel('company/product_collection')
                    ->addAttributeToFilter('catalog_product_id', $catalogProductId)
                    ->addAttributeToFilter('company_id', $companyId)
                    ->addAttributeToSelect(array('entity_id', 'catalog_product_id', 'company_id'))
                    ->initCache(Mage::app()->getCache(),'parser_company_product_collection',array('SOME_TAGS'))
                    ->getFirstItem()
                    ->getId();
                return array(
                    'catalog_product_id' => $catalogProductId,
                    'company_product_id' => $companyProductId
                );
            }
        }
        return null;
    }

    /**
     * @return array [id] => Name
     */
    public function getManufacturers()
    {
        if(is_null($this->_manufacturers)) {
            $attribute = Mage::getModel('eav/entity_attribute')
                ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_ATTRIBUTE);
            $this->_manufacturers = $attribute->getSource()->getOptionArray();
        }
        return $this->_manufacturers;
    }

    /**
     * Get id by Name
     * @param $name string
     * @return int
     */
    public function getManufacturerIdByName($name)
    {
        $manufacturers = $this->getManufacturers();
        foreach($manufacturers as $_id => $_name){
            if(strcasecmp($_name ,$name) == 0){
                return $_id;
            }
        }
        return null;
    }

    protected function _getEventData()
    {
        return array(
            'data_object'       => $this,
            $this->_eventObject => $this,
        );
    }
}