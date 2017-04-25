<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 11:55 AM
 */
class Stableflow_Company_Model_Company extends Mage_Core_Model_Abstract{

    protected function _construct(){
        $this->_init('company/company');
    }


    public function getCompanyList(){
        return $this->getResource()->getCollection();
    }

    public function getCompanyProducts(){
        $companyId = $this->getCompanyId();
    }

    public function getCompanyProductById(){

    }

    public function getCompanyId(){
        return $this->getId();
    }

    public function getCompanyUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('company/general/url_prefix')) {
                $urlKey .= $prefix . '/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('company/general/url_suffix')) {
                $urlKey .= '.' . $suffix;
            }
            return Mage::getUrl('', array('_direct' => $urlKey));
        }
        return Mage::getUrl('company/company/view', array('id' => $this->getId()));
    }

    /**
     * check URL key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     */
    public function checkUrlKey($urlKey, $active = true)
    {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * Load entity by attribute
     *
     * @param Mage_Eav_Model_Entity_Attribute_Interface|integer|string|array $attribute
     * @param null|string|array $value
     * @param string $additionalAttributes
     * @return bool|Mage_Catalog_Model_Abstract
     */
    public function loadByAttribute($attribute, $value, $additionalAttributes = '*')
    {
        $collection = $this->getResourceCollection()
            ->addAttributeToSelect($additionalAttributes)
            ->addAttributeToFilter($attribute, $value)
            ->setPage(1,1);

        foreach ($collection as $object) {
            return $object;
        }
        return false;
    }
}