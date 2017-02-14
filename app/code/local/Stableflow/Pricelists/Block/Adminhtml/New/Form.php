<?php
class Stableflow_Pricelists_Block_Adminhtml_New_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/save'),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            )
        );

        $fieldset = $form->addFieldset('edit_pricelist', array(
            'legend' => Mage::helper('stableflow_pricelists')->__('New Price List')
        ));

        $fieldset->addField('id_company', 'text', array(
            'label' => Mage::helper('stableflow_pricelists')->__('Company Id'),
            'required' => true,
            'name' => 'id_company',
        ));

        $fieldset->addField('file', 'file', array(
            'label'     => Mage::helper('stableflow_pricelists')->__('XLS'),
            'required'  => true,
            'class'     => 'disable',
            'name'      => 'file',
        ));


        $form->setUseContainer(true);
        $this->setForm($form);
    }

}
