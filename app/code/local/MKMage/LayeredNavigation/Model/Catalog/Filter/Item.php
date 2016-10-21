<?php
class MKMage_LayeredNavigation_Model_Catalog_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{
	/**
	 * variable marked to true when item is selected
	 *
	 * @var string
	 */
	protected $_isSelected = null;
	
	/**
	 * Set item select state manualy
	 *
	 * @return MKMage_LayeredNavigation_Model_Layer_Filter_Item
	 */
	public function setSelected($val) {
		$this->_isSelected = $val;
		return $this;
	}

	/**
	 * Check if item has been apply
	 *
	 * @return boolean
	 */
	public function isSelected() {
		if ($this->_isSelected === null) {
			$input = Mage::app()->getFrontController()->getRequest()->getParam($this->getFilter()->getRequestVar());
			$values = explode('_', $input);
			$this->_isSelected = in_array($this->getValue(), $values);
		}
		return $this->_isSelected;
	}
	
	/**
	 * Get the toggled value for use in checkbox
	 *
	 * @return string
	 */
	public function getInvertValue($val) {
		$result = $val;
		$req = Mage::app()->getFrontController()->getRequest()->getParam($this->getFilter()->getRequestVar());
		if (empty($req)) return $result;
		if ($req==$val) return '';
		
		$values = explode('_', $req);
		if (in_array($val, $values)) {
			$values = array_diff($values, array($val));	// exclude current value from request
		} else {
			$values[] = $val;	// include ...
		}
		$result = implode('_', $values);
		return $result;
	}
	
    public function getClearLinkUrl() {
		// *comment this to retrieve the clear link url in layer block
        // $clearLinkText = $this->getFilter()->getClearLinkText();
        // if (!$clearLinkText) {
            // return false;
        // }

        $urlParams = array(
            '_current' => true,
            '_use_rewrite' => true,
            '_query' => array($this->getFilter()->getRequestVar() => null),
            '_escape' => true,
        );
        return Mage::getUrl('*/*/*', $urlParams);
    }
	
    public function getUrl($invert=false) {
		// modify the url for use in checkbox type
		$val = $this->getInvertValue($this->getValue());
		if (empty($val)) return $this->getRemoveUrl();
		
		$query = array(
			$this->getFilter()->getRequestVar() => $val,
			Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
		);
        return Mage::getUrl('*/*/*', array('_current'=>true, '_use_rewrite'=>true, '_query'=>$query));
    }
    
    public function getRemoveUrl()
    {
        $query = array($this->getFilter()->getRequestVar()=>$this->getFilter()->getResetValue());
        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $query;
        $params['_escape']      = true;
        $url=str_replace('&amp;','&',Mage::getUrl('*/*/*', $params));
       // $url=$query;
        return $url;
    }
    
    public function getLabel() {
    	
    	if(!$this->getData('label')) {
    		$obj['attr_code'] = $this->getFilter()->getAttributeModel()->getAttributeCode();
    		$obj['option_ids'] = explode('_',$this->getValue());
    		
    		$attributeDetails = Mage::getSingleton("eav/config")->getAttribute("catalog_product", $obj['attr_code']);
    		$optionLabels = array();
    		
    		$out = '';
    		
    		foreach ($obj['option_ids'] as $opt) {
    			$optionLabels[] = $attributeDetails->getSource()->getOptionText($opt);
    			$out .= $attributeDetails->getSource()->getOptionText($opt) . ',';
    		}    		
    		
    		$obj['option_labels'] = $optionLabels;
    		
    		return substr($out,0,-1);
    	}
    	return $this->getData('label');
    	
    }

}
