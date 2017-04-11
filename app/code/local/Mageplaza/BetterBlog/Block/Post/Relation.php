<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 12/14/16
 * Time: 1:22 PM
 */

class Mageplaza_BetterBlog_Block_Post_Relation extends Mage_Core_Block_Template{

    protected $_postIds = array();
    protected $_posts = null;
    protected $_product = null;
    protected $_categories = null;

    public function _construct(){
        parent::_construct();
        $this->_categories = $this->getProduct()->getCategoryCollection();
        foreach($this->_categories as $_category) {
            $post = Mage::getResourceModel('mageplaza_betterblog/post_relation')
                ->getRelatedPostIds($_category->getId());
            array_push($this->_postIds, $post);
        }
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
        if(is_null($this->_product)) {
            $this->_product = Mage::registry('current_product');
        }
        return $this->_product;
    }

    public function getCommetCount($postId){
        $count = Mage::getModel('mageplaza_betterblog/post_comment')->getCollection()
            ->addFieldToFilter('post_id', $postId)
            ->addFieldToFilter('status', Mageplaza_BetterBlog_Model_Post_Comment::STATUS_APPROVED)
            ->count();
        return $count ? $count : 0;
    }
}