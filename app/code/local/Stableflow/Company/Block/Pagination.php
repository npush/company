<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 4/4/17
 * Time: 4:06 PM
 */
class Stableflow_Company_Block_Pagination extends Mage_Core_Block_Template{

    public function __construct(){
        parent::__construct();
        //set your own collection (get data from database so you can list it)
        $collection = Mage::getModel('company/company')->getCollection();
        $this->setCollection($collection);
    }
    protected function _prepareLayout(){
        parent::_prepareLayout();
        //this is toolbar we created in the previous step
        $toolbar = $this->getLayout()->createBlock('company/toolbar');
        //get collection of your model
        $collection = Mage::getModel('company/company')->getCollection();
        //this is where you set what options would you like to  have for sorting your grid. Key is your column in your database, value is just value that will be shown in template
        $toolbar->setAvailableOrders(array('created_at'=> 'Name','id'=>'ID'));
        $toolbar->setDefaultOrder('id');
        $toolbar->setDefaultDirection("asc");
        $toolbar->setCollection($collection);
        $this->setChild('toolbar', $toolbar);
        $this->getCollection()->load();
        return $this;
    }

    //this is what you call in your .phtml file to render toolbar
    public function getToolbarHtml(){
        return $this->getChildHtml('toolbar');
    }
}