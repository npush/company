<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 3/1/17
 * Time: 11:43 AM
 */
class Stableflow_Company_Model_Resource_Relation extends Mage_Core_Model_Resource_Db_Abstract{

    public function _construct()
    {
        $this->_init('company/company_to_products', 'entity_id');
    }

}