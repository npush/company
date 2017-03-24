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

        $model = Mage::getModel('company/company_attribute_source_company');

        $fieldset->addField('id_company', 'select', array(
            'label' => Mage::helper('stableflow_pricelists')->__('Company'),
            'required' => true,
            'name' => 'id_company',
            'values' => $model->getAllOptions(),
        ));

        $fieldset->addField('file', 'file', array(
            'label'     => Mage::helper('stableflow_pricelists')->__('Pricelist'),
            'required'  => true,
            'class'     => 'disable',
            'name'      => 'file',
        ));


        $form->setUseContainer(true);
        $this->setForm($form);
    }

    protected function _afterToHtml($html) {
        $html =  parent::_afterToHtml($html);
        $html .= '<script>//< ![C
            $$("#edit_form input[name=file]")[0].writeAttribute("accept", ".xls,.xlsx");
        //]]></script>';
        return $html;
    }

}
