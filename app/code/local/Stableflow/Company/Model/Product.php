<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 2/28/17
 * Time: 6:15 PM
 */
class Stableflow_Company_Model_Product extends Mage_Core_Model_Abstract
{

    const CACHE_TAG = 'company_product';

    /**
     * Model event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'company_product';

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'company_product';

    /**
     * Owners collection
     * @var Stableflow_Company_Model_Resource_Product_Collection
     */
    protected $_productsCollection = null;

    /**
     * Model cache tag for clear cache in after save and after delete
     *
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    protected function _construct()
    {
        $this->_init('company/product');
    }

    public function getAvailable(){
        return $this->getData('qty');
    }

    public function getPrice(){
        return $this->getData('price');
    }

    public function getFormatPrice(){
        return Mage::helper('core')->currency($this->getPrice(), true, false);
    }

    /**
     * Retrieve product collection that sell this company
     * @param int $companyId
     * @return Stableflow_Company_Model_Resource_Product_Collection
     */
    public function getProductsByCompanyId($companyId)
    {
        if(!$this->_productsCollection){
            $this->_productsCollection = $this->getCollection()
            ->addAttributeToFilter('company_id', $companyId)
            ->addAttributeToFilter('is_active', 1);
        }
        return $this->_productsCollection;
    }

    /**
     * Retrieve product collection by catalog_product_id
     * @param int $catalogProductId
     * @return Stableflow_Company_Model_Resource_Product_Collection
     */
    public function getProductsByCatalogProductId($catalogProductId){
        /*return $this->_getResource()
            ->addCatalogProductFilter($catalogProductId);*/
        return $this->getCollection()
            ->addAttributeToFilter('catalog_product_id', $catalogProductId)
            ->addAttributeToFilter('is_active', 1);
    }

    public function getProductByCatalogProductId($catalogProductId, $companyId){
        $productId = $this->_getResource()->_getProductId($catalogProductId, $companyId);
        return $this->load($productId);
    }

    /**
     * Retrieve Catalog product collection
     * @param array $productsIds
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getCatalogProductCollection($productsIds){
        return Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('entity_id', array('in',$productsIds))
            ->addAttributeToFilter('status', 1);
    }

    /**
     * Get Catalog Product By ID
     * @param int $catalogProductId Catalog Product ID
     * @return Mage_Catalog_Model_Product
     */
    public function getCatalogProduct($catalogProductId = null){
        if(!$catalogProductId) {
            $catalogProductId = $this->getData('catalog_product_id');
        }
        return Mage::getModel('catalog/product')->load($catalogProductId);
    }

    public function getCatalogProductIds($companyId){
        return $this->_getResource()->_getCatalogProductIds($companyId);
    }

    /**
     * Get Companies That sell this product by CatalogProductId
     *  @param int $catalogProductId Catalog Product ID
     * @return Stableflow_Company_Model_Resource_Company_Collection
     */
    public function getProductSellsCompanies($catalogProductId = null){
        if(!$catalogProductId) {
            $catalogProductId = $this->getData('catalog_product_id');
        }
        $companyIds = $this->_getResource()
            ->addCatalogProductFilter($catalogProductId);
        return $this->getCollection()
            ->addAttributeToFilter('company_id', array('in' => $companyIds));
    }
}