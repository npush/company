<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/27/17
 * Time: 12:49 PM
 */
class Stableflow_Company_Model_Company_Attribute_Frontend_Company extends Mage_Eav_Model_Entity_Attribute_Frontend_Abstract
{
    /**
     * Retreive attribute value
     *
     * @param $object
     * @return mixed
     */
    public function getValue(Varien_Object $object)
    {

    }

    /**
     * Retreive option by option id
     *
     * @param int $optionId
     * @return mixed|boolean
     */
    public function getOption($optionId)
    {
        return "option text";
    }

}