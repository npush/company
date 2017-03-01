<?php

class Stableflow_Pricelists_Block_Adminhtml_Preview extends Mage_Adminhtml_Block_Template {

    protected function _construct() {
        parent::_construct();
        $this->setTemplate('stableflow/pricelists/preview.phtml');
    }

    public function thePreview() {

        $request = $this->getRequest();

        /** @var $pricelist Stableflow_Pricelists_Model_Pricelist */
        $pricelist = Mage::getModel('pricelists/pricelist')->load($request->getParam('id'));

        $config = $request->getParam('config');
        $mapArr = array();
        foreach ($config['value'] as $values) {
            $column = $values['column'];
            $letter = $values['letter'];
            $mapArr[$column] = $letter;
        }

        /** @var Stableflow_Pricelists_Model_PricelistParser $parser */
        $parser = Mage::getModel('pricelists/pricelistParser');
        $file = Mage::getBaseDir('media') . DS . 'pricelists' . DS . $pricelist->getFilename();
        $parser->init($file, $mapArr);

        return $parser->parseFile($request->getParam('row'), 20);
    }
}
