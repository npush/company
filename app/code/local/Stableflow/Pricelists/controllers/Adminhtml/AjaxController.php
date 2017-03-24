<?php

class Stableflow_Pricelists_Adminhtml_AjaxController extends Mage_Adminhtml_Controller_Action {

    public function previewAction(){
        $this->loadLayout();
        $myBlock = $this->getLayout()->createBlock('stableflow_pricelists/adminhtml_preview');
        $myHtml = $myBlock->toHtml();
        $this->getResponse()
            ->setHeader('Content-Type', 'text/html')
            ->setBody($myHtml);
        return;
    }


    public function approveAction() {

        $this->loadLayout();
        $response = $this->getResponse()->setHeader('Content-Type', 'application/json');

        try {
            $request = $this->getRequest();
            $id = $request->getParam('id');

            /** @var $priceList Stableflow_Pricelists_Model_Pricelist */
            $priceList = Mage::getModel('pricelists/pricelist')->load($id);

            $priceList->saveModel([
                'row' => $request->getParam('row'),
                'config' => $request->getParam('config')
            ], true);

            /** @var Stableflow_Pricelists_Model_PricelistParser $parser */
            $parser = Mage::getModel('pricelists/pricelistParser');

            /** @var Stableflow_Pricelists_Model_Pricelist $pricelist */
            $file = Mage::getBaseDir('media') . DS . $priceList->getPathToFile();

            $config = $priceList->getConfig();
            $parser->init($file, $config['mapping']);
            $parser->parseFile((int) $config['row']);
            $status = $parser->updatePrice();

            if($status['status']) {
                $result['type'] = 'success';
                $result['message'] = Mage::helper('stableflow_pricelists')->__('Configuration saved. Prices successfully updated.');
                $result['message'] .= Mage::helper('stableflow_pricelists')->__(" Skipped Items: {$status['skipped']}, Saved Items: {$status['saved']}, Total: {$status['total']}");
            } else {
                $result['type'] = 'error';
                $result['message'] = "code required";
            }

        } catch (Exception $e) {
            $result['type'] = 'error';
            $result['message'] = Mage::helper('stableflow_pricelists')->__($e->getMessage());
            Mage::log(Mage::helper('stableflow_pricelists')->__($e->getMessage()), null, 'updating_price.log');
        }

        $response->setBody(json_encode($result));
        return;
    }
}