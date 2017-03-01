<?php

/**
 * Created by nick
 * Project magento.dev
 * Date: 2/22/17
 * Time: 3:27 PM
 */

require_once 'abstract.php';

class Mage_Shell_CompanyImport extends Mage_Shell_Abstract{

    const COMPANY_ID = 0;
    const COMPANY_NAME = 1;
    const COMPANY_URL = 2;
    const COMPANY_EMAIL = 3;
    const COMPANY_TEL = 4;          //file
    const COMPANY_LOGO_IMG = 5;
    const COMPANY_ADDRESS = 6;
    const COMPANY_DESCRIPTION = 7;
    const COMPANY_CREATED_AT = 8;
    const COMPANY_TYPE = 9;         //multi select
    const COMPANY_ACTIVITY = 10;    //multi select
    const COMPANY_CITY = 11;
    const COMPANY_COUNTRY = 12;
    const COMPANY_LICENSES = 13;    //files
    const COMPANY_ADDITIONAL_IMG = 14;//files
    const COMPANY_APPROVED = 15;
    const COMPANY_BALANCE = 16;
    const COMPANY_PARENT_COMPANY_ID = 17;

    const COMPANY_PRODUCT_SKU = 0;
    const COMPANY_ID_PRODUCT = 1;
    const COMPANY_PRODUCT_PRICE = 2;
    const COMPANY_PRODUCT_OPT_PRICE = 3;
    const COMPANY_PRODUCT_INT_PRICE = 4;
    const COMPANY_PRODUCT_MEASURE = 5;
    const COMPANY_PRODUCT_EXIST = 6;
    const COMPANY_PRODUCT_DATE = 7;

    protected $_importImagePath;
    protected $_basePath;

    public function run(){
        if ($this->getArg('file')) {
            $this->_init();
            $path = $this->getArg('file');
            echo 'reading data from ' . $path . PHP_EOL;
            if (false !== ($file = fopen($path, 'r'))) {
                while (false !== ($data = fgetcsv($file, 10000, ',', '"'))) {
                    //$this->addCompany($data);
                    $this->addCompanyProduct($data);
                    printf("Adding %s \n", $data[self::COMPANY_NAME]);
                }
                fclose($file);
            }
        } else {
            echo $this->usageHelp();
        }
    }

    protected function _init(){
        $this->_importImagePath = $path = Mage::getBaseDir('media') . DS . 'import/';
        $this->_basePath = $path = Mage::getBaseDir('media') . DS . 'company';
    }

    public function addCompany($companyData){
        array_walk($companyData, function(&$value){
            $value = str_replace('\N', '',$value);
            $value = str_replace('\\\\', '',$value);
        });
        $_date = date_create_from_format('Y-M-d H:i:s', $companyData[self::COMPANY_CREATED_AT]);
        $companyModel = Mage::getModel('company/company');
        $data = array(
            'entity_id'     => $companyData[self::COMPANY_ID],
            'name'          => stripslashes($companyData[self::COMPANY_NAME]),
            'description'   => stripslashes($companyData[self::COMPANY_DESCRIPTION]),
            'image'         => $this->uploadFile($companyData[self::COMPANY_LOGO_IMG]),
            'created_at'    => $_date ? $_date : Varien_Date::now(),
            'email'         => $companyData[self::COMPANY_EMAIL],
            'url'           => $companyData[self::COMPANY_URL],
            'address_id'    => $this->_addCompanyAddress($companyData),
        );
        $companyModel->setData($data);
        $companyModel->save();
    }

    protected function _addCompanyAddress($address){
        $addressModel = Mage::getModel('company/address');
        $data = array(
            'street'        => $address[self::COMPANY_ADDRESS],
            'city'          => $address[self::COMPANY_CITY],
            'telephone'     => $address[self::COMPANY_TEL],
        );
        $addressModel->setData($data);
        $addressModel->save();
        return $addressModel->getId();
    }

    public function getAttributeId($attribute, $value){

    }

    public function addCompanyProduct($_data){
        $productId = Mage::getModel('catalog/product')->getIdBySku($_data[self::COMPANY_PRODUCT_SKU]);
        if($productId) {
            $model = Mage::getModel('company/product');
            $_date = date_create_from_format('Y-M-d H:i:s', $_data[self::COMPANY_CREATED_AT]);
            $data = array(
                'price' => $_data[self::COMPANY_PRODUCT_PRICE],
                'price_int' => $_data[self::COMPANY_PRODUCT_INT_PRICE],
                'price_wholesale' => $_data[self::COMPANY_PRODUCT_OPT_PRICE],
                'measure' => $_data[self::COMPANY_PRODUCT_MEASURE],
                'created_at' => $_date ? $_date : Varien_Date::now(),
            );

            $model->setData($data);
            $model->save();
            $companyProductId = $model->getId();
            $relation = Mage::getModel('company/relation');
            $relation->setData('company_id', $_data[self::COMPANY_ID_PRODUCT]);
            $relation->setData('company_product_id', $companyProductId);
            $relation->setData('product_id', $productId);
            $relation->save();
        }
    }

    public function uploadFile($file){
        try {
            $uploader = new Mage_ImportExport_Model_Import_Uploader($file);
            $fileInfo = pathinfo($file);
            $newFile = $fileInfo['basename'];
            $dest = $uploader->getDispretionPath($newFile);
            $path = $this->_basePath . $dest;
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $uploader->save($path, $newFile);
            $newFilename = $uploader->getUploadedFileName();
            return "{$dest}/{$newFilename}";
        }catch(Exception $e){
            echo "Error when upload file";
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f company_import.php -- --file <csv_file>
  help                        This help
USAGE;
    }
}

$shell = new Mage_Shell_CompanyImport();
$shell->run();