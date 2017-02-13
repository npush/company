<?php
class Stableflow_Pricelists_Block_Adminhtml_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_blockGroup = 'stableflow_pricelists';
        $this->_mode = 'edit';
        $this->_controller = 'adminhtml';

        $pricelist_id = (int)$this->getRequest()->getParam($this->_objectId);

        if (! empty($pricelist_id)) {
            $this->_addButton('delete', array(
                'label'     => Mage::helper('adminhtml')->__('Delete pricelist'),
                'class'     => 'delete',
                'onclick'   => 'deleteConfirm(\''
                    . Mage::helper('core')->jsQuoteEscape(
                        Mage::helper('adminhtml')->__('Are you sure you want to do this?')
                    )
                    .'\', \''
                    . $this->getDeleteUrl()
                    . '\')',
            ));
        }

        $this->_addButton('save', array(
            'label'     => Mage::helper('adminhtml')->__('Save configurations'),
            'onclick'   => 'editForm.submit();',
            'class'     => 'save',
        ), 1);

        if(!$pricelist_id) {
            Mage::throwException($this->__('Price list with this id does not exists'));
        }
        $pricelist = Mage::getModel('pricelists/pricelist')->load($pricelist_id);

        Mage::register('current_pricelist', $pricelist);

        $this->_removeButton('reset');
    }
 
    public function getHeaderText()
    {
        $pricelist = Mage::registry('current_pricelist');
        if ($pricelist->getConfig() !== false) {
            return Mage::helper('stableflow_pricelists')->__("Edit price list '%s' configuration", $pricelist->getFilename());
        } else {
            return Mage::helper('stableflow_pricelists')->__("Add new price list configuration");
        }
    }
}
