<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 2/3/17
 * Time: 11:31 AM
 */
class Stableflow_UserManual_Block_Adminhtml_Catalog_Product_Manual extends Mage_Adminhtml_Block_Widget
{
    /**
     * Preparing layout, adding buttons
     *
     * @return Mage_Eav_Block_Adminhtml_Attribute_Edit_Options_Abstract
     */
    protected function _prepareLayout()
    {
        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('eav')->__('Delete'),
                    'class' => 'delete delete-option'
                )));

        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('eav')->__('Add Option'),
                    'class' => 'add',
                    'id'    => 'add_new_option_button'
                )));
        return parent::_prepareLayout();
    }


    /**
     * Retrieve HTML of delete button
     *
     * @return string
     */
    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    /**
     * Retrieve HTML of add button
     *
     * @return string
     */
    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    /**
     * Retrieve stores collection with default store
     *
     * @return Mage_Core_Model_Mysql4_Store_Collection
     */
    public function getStores()
    {
        $stores = $this->getData('stores');
        if (is_null($stores)) {
            $stores = Mage::getModel('core/store')
                ->getResourceCollection()
                ->setLoadDefault(true)
                ->load();
            $this->setData('stores', $stores);
        }
        return $stores;
    }


    /**
     * Retrieve attribute option values
     *
     * @return array
     */
    public function getOptionValues()
    {
        $defaultValues = array();
        $productId = $this->getRequest()->getParam('id');
        $inputType = '';//'checkbox' 'radio'

        $values = $this->getData('option_values');
        if (is_null($values)) {
            $values = array();
            //$optionCollection = Mage::getResourceModel('catalog/product_collection')
            //->setAttributeFilter($this->getAttributeObject()->getId())
            //->setPositionOrder('desc', true)
            //->load();
            $optionCollection = Mage::getModel('user_manual/manual')
                ->getCollection()
                ->addFieldToFilter('entity_id', $productId)
                ->load();

            $helper = Mage::helper('core');
            foreach ($optionCollection as $option) {
                $value = array();
                if (in_array($option->getId(), $defaultValues)) {
                    $value['checked'] = 'checked="checked"';
                } else {
                    $value['checked'] = '';
                }

                $value['intype'] = $inputType;
                $value['id'] = $option->getId();
                $value['sort_order'] = $option->getSortOrder();
                foreach ($this->getStores() as $store) {
                    $storeValues = $this->getStoreOptionValues($store->getId());
                    $value['store' . $store->getId()] = isset($storeValues[$option->getId()])
                        ? $helper->escapeHtml($storeValues[$option->getId()]) : '';
                }
                $values[] = new Varien_Object($value);
            }
            $this->setData('option_values', $values);
        }

        return $values;
    }

    /**
     * Retrieve frontend labels of attribute for each store
     *
     * @return array
     */
    public function getLabelValues()
    {
        $values = array();
        $values[0] = $this->getAttributeObject()->getFrontend()->getLabel();
        // it can be array and cause bug
        $frontendLabel = $this->getAttributeObject()->getFrontend()->getLabel();
        if (is_array($frontendLabel)) {
            $frontendLabel = array_shift($frontendLabel);
        }
        $storeLabels = $this->getAttributeObject()->getStoreLabels();
        foreach ($this->getStores() as $store) {
            if ($store->getId() != 0) {
                $values[$store->getId()] = isset($storeLabels[$store->getId()]) ? $storeLabels[$store->getId()] : '';
            }
        }
        return $values;
    }

    /**
     * Retrieve attribute option values for given store id
     *
     * @param integer $storeId
     * @return array
     */
    public function getStoreOptionValues($storeId)
    {
        $values = $this->getData('store_option_values_'.$storeId);
        if (is_null($values)) {
            $values = array();
            $valuesCollection = Mage::getResourceModel('user_manual/manual_collection')
                //->setAttributeFilter($this->getAttributeObject()->getId())
                //->setStoreFilter($storeId, false)
                ->load();
            foreach ($valuesCollection as $item) {
                $values[$item->getId()] = $item->getValue();
            }
            $this->setData('store_option_values_'.$storeId, $values);
        }
        return $values;
    }

    /**
     * Retrieve attribute object from registry
     *
     * @return Mage_Eav_Model_Entity_Attribute_Abstract
     */
    public function getAttributeObject()
    {
        $id = '146';//$this->getRequest()->getParam('attribute_id');
        $model = Mage::getModel('catalog/resource_eav_attribute')
            ->setEntityTypeId('4');
        $model->load($id);
        return $model;
        return Mage::registry('entity_attribute');
    }


    public function getManual(){
        $productId = $this->getRequest()->getParam('id');
        $manualArray = null;
        $manualPath = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product_manual/';
        $storeId = $this->getStore();
        if($productId) {
            $manuals = Mage::getModel('user_manual/manual')
                ->getCollection()
                ->addFieldToFilter('entity_id', $productId);
            //return $manuals;
            $i = 0;
            foreach($manuals as $manual){
                $manualArray[$i] = [
                    'label' => $manual->getLabel(),
                    'file' => $manualPath . $manual->getValue(),
                ];
                $i++;
            }
            return $manualArray;
        }else {
            return $manualArray;
        }
    }

    public function getStore(){
        return (int)Mage::app()->getStore()->getId();
    }
}