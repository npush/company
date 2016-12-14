<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/14/16
 * Time: 1:22 PM
 */
class Mageplaza_BetterBlog_Block_Post_Relation extends Mage_Core_Block_Template{

    public function _construct(){
        parent::_construct();
        $posts = Mage::getResourceModel('mageplaza_betterblog/post_relation');
            //->addStoreFilter(Mage::app()->getStore());
        $this->setPostm($posts);
    }

    protected function _prepareLayout(){
        parent::_prepareLayout();
        return $this;
    }


    public function getPosts($postIds){
        if(!is_array($postIds) || !count($postIds)){
            return false;
        }
        $posts = Mage::getResourceModel('mageplaza_betterblog/post_collection')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('entity_id',$postIds);
        return $posts;
    }
}