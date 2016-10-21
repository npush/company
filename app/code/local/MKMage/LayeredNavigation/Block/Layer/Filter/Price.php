<?php 

class MKMage_LayeredNavigation_Block_Layer_Filter_Price extends Mage_Catalog_Block_Layer_Filter_Price {
    
    protected $_priceFilterMin = null;
    protected $_priceFilterMax = null;

	public function __construct() {
		
		parent::__construct();
		
		if (Mage::app()->getRequest()->isXmlHttpRequest()) {
		
			$range = explode('-',Mage::app()->getRequest()->getParam('price'));
			
			$this->_priceFilterMin = $range[0];
			$this->_priceFilterMax = $range[1];
			
		}
	
	}

    public function getPriceFilterMin() {
    	return $this->_priceFilterMin;
    }
    
    public function getPriceFilterMax() {
    	return $this->_priceFilterMax;
    }

}