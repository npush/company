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
}