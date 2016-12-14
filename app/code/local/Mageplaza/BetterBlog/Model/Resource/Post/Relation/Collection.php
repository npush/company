<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/13/16
 * Time: 6:59 PM
 */
class Mageplaza_BetterBlog_Model_Resource_Post_Relation_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{

    protected function _construct(){
        parent::_construct();
        $this->_init('mageplaza_betterblog/post_relation');
    }
}