<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/31/17
 * Time: 3:47 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Task_Edit  extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'company';
        $this->_controller = 'adminhtml_parser_task';


        $this->_addButton('quickview', array(
            'label'     => Mage::helper('adminhtml')->__('Quick Form View'),
            'onclick'   => 'quickview()',
            'class'     => 'info',
        ), -200);
        $this->_formScripts[] = "
            function quickview(){
                f1=document.forms['edit_form'];
                //to show specific element value
                var txt = document.getElementById('page_title').value;
                //to get All elements
                var txt = '';
                for (var i=0; i<f1.length; i++){
                    txt = txt + f1.elements[i].id + ' : ' + f1.elements[i].value + '\\n';
                }
                var popupDiv = document.createElement('div');
                var popupHtml = '<div id =\"popup\" style=\"position: absolute; width: 94%; padding: 10px; z-index: 10; left: 28px; background: none repeat scroll 0% 0% rgb(248, 248, 248); display: block; border: 1px solid rgb(204, 204, 204);\"></div>';
                popupHtml+='<div style=\"background: none repeat scroll 0px 0px rgb(0, 0, 0); left: 0px; min-height: 250%; min-width: 100%; opacity: 0.45; position: absolute; top: 0px; z-index: 9; display: block;\" ></div>';
                popupDiv.innerHTML = popupHtml;
                f1.appendChild(popupDiv);
                document.getElementById('popup').innerHTML = txt;
            }
        ";

    }

    public function getHeaderText()
    {
        return Mage::helper('company')->__('Task Edit');
    }
}