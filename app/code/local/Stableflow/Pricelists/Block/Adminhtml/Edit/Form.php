<?php
class Stableflow_Pricelists_Block_Adminhtml_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

	protected function _prepareForm()
    {
        $pricelist = Mage::registry('current_pricelist');

        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/saveConfig'),
                'method' => 'post'
            )
        );

        $fieldset = $form->addFieldset('edit_pricelist', array(
            'legend' => Mage::helper('stableflow_pricelists')->__('Price List Configuration')
        ));

        $fieldset->addField('id', 'hidden', array(
            'required' => true,
            'name' => 'id',
            'value' => $pricelist->getId()
        ));


        $fieldset->addField('row', 'text', array(
            'required' => true,
            'name' => 'row',
            'label' => 'Row',
            'style' => 'width: 160px',
            'note'  => $this->helper('stableflow_pricelists')->__('Line number at which to start'),
            'value' => $pricelist->getRow()
        ));

        $fieldset->addField('config', 'text', array(
            'name'      => 'config',
            'label'     => Mage::helper('stableflow_pricelists')->__('Mapping'),
            'required'  => true,
        ));

        $elem = $form->getElement('config');

        $elem->setRenderer(
            $this->getLayout()->createBlock('stableflow_pricelists/adminhtml_edit_renderer_preview')
        );

        $form->setUseContainer(true);
        $this->setForm($form);

    }

}
