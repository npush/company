<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/4/17
 * Time: 1:00 PM
 */
class Stableflow_Company_Model_Parser_Entity_Product extends Stableflow_Company_Model_Parser_Entity_Abstract
{

    const CODE_ATTRIBUTE = 'manufacturer_number';

    /**
     * Update Company Product
     * @param $data array
     */
    public function update($data)
    {
        $this->findByCode($data);
    }

    /**
     * Create new company product
     * @param $data
     */
    protected function addNew($data)
    {

        $model = Mage::getModel('company/product')->load($data['product_id']);
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
     * @return Stableflow_Company_Model_Product | null
     */
    public function findByCode($code)
    {
        $companyProduct = null;
        $attribute = Mage::getModel('eav/entity_attribute')
            ->loadByCode('catalog_product', 'manufacturer_number');
        $code = $code['row']['code'];//005454;
        printf("? Search code: %s \n", $code);
        $productCollection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToFilter($attribute, array('like' => '%'.$code.'%'));
        foreach($productCollection as $_product) {
            $_code = explode('|', $_product->getData('manufacturer_number'));
            printf("Product ID: %d. Have manuf numb: %s\n", $_product->getId(), $_product->getData('manufacturer_number'));
            $companyProduct = Mage::getResourceModel('company/product_collection')
                ->addAttributeToFilter('catalog_product_id', $_product->getId())
                ->addAttributeToFilter('company_id', $code['company_id'])
                ->getFirstItem();
            if($companyProduct->getId()) {
                printf("++ > Company Product ID: %d. \n", $companyProduct->getId());
            }else{
                printf("-- > Company ID: %d don`t have Product with ID: %d.\n", $code['company_id'], $_product->getId());
            }
            printf("\n");
        }
        return $companyProduct;
    }
}