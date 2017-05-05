<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 11:55 AM
 */

/**
 * Company model
 *
 * @package    Stableflow_Company
 */
class Stableflow_Company_Model_Company extends Mage_Core_Model_Abstract{

    const CACHE_TAG = 'company';

    /**
     * Model event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'company';

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'company';

    /**
     * List of errors
     *
     * @var array
     */
    protected $_errors = array();

    /**
     * Assoc array of company attributes
     *
     * @var array
     */
    protected $_attributes;

    /**
     * Company addresses array
     *
     * @var array
     */
    protected $_addresses = null;

    /**
     * Customer addresses collection
     *
     * @var Stableflow_Company_Model_Entity_Address_Collection
     */
    protected $_addressesCollection;

    /**
     * Is model deleteable
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
     * Initialize company model
     */
    protected function _construct(){
        $this->_init('company/company');
    }

    public function getId(){
        return $this->getData('entity_id');
    }

    public function getCompanyList(){
        return $this->getResource()->getCollection();
    }

    public function getCompanyProducts(){
        $companyId = $this->getCompanyId();
    }

    public function getCompanyProductById(){

    }

    public function getAddress(){
        $address = Mage::getModel('company/address')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', $this->getData('address_id'));
        return $address;
    }

    public function getCompanyId(){
        return $this->getId();
    }

    /**
     * Add address to address collection
     *
     * @param   Stableflow_Company_Model_Address $address
     * @return  Stableflow_Company_Model_Company
     */
    public function addAddress(Stableflow_Company_Model_Address $address)
    {
        $this->getAddressesCollection()->addItem($address);
        $this->getAddresses();
        $this->_addresses[] = $address;
        return $this;
    }

    /**
     * Retrieve company address by address id
     *
     * @param   int $addressId
     * @return  Stableflow_Company_Model_Address
     */
    public function getAddressById($addressId)
    {
        $address = Mage::getModel('company/address')->load($addressId);
        if ($this->getId() == $address->getParentId()) {
            return $address;
        }
        return Mage::getModel('company/address');
    }

    /**
     * Getting company address object from collection by identifier
     *
     * @param int $addressId
     * @return Stableflow_Company_Model_Address
     */
    public function getAddressItemById($addressId)
    {
        return $this->getAddressesCollection()->getItemById($addressId);
    }

    /**
     * Retrieve not loaded address collection
     *
     * @return Stableflow_Company_Model_Entity_Address_Collection
     */
    public function getAddressCollection()
    {
        return Mage::getResourceModel('company/address_collection');
    }

    /**
     * Company addresses collection
     *
     * @return Mage_Customer_Model_Entity_Address_Collection
     */
    public function getAddressesCollection()
    {
        if ($this->_addressesCollection === null) {
            $this->_addressesCollection = $this->getAddressCollection()
                ->setCompanyFilter($this)
                ->addAttributeToSelect('*');
            foreach ($this->_addressesCollection as $address) {
                $address->setCompany($this);
            }
        }

        return $this->_addressesCollection;
    }

    /**
     * Retrieve company address array
     *
     * @return array
     */
    public function getAddresses()
    {
        $this->_addresses = $this->getAddressesCollection()->getItems();
        return $this->_addresses;
    }

    /**
     * Retrieve default address by type(attribute)
     *
     * @param   string $attributeCode address type attribute code
     * @return  Stableflow_Company_Model_Address
     */
    public function getPrimaryAddress($attributeCode)
    {
        $primaryAddress = $this->getAddressesCollection()->getItemById($this->getData($attributeCode));

        return $primaryAddress ? $primaryAddress : false;
    }

    /**
     * Retrieve all customer default addresses
     *
     * @return array
     */
    public function getPrimaryAddresses()
    {
        $addresses = array();
        $primaryBilling = $this->getPrimaryBillingAddress();
        if ($primaryBilling) {
            $addresses[] = $primaryBilling;
            $primaryBilling->setIsPrimaryBilling(true);
        }

        $primaryShipping = $this->getPrimaryShippingAddress();
        if ($primaryShipping) {
            if ($primaryBilling->getId() == $primaryShipping->getId()) {
                $primaryBilling->setIsPrimaryShipping(true);
            } else {
                $primaryShipping->setIsPrimaryShipping(true);
                $addresses[] = $primaryShipping;
            }
        }
        return $addresses;
    }

    /**
     * Retrieve not default addresses
     *
     * @return array
     */
    public function getAdditionalAddresses()
    {
        $addresses = array();
        $primatyIds = $this->getPrimaryAddressIds();
        foreach ($this->getAddressesCollection() as $address) {
            if (!in_array($address->getId(), $primatyIds)) {
                $addresses[] = $address;
            }
        }
        return $addresses;
    }

    /**
     * Retrieve all company attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        if ($this->_attributes === null) {
            $this->_attributes = $this->_getResource()
                ->loadAllAttributes($this)
                ->getSortedAttributes();
        }
        return $this->_attributes;
    }

    /**
     * Get company attribute model object
     *
     * @param   string $attributeCode
     * @return  Stableflow_Company_Model_Entity_Attribute | null
     */
    public function getAttribute($attributeCode)
    {
        $this->getAttributes();
        if (isset($this->_attributes[$attributeCode])) {
            return $this->_attributes[$attributeCode];
        }
        return null;
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