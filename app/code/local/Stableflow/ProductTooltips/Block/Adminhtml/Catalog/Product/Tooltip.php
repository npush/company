<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/20/17
 * Time: 12:34 PM
 */

class Stableflow_ProductTooltips_Block_Adminhtml_Catalog_Product_Tooltip extends Mage_Adminhtml_Block_Widget
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
            $product = Mage::getModel('catalog/product')->load($productId);
            $tooltip_id = explode(',', $product->getData('tooltips'));
            //$optionsArr = array_reverse($product->getTooltips(), true);
//            $optionCollection = $product->getResource()->getAttribute('tooltips')->getSource()->getTooltipsValues($tips_id);//->getFrontend()->getValue($product);
            $optionCollection = Mage::getModel('product_tooltips/tooltip')
                ->getCollection()
                //->addFieldToFilter('main_table.tooltip_id' , array('in' => $tooltip_id) );
                //->load();
            ;

            $helper = Mage::helper('core');
            foreach ($optionCollection as $option) {
                $value = array();
                if (in_array($option->getId(), $tooltip_id)) {
                    $value['checked'] = 'checked="checked"';
                } else {
                    $value['checked'] = '';
                }

                $value['intype'] = $inputType;
                $value['id'] = $option->getId();
                $value['sort_order'] = $option->getSortOrder();
//                foreach ($this->getStores() as $store) {
//                    $storeValues = $this->getStoreOptionValues($store->getId());
//                    $value['store' . $store->getId()] = isset($storeValues[$option->getId()])
//                        ? $helper->escapeHtml($storeValues[$option->getId()]) : '';
//                }
                $value['store' . 0] = array(
                    'description'   => $option->getDescription(),
                    'title'         => $option->getTitle()
                );

                $value['store' . 0] = $option->getTitle();

                $value['image'] = Mage::app()->getStore($product->getStore())->getBaseUrl('media').'tooltips'.$option->getValue();
                if($value['checked'] == 'checked="checked"') {
                    $values_top[] = new Varien_Object($value);
                }else {
                    $values[] = new Varien_Object($value);
                }
            }
            if(isset($values_top)) $values = array_merge($values, $values_top);
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
            $valuesCollection = Mage::getResourceModel('product_tooltips/tooltip_collection')
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
        $id = '174';//$this->getRequest()->getParam('attribute_id');
        $model = Mage::getModel('catalog/resource_eav_attribute')
            ->setEntityTypeId('4');
        $model->load($id);
        return $model;
        return Mage::registry('entity_attribute');
    }

    public function getReadOnly()
    {
        return true;
    }
}