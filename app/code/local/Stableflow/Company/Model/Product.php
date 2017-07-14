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

    /**
     * Retrieve Company product collection
     * @param Stableflow_Company_Model_Company $company
     * @return Stableflow_Company_Model_Resource_Product_Collection
     */
    public function getProducts(Stableflow_Company_Model_Company $company)
    {
        if(!$this->_productsCollection){
            $this->_productsCollection = $this->getCollection()
                ->addCompanyFilter($company)
                ->addFieldToFilter('is_active', 1);
        }
        return $this->_productsCollection;
    }

    /**
     * Retrieve Catalog product collection
     * @param array $productsIds
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getCatalogProductCollection($productsIds){
        return Mage::getResourceModel('catalog/product_collection')
            ->addFieldToFilter('entity_id', array('in',$productsIds))
            ->addFieldToFilter('is_active', 1);
    }

    /**
     * Get Catalog Product By ID
     * @param int $id Catalog Product ID
     * @return Mage_Catalog_Model_Product
     */
    public function getCatalogProduct($id){
        return Mage::getModel('catalog/product')->load($id);
    }

    /**
     * Get Companies That sell this product
     * @return Stableflow_Company_Model_Resource_Company_Collection
     */
    public function getProductSellsCompanies(){
        return Mage::getResourceModel('company/product_collection')
            ->addFieldToFilter('');
    }
}