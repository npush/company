<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 12/16/16
 * Time: 1:41 PM
 */

class Stableflow_Company_Model_Company_Attribute_Backend_Image extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract{

    /**
     * Save uploaded file and set its name to company object
     *
     * @access public
     * @param Varien_Object $object
     * @return null
     */
    public function afterSave($object){
    $value = $object->getData($this->getAttribute()->getName());
    if (is_array($value) && !empty($value['delete'])) {
        $object->setData($this->getAttribute()->getName(), '');
        $this->getAttribute()->getEntity()
            ->saveAttribute($object, $this->getAttribute()->getName());
        return;
    }

    $path = Mage::helper('company/image')->getImageBaseDir();

    try {
        $uploader = new Varien_File_Uploader($this->getAttribute()->getName());
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $result = $uploader->save($path);
        $object->setData($this->getAttribute()->getName(), $result['file']);
        $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
        } catch (Exception $e) {
            if ($e->getCode() != 666) {
                //throw $e;
            }
            return;
        }
    }
}
