<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 11/21/16
 * Time: 1:37 PM
 */
class Satbleflow_CustomMenu_Block_Menu extends Mage_Catalog_Block_Navigation{

    public function _prepareLayout() {
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addCss('css/magebuzz/catsidebarnav/click.css');
            $headBlock->addItem('skin_js','js/magebuzz/catsidebarnav/click2click.js');
        }
        return parent::_prepareLayout();
    }

    public function getMenunav() {
        if (!$this->hasData('menunav')) {
            $this->setData('menunav', Mage::registry('menunav'));
        }
        return $this->getData('menunav');
    }
}