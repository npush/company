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
    }

    protected function _prepareLayout(){
        parent::_prepareLayout();
        return $this;
    }

    public function getPosts(){
        $_categoryId = $this->getProduct()->getCategoryId();
        $postIds = Mage::getResourceModel('mageplaza_betterblog/post_relation')->getRelatedPostIds($_categoryId);
        $posts = Mage::getResourceModel('mageplaza_betterblog/post_collection')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', 1)
            ->addAttributeToFilter('entity_id',$postIds);
        return $posts;
    }

    public function getProduct(){
        return Mage::registry('current_product');
    }
}