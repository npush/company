<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 5/10/17
 * Time: 2:04 PM
 */

/**
 * Company owner model
 *
 * @package    Stableflow_Company
 */
class Stableflow_Company_Model_Owner extends Mage_Core_Model_Abstract
{
    protected $_owners = null;

    protected $_owner = null;
    /**
     * Initialize company model
     */
    protected function _construct(){
        $this->_init('company/owner');
    }

    public function getOwners(){
        $this->_owners = $this->getResouce()->getCollection();
    }
}