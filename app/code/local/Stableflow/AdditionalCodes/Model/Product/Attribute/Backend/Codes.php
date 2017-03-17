<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 3/16/17
 * Time: 5:04 PM
 */
class Stableflow_AdditionalCodes_Model_Product_Attribute_Backend_Codes extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract{

    /**
     * Load attribute data after product loaded
     *
     * @param Mage_Catalog_Model_Product $object
     */
    public function afterLoad($object)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $value = array();

        foreach ($this->_getResource()->loadCodes($object, $this) as $code) {
            $value[] = $code;
        }

        $object->setData($attrCode, $value);
    }

    /**
     * Add image to media gallery and return new filename
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string                     $file              file path of image in file system
     * @param string|array               $mediaAttribute    code of attribute with type 'media_image',
     *                                                      leave blank if image should be only in gallery
     * @param boolean                    $move              if true, it will move source file
     * @param boolean                    $exclude           mark image as disabled in product page view
     * @return string
     */
    public function addImage(Mage_Catalog_Model_Product $product, $file,
                             $mediaAttribute = null, $move = false, $exclude = true)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();
        $mediaGalleryData = $product->getData($attrCode);
        $position = 0;
        if (!is_array($mediaGalleryData)) {
            $mediaGalleryData = array(
                'images' => array()
            );
        }

        foreach ($mediaGalleryData['images'] as &$image) {
            if (isset($image['position']) && $image['position'] > $position) {
                $position = $image['position'];
            }
        }

        $mediaGalleryData['images'][] = array(
            'file'     => $fileName,

        );

        $product->setData($attrCode, $mediaGalleryData);

        if (!is_null($mediaAttribute)) {
            $this->setMediaAttribute($product, $mediaAttribute, $fileName);
        }

        return $fileName;
    }

    /**
     * Remove image from gallery
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string $file
     * @return Mage_Catalog_Model_Product_Attribute_Backend_Media
     */
    public function removeImage(Mage_Catalog_Model_Product $product, $file)
    {
        $attrCode = $this->getAttribute()->getAttributeCode();

        $mediaGalleryData = $product->getData($attrCode);

        if (!isset($mediaGalleryData['images']) || !is_array($mediaGalleryData['images'])) {
            return $this;
        }

        foreach ($mediaGalleryData['images'] as &$image) {
            if ($image['file'] == $file) {
                $image['removed'] = 1;
            }
        }

        $product->setData($attrCode, $mediaGalleryData);

        return $this;
    }

    /**
     * Clear media attribute value
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string|array $mediaAttribute
     * @return Mage_Catalog_Model_Product_Attribute_Backend_Media
     */
    public function clearMediaAttribute(Mage_Catalog_Model_Product $product, $mediaAttribute)
    {
        $mediaAttributeCodes = array_keys($product->getMediaAttributes());

        if (is_array($mediaAttribute)) {
            foreach ($mediaAttribute as $atttribute) {
                if (in_array($atttribute, $mediaAttributeCodes)) {
                    $product->setData($atttribute, null);
                }
            }
        } elseif (in_array($mediaAttribute, $mediaAttributeCodes)) {
            $product->setData($mediaAttribute, null);
        }

        return $this;
    }

    /**
     * Set media attribute value
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string|array $mediaAttribute
     * @param string $value
     * @return Mage_Catalog_Model_Product_Attribute_Backend_Media
     */
    public function setMediaAttribute(Mage_Catalog_Model_Product $product, $mediaAttribute, $value)
    {
        $mediaAttributeCodes = array_keys($product->getMediaAttributes());

        if (is_array($mediaAttribute)) {
            foreach ($mediaAttribute as $atttribute) {
                if (in_array($atttribute, $mediaAttributeCodes)) {
                    $product->setData($atttribute, $value);
                }
            }
        } elseif (in_array($mediaAttribute, $mediaAttributeCodes)) {
            $product->setData($mediaAttribute, $value);
        }

        return $this;
    }

    /**
     * Retrieve resource model
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Attribute_Backend_Media
     */
    protected function _getResource()
    {
        return Mage::getResourceSingleton('additional_codes/product_attribute_backend_codes');
    }

    /**
     * Retrive media config
     *
     * @return Mage_Catalog_Model_Product_Media_Config
     */
    protected function _getConfig()
    {
        return Mage::getSingleton('additional_codes/product_attribute_backend_codes');
    }

}