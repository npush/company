<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/22/17
 * Time: 3:19 PM
 */
class Stableflow_UserManual_Model_Manual_Attribute_Source_Manual  extends Mage_Eav_Model_Entity_Attribute_Source_Abstract{

    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            $this->_options[] = array(
                'label' => Mage::helper('user_manual')->__('-- Please Select --'),
                'value'
            );
        }
    }
}