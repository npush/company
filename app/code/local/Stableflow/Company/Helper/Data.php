<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 6:31 PM
 */
class Stableflow_Company_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getFileBaseUrl()
    {
        return Mage::getBaseUrl('media').'company'.'/'.'file';
    }

    public function formatUrl($url){
        //preg_match('/^(http|https):\\/\\/[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*\\.[a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i' ,$url);
        $match = array();
        preg_match('/^(?P<protocol>(http|https):\/\/)?(?P<w>w{3}.)?(?P<domain>[a-z0-9]+([\\-\\.]{1}[a-z0-9]+)*)/', $url, $match);
        if($match['domain'])
            return $match['domain'];
        return $url;
    }

    /**
     * Retrieve company view page url
     *
     * @param   mixed $company
     * @return  string
     */
    public function getCompanyUrl($company){
        if($company instanceof Stableflow_Company_Model_Company){
            return $company->getProductUrl();
        }
        elseif (is_numeric($company)){
            return Mage::getModel('company/company')->load($company)->getCompanyUrl();
        }
        elseif (is_string($company)){
            $model =  Mage::getModel('company/company')->loadByAttribute('name', $company);
            if($model){
                return $model->getCompanyUrl();
            }
            return '#';
        }
        return false;
    }

    /**
     * Retrieve base image url
     * @param Stableflow_Company_Model_Company $company
     * @return string
     */
    public function getImageUrl($company)
    {
        $url = false;
        if (!$company->getImage()) {
            $url = Mage::getBaseUrl('media') . 'company/placeholder/logo.jpeg';
        }
        elseif ($attribute = $company->getResource()->getAttribute('image')) {
            //$url = $attribute->getFrontend()->getUrl($company);
            $url = Mage::getBaseUrl('media') . 'company' . $company->getImage();
        }
        return $url;
    }

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

}