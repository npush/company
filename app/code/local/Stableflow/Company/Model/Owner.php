<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 5/10/17
 * Time: 2:04 PM
 */

/**
 * Company owner model
 *
 * @package    Stableflow_Company
 */
class Stableflow_Company_Model_Owner extends Mage_Core_Model_Abstract{

    const CACHE_TAG = 'company_owner';

    /**
     * Model event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'company_owner';

    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'company_owner';

    /**
     * Owners collection
     * @var Stableflow_Company_Model_Resource_Owner_Collection
     */
    protected $_ownersCollection = null;

    /**
     * Model cache tag for clear cache in after save and after delete
     *
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Customer model
     * @var Mage_Customer_Model_Customer
     */
    protected $_customer = null;

    /**
     * Initialize company model
     */
    protected function _construct(){
        $this->_init('company/owner');
    }

    public function getOwnersCollection(){
        if(!$this->_ownersCollection) {
            $this->_ownersCollection = $this->getCollection();
        }
        return $this->_ownersCollection;
    }

    /**
     * @param $id
     * @return $this
     */
    public function getOwnerById($id){
        /** @var  $_owner Stableflow_Company_Model_Resource_Owner_Collection */
        $this->getCollection()
            ->addFieldToFilter('customer_id', $id)
            ->addFieldToFilter('is_active', 1)
            ->load();
        $this->_customer = Mage::getModel('customer/customer')->load($id);
        return $this;
    }

    /**
     * Assign owner to company
     * @param Mage_Customer_Model_Customer $customer
     * @param Stableflow_Company_Model_Company $company
     * @return $this
     */
    public function addOwner(Mage_Customer_Model_Customer $customer, Stableflow_Company_Model_Company $company){
        if($canManage = $customer->getData('company_owner')) {
            $customerId = $customer->getId();
            $companyId = $company->getId();
            $this->addData(array(
                'company_id'    => $companyId,
                'customer_id'   => $customerId,
                'created_at'    => Varien_Date::now(),
                'updated_at'    => Varien_Date::now(),
            ));
            $this->_customer = $customer;
            $this->getResource()->save($this);
            return $this;
        }
    }
}