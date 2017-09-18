<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 1:00 PM
 */
class Stableflow_Company_Model_Parser_Entity_Product extends Stableflow_Company_Model_Parser_Entity_Abstract
{

    const MANUFACTURER_CODE_ATTRIBUTE = 'manufacturer_number';
    const MANUFACTURER_CODE_DELIMITER = '|';

    const BEHAVIOR_ADD_NEW  = 1;
    const BEHAVIOR_UPDATE   = 2;
    const BEHAVIOR_DISABLE  = 3;
    const BEHAVIOR_DELETE   = 4;

    /**
     * Update Company Product
     * @param $data array
     */
    public function update($data)
    {
        Mage::dispatchEvent('company_parser_product_update_before', array('update_data' => $data));
        $product = $this->findByCode($data['row']['code'], $data['company_id']);
        if(is_null($product)){
            $this->addNew($data);
        }
        Mage::dispatchEvent('company_parser_product_update_after', array('data' => $data));
    }

    /**
     * Create new company product
     * @param $data
     */
    protected function addNew($data, $behavior)
    {
        $product = Mage::getModel('company/product');
        switch($behavior){
            case self::BEHAVIOR_ADD_NEW :
                $_data = array(
                    'price' => $_data[self::COMPANY_PRODUCT_PRICE],
                    'price_int' => $_data[self::COMPANY_PRODUCT_INT_PRICE],
                    'price_wholesale' => $_data[self::COMPANY_PRODUCT_OPT_PRICE],
                    'created_at' => Varien_Date::now(),
                );
                $product->addData($_data);
                $id = $product->save();
                break;
            case self::BEHAVIOR_UPDATE :
                $product->load($productId);
                $_data = array(
                    'price' => $_data[self::COMPANY_PRODUCT_PRICE],
                    'price_int' => $_data[self::COMPANY_PRODUCT_INT_PRICE],
                    'price_wholesale' => $_data[self::COMPANY_PRODUCT_OPT_PRICE],
                    'updated_at' => Varien_Date::now(),
                );
                $product->addData($_data);
                $product->save();
                break;
            case self::BEHAVIOR_DISABLE :
                $product->load($productId);
                $product->setStatust(Stableflow_Company_Model_Product::DISABLE);
                $product->save();
                break;
            case self::BEHAVIOR_DELETE :
                $product->load($productId);
                $product->delete();
                break;
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
     * @param $companyId int
     * @return Stableflow_Company_Model_Product | null
     */
    public function findByCode($code, $companyId)
    {
        $companyProduct = null;
        $attribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode('catalog_product', self::MANUFACTURER_CODE_ATTRIBUTE);
        printf("? Search code: %s \n", $code);
        $productCollection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToFilter($attribute, array('like' => '%'.$code.'%'));
        printf("Products ws code found: %d\n", $productCollection->getSize());
        foreach($productCollection as $_product) {
            $_productId = $_product->getId();
            $manufacturerCode = $_product->getData(self::MANUFACTURER_CODE_ATTRIBUTE);
            printf("Product ID: %d. Have manuf numb: %s\n", $_productId, $manufacturerCode);
            $_codes = explode(self::MANUFACTURER_CODE_DELIMITER, $manufacturerCode);
            if(in_array($code, $_codes)) {
                $companyProduct = Mage::getResourceModel('company/product_collection')
                    ->addAttributeToFilter('catalog_product_id', $_productId)
                    ->addAttributeToFilter('company_id', $companyId)
                    ->getFirstItem();
                if ($companyProduct->getId()) {
                    printf("++ > Company Product ID: %d. \n", $companyProduct->getId());
                } else {
                    printf("-- > Company ID: %d don`t have Product with ID: %d.\n", $companyId, $_productId);
                }
                printf("\n");
            }
        }
        return $companyProduct;
    }

    public function findCompanyProduct($companyId, $productId)
    {

    }
}