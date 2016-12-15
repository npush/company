<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/14/16
 * Time: 1:22 PM
 */

class Mageplaza_BetterBlog_Block_Post_Relation extends Mage_Core_Block_Template{

    protected $_postIds;
    protected $_posts;
    protected $_product;
    protected $_categoryId;

    public function _construct(){
        parent::_construct();
        $this->_categoryId = $this->getProduct()->getCategoryId();
        $this->_postIds = Mage::getResourceModel('mageplaza_betterblog/post_relation')
            ->getRelatedPostIds($this->_categoryId);
    }

    protected function _prepareLayout(){
        parent::_prepareLayout();
        return $this;
    }

    public function getPosts(){
        if(!$this->_posts && $this->_postIds) {
            $this->_posts = Mage::getResourceModel('mageplaza_betterblog/post_collection')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status', 1)
                ->addAttributeToFilter('entity_id', $this->_postIds);
        }
        return $this->_posts;
    }

    public function getProduct(){
        if(!$this->_product) {
            $this->_product = Mage::registry('current_product');
        }
        return $this->_product;
    }
}