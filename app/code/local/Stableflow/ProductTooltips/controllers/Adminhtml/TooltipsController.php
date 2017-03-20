<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/20/17
 * Time: 12:49 PM
 */

class Stableflow_ProductTooltips_Adminhtml_TooltipsController extends Mage_Adminhtml_Controller_Action{

    /**
     * constructor - set the used module name
     *
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author nick
     */
    protected function _construct(){
        $this->setUsedModuleName('Stableflow_ProductTooltips');
    }

    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();

    }

    public function ajaxAction(){
        $result = new Varien_Object();

        // Output the result as JSON encoded
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($result->toJson());
    }

    public function updateAction(){
        // Load the layout
        $this->loadLayout(array('default', 'adminhtml_example_index'));

        // Get just part of the page
        $block = $this->getLayout()->getBlock('some_block_name');

        // Output the result after all processing is finished
        // or else you will get an "Headers already sent" error.
        $this->getResponse()->setBody($block->toHtml());
    }

}
