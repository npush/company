<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/20/17
 * Time: 3:30 PM
 */
class Stableflow_ProductTooltips_Model_Tooltips_Attribute_Source_Tooltips extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{

    protected $_options = null;
    protected $_tooltips = null;

    public function getAllOptions(){
        $tooltips = $this->getTooltips();
        if (is_null($this->_options)) {
            $this->_options[] = array(
                'label' => Mage::helper('product_tooltips')->__('-- Please Select --'),
                'value'
            );
            foreach($tooltips as $tooltip) {
                $this->_options[] =
                    array(
                        'label' => $tooltip->getData('title'),
                        'value' => $tooltip->getData('tooltip_id'),
                    );
            }
        }
        return $this->_options;
    }

    public function getTooltips(){
        if(is_null($this->_tooltips)) {
            $this->_tooltips = Mage::getModel('product_tooltips/tooltip')
                ->getCollection()
                ->addFieldToSelect(array('tooltip_id','title'))
                ->setOrder('title','ASC');
            ;
        }
        return $this->_tooltips;
    }
}