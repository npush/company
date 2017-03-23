<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/23/17
 * Time: 3:39 PM
 */
class Stableflow_AdditionalCodes_Model_Observer extends Mage_Core_Model_Observer{


    /**
     * Add new attribute type to manage attributes interface
     *
     * @param   Varien_Event_Observer $observer
     * @return  Mage_Weee_Model_Observer
     */
    public function addAdditionalCodeAttributeType(Varien_Event_Observer $observer)
    {
        // adminhtml_product_attribute_types

        $response = $observer->getEvent()->getResponse();
        $types = $response->getTypes();
        $types[] = array(
            'value' => 'add_codes',
            'label' => Mage::helper('additional_codes')->__('Additional Codes'),
            'hide_fields' => array(
                'is_unique',
                'is_required',
                'frontend_class',
                'is_configurable',

                '_scope',
                '_default_value',
                '_front_fieldset',
            ),
            'disabled_types' => array(
                Mage_Catalog_Model_Product_Type::TYPE_GROUPED,
            )
        );

        $response->setTypes($types);

        return $this;
    }

    /**
     * Assign backend model to file upload input type
     *
     * @param  Varien_Event_Observer $observer
     * @return Stableflow_AdditionalCodes_Model_Observer
     */
    public function assignBackendModelToAttribute(Varien_Event_Observer $observer)
    {
        $backendModel = Stableflow_AdditionalCodes_Model_Product_Attribute_Backend_Codes::getBackendModelName();
        //$backendModel = 'jvs_fileattribute/attribute_backend_file';

        /** @var $object Mage_Eav_Model_Entity_Attribute_Abstract */
        $object = $observer->getEvent()->getAttribute();

        if ($object->getFrontendInput() == 'add_codes') {
            $object->setBackendModel($backendModel);
            $object->setBackendType('varchar');
        }

        return $this;
    }

    /**
     * Assign frontend model to file upload input type
     *
     * @param  Varien_Event_Observer $observer
     * @return Jvs_FileAttribute_Model_Observer
     */
    public function updateElementTypes(Varien_Event_Observer $observer)
    {
        $response = $observer->getEvent()->getResponse();

        $types = $response->getTypes();
        $types['add_codes'] = Mage::getConfig()->getBlockClassName('jvs_fileattribute/element_file');

        $response->setTypes($types);

        return $this;
    }

    /**
     * Exclude 'jvs_file' attributes from standard form generation
     *
     * @param   Varien_Event_Observer $observer
     * @return  Jvs_FileAttribute_Model_Observer
     */
    public function updateExcludedFieldList(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getObject();
        $list = $block->getFormExcludedFieldList();

        $attributes = Mage::getModel('eav/entity_attribute')->getAttributeCodesByFrontendType('add_codes');
        $list = array_merge($list, array_values($attributes));

        $block->setFormExcludedFieldList($list);

        return $this;
    }
}