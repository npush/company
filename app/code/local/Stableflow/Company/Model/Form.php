<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 12/23/16
 * Time: 12:24 PM
 */
class Stableflow_Company_Model_Form extends Mage_Eav_Model_Form
{
    /**
     * Current module pathname
     *
     * @var string
     */
    protected $_moduleName = 'company';

    /**
     * Current EAV entity type code
     *
     * @var string
     */
    protected $_entityTypeCode = 'company_address';

    /**
     * Get EAV Entity Form Attribute Collection for Company
     * exclude 'created_at'
     *
     * @return Stableflow_Company_Model_Resource_Form_Attribute_Collection
     */
    protected function _getFormAttributeCollection()
    {
        return parent::_getFormAttributeCollection();
            //->addFieldToFilter('attribute_code', array('neq' => 'created_at'));
    }

    /**
     * Return attribute data model by attribute
     *
     * @param  Stableflow_Company_Model_Entity_Attribute $attribute
     * @return Mage_Eav_Model_Attribute_Data_Abstract
     */
    protected function _getAttributeDataModel($attribute)
    {
        $dataModel =  Stableflow_Company_Model_Attribute_Data::factory($attribute, $this->getEntity());
        $dataModel->setIsAjaxRequest($this->getIsAjaxRequest());

        return $dataModel;
    }

}