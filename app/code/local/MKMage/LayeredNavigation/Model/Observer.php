<?php

class MKMage_LayeredNavigation_Model_Observer {

	public function checkIfActive() {
	
        $isRewriteEnabled = Mage::helper('layerednavigation')->getIsActive();
        
        if ($isRewriteEnabled) {
        	
        	Mage::getConfig()->setNode('global/models/catalog/rewrite/layer',
        		'MKMage_LayeredNavigation_Model_Catalog_Layer');
        	
        	Mage::getConfig()->setNode('global/models/catalog/rewrite/layer_filter_item',
        		'MKMage_LayeredNavigation_Model_Catalog_Filter_Item');
        		
        	Mage::getConfig()->setNode('global/models/catalog/rewrite/layer_filter_attribute',
        		'MKMage_LayeredNavigation_Model_Catalog_Filter_Attribute');
        	
        	Mage::getConfig()->setNode('global/models/catalog/rewrite/layer_filter_price',
        		'MKMage_LayeredNavigation_Model_Catalog_Filter_Price');
        	
        	Mage::getConfig()->setNode('global/models/catalog_resource/rewrite/layer_filter_attribute',
        		'MKMage_LayeredNavigation_Model_Resource_Catalog_Filter_Attribute');
        		
        	Mage::getConfig()->setNode('frontend/layout/updates/layerednavigation/file',
        		'mkmage/layerednavigation.xml');
        		
        	if(Mage::helper('layerednavigation')->getIsEnabledOnSearch()) {
        	
        		Mage::getConfig()->setNode('global/models/updates/catalogsearch/rewrite/layer',
        			'MKMage_LayeredNavigation_Model_Search_Layer');
        			
        		Mage::getConfig()->setNode('global/models/updates/catalogsearch/rewrite/layer_filter_attribute',
        			'MKMage_LayeredNavigation_Model_Search_Filter_Attribute');
        	
        	}

        }
        
	}

	public function validateSystemSettings(Varien_Event_Observer $observer) {
		
		/*if (Mage::getStoreConfig('mkmage_layerednavigation/mkmage_layerednavigation_general/email') != '') {
		
			if (Mage::getStoreConfig('mkmage_layerednavigation/mkmage_layerednavigation_general/email_sent') != 1) {

				$body = '<p>Layered Navigation registration for ' . Mage::getBaseUrl() . '</p>';
		
				$email = Mage::getModel('core/email');
				$email->setToName('MkMage');
				$email->setToEmail('igor@mkmage.com');
				$email->setBody($body);
				$email->setSubject('Layered Navigation Customer Registration');
				$email->setFromEmail(Mage::getStoreConfig('mkmage_layerednavigation/mkmage_layerednavigation_general/email'));
				$email->setFromName(Mage::getBaseUrl());
				$email->setType('html');

				try {
					$email->send();
					Mage::getSingleton('core/session')->addSuccess('Thank you for registering with us.');
				}
				catch (Exception $e) {
					Zend_Debug::dump($e->getMessage());
					Mage::getSingleton('core/session')->addError('Could not proccess your request.');
				}
		
				Mage::getConfig()->saveConfig('mkmage_layerednavigation/mkmage_layerednavigation_general/email_sent', 1);
				Mage::getConfig()->saveConfig('mkmage_layerednavigation/mkmage_layerednavigation_general/layerednavigation_enabled', 1);
		
			}
		
		} else {*/
		Mage::getSingleton('core/session')->addSuccess('Thank you for registering with us.');
			Mage::getConfig()->saveConfig('mkmage_layerednavigation/mkmage_layerednavigation_general/layerednavigation_enabled', 1);
			Mage::getConfig()->saveConfig('mkmage_layerednavigation/mkmage_layerednavigation_general/email_sent', 1);
			//Mage::getSingleton('core/session')->addWarning('Please enter email address to register the extension.');
			
		/*}*/
	
	}

	public function initCategoryAjax($event) {
		
		$layout = Mage::getSingleton('core/layout');
		
		if (!$layout)
			return;
		
		if (!Mage::app()->getRequest()->isXmlHttpRequest())
			return;
			       
		$layout->removeOutputBlock('root');
		 
		Mage::app()->getFrontController()->getResponse()->setHeader('content-type', 'application/json');

		$page = $layout->getBlock('product_list');
		
		if (!$page)
			return;
			
		$block='';
		
		foreach ($layout->getAllBlocks() as $child){
			if (!in_array($child->getNameInLayout(), array('catalog.leftnav'))){
				continue;
			}
			$block = $child;			
		}
		
		if (!$block)
			return;
		     
		$container = $layout->createBlock('core/template', 'sparx_container');
		//$container->setData('js', $layout->createBlock('core/template', 'ajaxfilter_js')->setTemplate('ajaxfilter/js.phtml')->toHtml());
		$container->setData('block', $block->toHtml());
		$container->setData('page', $page->toHtml());
		
		$layout->addOutputBlock('sparx_container', 'toJson');			

	}

	public function initSearchAjax($event) {
		
		$layout = Mage::getSingleton('core/layout');
		
		if (!$layout)
			return;
		
		if (!Mage::app()->getRequest()->isXmlHttpRequest())
			return;
			       
		$layout->removeOutputBlock('root');
		 
		Mage::app()->getFrontController()->getResponse()->setHeader('content-type', 'application/json');

		$page = $layout->getBlock('search_result_list');
		
		if (!$page)
			return;
			
		$block='';
		
		foreach ($layout->getAllBlocks() as $child){
			if (!in_array($child->getNameInLayout(), array('catalogsearch.leftnav'))){
				continue;
			}
			$block = $child;			
		}
		
		if (!$block)
			return;
		     
		$container = $layout->createBlock('core/template', 'sparx_container');
		//$container->setData('js', $layout->createBlock('core/template', 'ajaxfilter_js')->setTemplate('ajaxfilter/js.phtml')->toHtml());
		$container->setData('block', $block->toHtml());
		$container->setData('page', $page->toHtml());
		
		$layout->addOutputBlock('sparx_container', 'toJson');			

	}

}