<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 12/16/16
 * Time: 1:43 PM
 */
class Stableflow_Company_Helper_Image extends Mage_Core_Helper_Abstract{

    protected $_subdir          = '';

    public function getImageBaseDir()
    {
        return Mage::getBaseDir('media').DS.$this->_subdir.DS.'image';
    }

    /**
     * get the image url for object
     *
     * @access public
     * @return string
     */
    public function getImageBaseUrl(){
        return Mage::getBaseUrl('media').$this->_subdir.DS.'image';
    }
}