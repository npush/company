<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/31/17
 * Time: 3:48 PM
 */
class Stableflow_Company_Block_Adminhtml_Parser_Task_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _construct()
    {
        parent::_construct();

        $form = new Varien_Data_Form(array(
            'id'      => 'edit_form',
            'method'  => 'post',
            'enctype' => 'multipart/form-data',
            'action'  => $this->getUrl('*/parser_task/saveTask', array('_current' => true)),
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return $this;
    }

    protected function _prepareForm(){

        $task = Mage::registry('current_task');
        $config = Mage::getModel('company/parser_config')->getConfigCollection($this->getCompanyId());
        foreach($config as $_config){
            $values[] =  array(
                'value'     => $_config->getId(),
                'label'     => $_config->getData('description'),
            );
        }
        $fieldset = $this->getForm()->addFieldset('taskparam_form', array(
            'legend' => Mage::helper('company')->__('Task Parameters')
        ));
        $fieldset->addField('entity_id', 'hidden', array(
            'name'  => 'entity_id',
            'values' => ''
        ));
        $fieldset->addType('text-file', Mage::getConfig()->getBlockClassName('company/adminhtml_parser_helper_file'));
        $fieldset->addField('name', 'text-file', array(
            'label' => Mage::helper('company')->__('File'),
            'value'  => '',
            //'class' => 'required-entry',
            //'required' => true,
            'name' => 'name',

        ));
        $fieldset->addField('config_id', 'select', array(
            'label' => Mage::helper('company')->__('Select Configuration'),
            'name'  => 'config_id',
            'class' => 'required-entry',
            'required' => true,
            'values' => $values
        ));
        $fieldset->addField('status_id', 'select', array(
            'label'     => Mage::helper('company')->__('Is Active'),
            'name'      => 'status_id',
            'values'    => array(
                array(
                    'value'     => 1,
                    'label'     => Mage::helper('company')->__('Yes'),
                ),
                array(
                    'value'     => 2,
                    'label'     => Mage::helper('company')->__('No'),
                ),
            ),
        ));
        $this->getForm()->addValues($task->getData());
        return parent::_prepareForm();
    }

    public function getCompanyId()
    {
        return Mage::getSingleton('adminhtml/session')->getCompanyId();
    }
}