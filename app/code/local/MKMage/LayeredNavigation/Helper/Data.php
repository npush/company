<?php

class MKMage_LayeredNavigation_Helper_Data extends Mage_Core_Helper_Abstract {

	public function getIsActive() {
		
		$layeredNavigationXmlEnabled = Mage::helper('core')->isModuleEnabled('MKMage_LayeredNavigation') ? true : false;
		$layeredNavigationOutputEnabled = Mage::helper('core')->isModuleOutputEnabled("MKMage_LayeredNavigation") ? true : false;
		$layeredNavigationModuleEnabled = Mage::getStoreConfig('mkmage_layerednavigation/mkmage_layerednavigation_general/layerednavigation_enabled',Mage::app()->getStore()) ? true : false;
		
		return ( $layeredNavigationXmlEnabled && $layeredNavigationOutputEnabled && $layeredNavigationModuleEnabled ) ? true : false;
	
	}
	
	public function getIsPriceSliderEnabled() {
		
		return Mage::getStoreConfig('mkmage_layerednavigation/mkmage_layerednavigation_display/price_slider_enable',Mage::app()->getStore()) ? true : false;
		
	}
	
	public function getLoaderUrl() {
	
		$check = Mage::getStoreConfig('mkmage_layerednavigation/mkmage_layerednavigation_display/loader_image',Mage::app()->getStore());
		if(!empty($check)) {
			return Mage::getBaseUrl() . 'media' . DS . 'theme' . DS . Mage::getStoreConfig('mkmage_layerednavigation/mkmage_layerednavigation_display/loader_image',Mage::app()->getStore());
		} else {
			return false;
		}
	
	}
	
	public function getIsEnabledOnSearch() {
		
		return Mage::getStoreConfig('mkmage_layerednavigation/mkmage_layerednavigation_display/catalogsearch_enable',Mage::app()->getStore()) ? true : false;
		
	}
	
}