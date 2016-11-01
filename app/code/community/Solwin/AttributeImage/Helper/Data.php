<?php

class Solwin_AttributeImage_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getAttributeImage($optionId) {
        $images = $this->getAttributeImages();
        $image = array_key_exists($optionId, $images) ? $images[$optionId] : '';
        if ($image && (strpos($image, 'http') !== 0)) {
            $image = Mage::getDesign()->getSkinUrl($image);
        }
        return $image;
    }

    public function getAttributeImages() {
        $images = Mage::getResourceModel('eav/entity_attribute_option')->getAttributeImages();
        return $images;
    }

    public function getAttributeThumb($optionId) {
        $images = $this->getAttributeThumbs();
        $image = array_key_exists($optionId, $images) ? $images[$optionId] : '';
        if ($image && (strpos($image, 'http') !== 0)) {
            $image = Mage::getDesign()->getSkinUrl($image);
        }
        return $image;
    }

    public function getAttributeThumbs() {
        $images = Mage::getResourceModel('eav/entity_attribute_option')->getAttributeThumbs();
        return $images;
    }

    public function getAttributeHint($optionId) {
        $hints = $this->getAttributeHints();
        $hint = array_key_exists($optionId, $hints) ? $hints[$optionId] : '';
        return $hint;
    }

    public function getAttributeHints() {
        $hints = Mage::getResourceModel('eav/entity_attribute_option')->getAttributeHints();
        return $hints;
    }

    public function resizeImg($fileName, $width, $height = ''){
        $folderURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $imageURL = $folderURL . $fileName;

        $basePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $fileName;
        $newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . "resized" . DS . $fileName;
        //if width empty then return original size image's URL
        if ($width != '') {
            //if image has already resized then just return URL
            if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
                $imageObj = new Varien_Image($basePath);
                $imageObj->constrainOnly(TRUE);
                $imageObj->keepAspectRatio(FALSE);
                $imageObj->keepFrame(FALSE);
                $imageObj->resize($width, $height);
                $imageObj->save($newPath);
            }
            $resizedURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . "resized" . DS . $fileName;
        } else {
            $resizedURL = $imageURL;
        }
        return $resizedURL;
    }
}
