<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/17/17
 * Time: 6:16 PM
 */

class Stableflow_Company_Model_Company_Attribute_Source_Company extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{

    protected $_companies = null;
    protected $_options  = null;
    protected $_optionsArray = null;

    public function getAllOptions(){
        $companies = $this->getCompanies();
        if (is_null($this->_options)) {
            $this->_options[] = array(
                'label' => Mage::helper('company')->__('-- Please Select --'),
                'value'
            );
            foreach($companies as $company) {
                $this->_options[] =
                    array(
                        'label' => $company->getName(),
                        'value' => $company->getId(),
                    );
            }
        }
        return $this->_options;
    }

    public function toOptionArray(){
        return $this->getAllOptions();
    }

    public function getOptionArray()
    {
        $companies = $this->getCompanies();
        if (is_null($this->_optionsArray)) {
            foreach ($companies as $company) {
                $this->_optionsArray[$company->getId()] = $company->getName();
            }
        }
        return $this->_optionsArray;
    }

    public function getCompanies(){
        if(is_null($this->_companies)) {
            $this->_companies = Mage::getModel('company/company')
                ->getCollection()
                ->addAttributeToSelect(array('id','name'))
                ->setOrder('name','ASC');
            ;
        }
        return $this->_companies;
    }

}