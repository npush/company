<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 1:00 PM
 */
class Stableflow_Company_Model_Parser_Entity_Product extends Stableflow_Company_Model_Parser_Entity_Abstract
{
    /**
     * Update Company Product
     * @param $data array
     */
    public function update($data)
    {
        $this->findByCode($data['code']);
    }

    /**
     * Create new company product
     * @param $data
     */
    protected function addNew($_data)
    {
        if($productId) {
            $model = Mage::getModel('company/product');
            $data = array(
                'price' => $_data[self::COMPANY_PRODUCT_PRICE],
                'price_int' => $_data[self::COMPANY_PRODUCT_INT_PRICE],
                'price_wholesale' => $_data[self::COMPANY_PRODUCT_OPT_PRICE],
                'measure' => $_data[self::COMPANY_PRODUCT_MEASURE],
                'created_at' => Varien_Date::now(),
            );

            $model->setData($data);
            $model->save();
        }
    }

    /**
     * Delete company product
     * @param $data array
     */
    public function remove($data)
    {

    }

    /**
     * Find product by manufacture code
     * @param $code string
     */
    public function findByCode($code)
    {


        $attribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode('catalog_product', 'manufacturer_number');
        $attributeId = $attribute->getData('attribute_id');

        $collection = Mage::getResourceModel('eav/entity_attribute_collection')
            //->setCodeFilter($attributeId)
            ->addFieldToFilter('attribute_id', $attributeId);
            //->setStoreFilter(0, false);

        $preparedManufacturers = array();
        foreach($collection as $value) {
            $preparedManufacturers[$value->getOptionId()] = $value->getValue();
        }


        if (count($preparedManufacturers)) {
            foreach($preparedManufacturers as $optionId => $value) {
                $products = Mage::getModel('catalog/product')->getCollection();
                $products->addAttributeToSelect('manufacturer_number');
                $products->addFieldToFilter(array(
                    array('attribute'=>'manufacturer', 'eq'=> $optionId,
                    )));

                echo  $value . " - (" . $optionId . ") - (Products: ".count($products).")";
            }
        }


    }
}