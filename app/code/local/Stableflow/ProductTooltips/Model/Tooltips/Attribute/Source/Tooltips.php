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
            foreach($tooltips as $tooltip) {
                $this->_options[] =
                    array(
                        'label' => $tooltip->getData('title'),
                        'value' => $tooltip->getData('tooltip_id'),
                        'image' => $tooltip->getData('value'),
                    );
            }
        }
        return $this->_options;
    }

    public function getTooltips($tooltip_id){
        if(is_null($this->_tooltips)) {
            $this->_tooltips = Mage::getModel('product_tooltips/tooltip')
                ->getCollection()
                //->addFieldToFilter('tooltip_id', array('eq' => $tooltip_id))
                //->addFieldToSelect(array('tooltip_id','title'))
               // ->setOrder('title','ASC');
            ;
        }
        return $this->_tooltips;
    }

    public function getTooltipsValues($tooltip_id)
    {
        $this->_tooltips = Mage::getModel('product_tooltips/tooltip')
            ->getCollection()
            ->addFieldToFilter('tooltip_id', array('in' => $tooltip_id))
            //->addFieldToSelect(array('tooltip_id','title'))
            // ->setOrder('title','ASC');
        ;
        return $this->_tooltips;
    }
}