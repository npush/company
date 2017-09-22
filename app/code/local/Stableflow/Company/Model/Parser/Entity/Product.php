<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 1:00 PM
 */
class Stableflow_Company_Model_Parser_Entity_Product extends Stableflow_Company_Model_Parser_Entity_Abstract
{

    protected $_eventPrefix      = 'company_parser_entity_product';
    protected $_eventObject      = 'product';

    const MANUFACTURER_ATTRIBUTE = 'manufacturer';

    const MANUFACTURER_CODE_ATTRIBUTE = 'manufacturer_number';
    const MANUFACTURER_CODE_DELIMITER = '|';

    const BEHAVIOR_ADD_NEW  = 1;
    const BEHAVIOR_UPDATE   = 2;
    const BEHAVIOR_DISABLE  = 3;
    const BEHAVIOR_DELETE   = 4;

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
        }elseif($result = $this->findByCode($row['code'], $data->getCompanyId(), $data->getManufacturerId())){
            // found product
            if($result['company_product_id']){
                //update company product
                $this->_productRoutine($data, self::BEHAVIOR_UPDATE, $result['company_product_id']);
            }else{
                // add new company product
                $result['company_product_id'] = $this->_productRoutine($data, self::BEHAVIOR_ADD_NEW);
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
     * @param $productId int
     * @return int
     */
    protected function _productRoutine($row, $behavior, $productId = null)
    {
        $product = Mage::getModel('company/product');
        switch($behavior){
            case self::BEHAVIOR_ADD_NEW :
                $_data = array(
                    'price' => $row['price'],
                    //'price_int' => $data[self::COMPANY_PRODUCT_INT_PRICE],
                    //'price_wholesale' => $data[self::COMPANY_PRODUCT_OPT_PRICE],
                    'created_at' => Varien_Date::now(),
                );
                $product->addData($_data);
                $productId = 654321;
                //$productId = $product->save();
                break;
            case self::BEHAVIOR_UPDATE :
                $product->load($productId);
                $_data = array(
                    'price' => $row['price'],
                    //'price_int' => $_data[self::COMPANY_PRODUCT_INT_PRICE],
                    //'price_wholesale' => $_data[self::COMPANY_PRODUCT_OPT_PRICE],
                    'updated_at' => Varien_Date::now(),
                );
                $product->addData($_data);
                $product->save();
                break;
            case self::BEHAVIOR_DISABLE :
                $product->load($productId);
                $product->setStatust(Stableflow_Company_Model_Product::DISABLE);
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
     * @param $manufacturerId int
     * @return array
     */
    public function findByCode($code, $companyId, $manufacturerId)
    {
        $companyProductId = null;
        $catalogProductId = null;
        $attribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_CODE_ATTRIBUTE);
        $productCollection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToFilter($attribute, array('like' => '%'.$code.'%'))
            ->addAttributeToSelect(array('entity_id',self::MANUFACTURER_CODE_ATTRIBUTE , self::MANUFACTURER_ATTRIBUTE))
            ->initCache(Mage::app()->getCache(),'parser_catalog_collection',array('SOME_TAGS'));
        foreach($productCollection as $_product) {
            $catalogProductId = $_product->getId();
            $manufacturerCode = $_product->getData(self::MANUFACTURER_CODE_ATTRIBUTE);
            $_codes = explode(self::MANUFACTURER_CODE_DELIMITER, $manufacturerCode);
            if(in_array($code, $_codes)) {
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
        $attribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode(Mage_Catalog_Model_Product::ENTITY, self::MANUFACTURER_ATTRIBUTE);
        return $attribute->getSource()->getOptionArray();
    }

    protected function _getEventData()
    {
        return array(
            'data_object'       => $this,
            $this->_eventObject => $this,
        );
    }
}