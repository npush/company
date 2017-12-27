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
        $this->setChild('uploader', $this->getLayout()->createBlock('user_manual/adminhtml_media_uploader'));
        return parent::_prepareLayout();
    }


    public function getUploader()
    {
        return $this->getChild('uploader');
    }

    public function getUploaderHtml()
    {
        return $this->getChildHtml('uploader');
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
            $optionCollection = Mage::getResourceModel('user_manual/manual_collection')
                ->addFieldToFilter('entity_id', $productId)
                ;

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

    public function getAddImagesButton()
    {
        return $this->getButtonHtml(
            Mage::helper('catalog')->__('Add New Images'),
            $this->getJsObjectName() . '.showUploader()',
            'add',
            $this->getHtmlId() . '_add_images_button'
        );
    }

    public function getImagesJson()
    {
//        if(is_array($this->getElement()->getValue())) {
//            $value = $this->getElement()->getValue();
//            if(count($value['images'])>0) {
//                foreach ($value['images'] as &$image) {
//                    $image['url'] = Mage::getSingleton('catalog/product_media_config')
//                        ->getMediaUrl($image['file']);
//                }
//                return Mage::helper('core')->jsonEncode($value['images']);
//            }
//        }
        return '[]';
    }

    public function getImagesValuesJson()
    {
        $values = array();
        foreach ($this->getMediaAttributes() as $attribute) {
            /* @var $attribute Mage_Eav_Model_Entity_Attribute */
            $values[$attribute->getAttributeCode()] = $this->getElement()->getDataObject()->getData(
                $attribute->getAttributeCode()
            );
        }
        return Mage::helper('core')->jsonEncode($values);
    }

    /**
     * Enter description here...
     *
     * @return array
     */
    public function getImageTypes()
    {
        $imageTypes = array();
        foreach ($this->getMediaAttributes() as $attribute) {
            /* @var $attribute Mage_Eav_Model_Entity_Attribute */
            $imageTypes[$attribute->getAttributeCode()] = array(
                'label' => $attribute->getFrontend()->getLabel() . ' '
                    . Mage::helper('catalog')->__($this->getElement()->getScopeLabel($attribute)),
                'field' => $this->getElement()->getAttributeFieldName($attribute)
            );
        }
        return $imageTypes;
    }
}