<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/9/16
 * Time: 3:45 PM
 */
class Stableflow_Company_Model_Resource_Attribute extends Mage_Eav_Model_Resource_Entity_Attribute {

    protected $_eventPrefix = 'company_entity_attribute';
    protected $_eventObject = 'attribute';
    const MODULE_NAME = 'Stableflow_Company';
    const ENTITY = 'company_eav_attribute';

    /**
     * after saving the attribute
     *
     * @access protected
     * @param $object Mage_Core_Model_Abstract $object
     * @return  Stableflow_Company_Model_Resource_Attribute
     */
    protected  function _afterSave(Mage_Core_Model_Abstract $object)
    {
        /** @var  $setup Mage_Eav_Model_Entity_Setup*/
        $setup       = Mage::getModel('eav/entity_setup', 'core_write');
        $entityType  = $object->getEntityTypeId();
        $setId       = $setup->getDefaultAttributeSetId($entityType);
        $groupId     = $setup->getDefaultAttributeGroupId($entityType);
        $attributeId = $object->getId();
        $sortOrder   = $object->getPosition();

        $setup->addAttributeToGroup($entityType, $setId, $groupId, $attributeId, $sortOrder);
        return parent::_afterSave($object);
    }
}