<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 1:31 PM
 */
class Stableflow_Company_Model_Address extends Stableflow_Company_Model_Address_Abstract
{

    const CACHE_TAG = 'company_address';

    /**
     * Model event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'company_address';

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'company_address';

    /**
     * Is model deletable
     *
     * @var boolean
     */
    protected $_isDeleteable = true;

    /**
     * Is model readonly
     *
     * @var boolean
     */
    protected $_isReadonly = false;

    /**
     * Model cache tag for clear cache in after save and after delete
     *
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Name of object id field
     *
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    protected $_company;

    protected function _construct()
    {
        $this->_init('company/address');
    }

    /**
     * Retrieve address company identifier
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->_getData('company_id') ? $this->_getData('company_id') : $this->getParentId();
    }

    /**
     * Declare address company identifier
     *
     * @param integer $id
     * @return Stableflow_Company_Model_Address
     */
    public function setCompanyId($id)
    {
        $this->setParentId($id);
        $this->setData('company_id', $id);
        return $this;
    }

    /**
     * Retrieve address company
     *
     * @return Stableflow_Company_Model_Company | false
     */
    public function getCompany()
    {
        if (!$this->getCompanyId()) {
            return false;
        }
        if (empty($this->_company)) {
            $this->_company = Mage::getModel('company/company')
                ->load($this->getCompanyId());
        }
        return $this->_company;
    }

    /**
     * Specify address compnay
     *
     * @param Stableflow_Company_Model_Company $company
     */
    public function setCompany(Stableflow_Company_Model_Company $company)
    {
        $this->_company = $company;
        $this->setCompanyId($company->getId());
        return $this;
    }

    /**
     * Delete customer address
     *
     * @return Stableflow_Company_Model_Address
     */
    public function delete(){
        parent::delete();
        $this->setData(array());
        return $this;
    }


    public function __clone(){
        $this->setId(null);
    }

    public function getId(){
        return $this->getData('entity_id');
    }

    /**
     * Retrieve address entity attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = $this->getData('attributes');
        if (is_null($attributes)) {
            $attributes = $this->_getResource()
                ->loadAllAttributes($this)
                ->getSortedAttributes();
            $this->setData('attributes', $attributes);
        }
        return $attributes;
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
     * Return Region ID
     *
     * @return int
     */
    public function getRegionId()
    {
        return (int)$this->getData('region_id');
    }

    /**
     * Set Region ID. $regionId is automatically converted to integer
     *
     * @param int $regionId
     * @return Stableflow_Company_Model_Address
     */
    public function setRegionId($regionId)
    {
        $this->setData('region_id', (int)$regionId);
        return $this;
    }
}