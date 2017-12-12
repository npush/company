<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/29/17
 * Time: 2:27 PM
 */
class Stableflow_Company_Block_Company_Offers extends Mage_Core_Block_Template
{
    /**
     * Get Companies That sell product
     * @param int $catalogProductId
     * @return Stableflow_Company_Model_Resource_Company_Collection
     */
    public function getSellsCompaniesByProductId($catalogProductId){
        $companyIds = Mage::getResourceModel('company/product')->addCatalogProductFilter($catalogProductId);
        return Mage::getResourceModel('company/company_collection')
            ->addFieldToFilter('entity_id',array('in' => $companyIds));
    }

    /**
     * Retrieve offers.
     * @param int $catalogProductId
     * @return Varien_Data_Collection
     */
    public function getOffers($catalogProductId){
        $companyCollection = $this->getSellsCompaniesByProductId($catalogProductId);
        $collection = new Varien_Data_Collection();
        /** @var  $_company Stableflow_Company_Model_Company */
        foreach($companyCollection as $_company){
            $_company = $_company->load();
            $data = new Varien_Object();
            /** @var  $_product Stableflow_Company_Model_Product */
            $_product = $_company->getProductByCatalogProductId($catalogProductId);
            $data->setData(array(
                'id'            => $_company->getId(),
                'name'          => $_company->getName(),
                'url'           => $_company->getCompanyUrl(),
                'topicality'    => $_company->getTopicality(),
                'price'         => $_product->getPrice(),
                'format_price'  => $_product->getFormatPrice(),
                'available'     => $_product->getAvailable(),
            ));
            $collection->addItem($data);
        }
        return $collection;
    }
}