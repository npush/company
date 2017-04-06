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
    const COMPANY_TYPE_ID = 18;
    const COMPANY_ACTIVITY_ID = 19;

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

    protected $_countries = null;

    public function run(){
        if ($this->getArg('file')  && $this->getArg('mode')) {
            $this->_init();
            $path = $this->getArg('file');
            $routine = $this->getArg('mode');
            echo 'reading data from ' . $path . PHP_EOL;
            if (false !== ($file = fopen($path, 'r'))) {
                while (false !== ($data = fgetcsv($file, 10000, ',', '"'))) {
                    switch($routine) {
                        case 'company':
                            $this->addCompany($data);
                            printf("Adding %s \n", $data[self::COMPANY_NAME]);
                            break;
                        case 'product':
                            $this->addCompanyProduct($data);
                            printf("Adding %s \n", $data[self::COMPANY_PRODUCT_SKU]);
                            break;
                        default:
                            printf("Incorrect parameter... %s \n", $routine);
                    }
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
        $description = stripslashes($companyData[self::COMPANY_DESCRIPTION]);
        //$sort_descr = strlen($description) > 100 ? substr($description, 0, 270) : $description;

        $pattern = '/.{3,}[\?|\!|\.]/mu';
        $result = array();
        if (preg_match_all($pattern, $description, $result) && !empty($result[0])){
            $sort_descr =  $result[0];
        }else{
            $sort_descr =  $description;
        }


        if($companyModel->load($companyData[self::COMPANY_ID]) && $companyModel->getId()){
            $addressId = $companyModel->getAddressId();
            $data = array(
                //'name'          => stripslashes($companyData[self::COMPANY_NAME]),
                'short_description' => $this->_formatDescription($sort_descr[0]),
                'description'   => $this->_formatDescription($description),
                //'email'         => $companyData[self::COMPANY_EMAIL],
                //'url'           => $companyData[self::COMPANY_URL],
                //'type'          => $companyData[self::COMPANY_TYPE_ID],
                'activity'      => str_replace('|', ',', $companyData[self::COMPANY_ACTIVITY_ID]),
            );
            $companyModel->setData($data);
            try {
                $companyModel->setId($companyData[self::COMPANY_ID])->save();
                printf ("Data updated successfully. \n");

            } catch (Exception $e){
                echo $e->getMessage();
            }
            $this->_addCompanyAddress($companyData, $addressId);
        }else {
            $data = array(
                'entity_id'     => $companyData[self::COMPANY_ID],
                'name'          => stripslashes($companyData[self::COMPANY_NAME]),
                'short_description' => $this->_formatDescription($sort_descr[0]),
                'description'   => $this->_formatDescription($description),
                'image'         => $this->uploadFile($this->_importImagePath . $companyData[self::COMPANY_LOGO_IMG]),
                'created_at'    => $_date ? $_date : Varien_Date::now(),
                'email'         => $companyData[self::COMPANY_EMAIL],
                'url'           => $companyData[self::COMPANY_URL],
                'address_id'    => $this->_addCompanyAddress($companyData),
                'type'          => $companyData[self::COMPANY_TYPE_ID],
                'activity'      => str_replace('|', ',', $companyData[self::COMPANY_ACTIVITY_ID]),
            );
            $companyModel->setData($data);
            $companyModel->save();
        }
    }

    protected function _addCompanyAddress($address, $id = null){
        $addressModel = Mage::getModel('company/address');
        if($addressModel->load($id) && $addressModel->getId()){
            $data = array(
                'street' => $address[self::COMPANY_ADDRESS],
                'city' => $address[self::COMPANY_CITY],
                'telephone' => $address[self::COMPANY_TEL],
                'country_id' => $this->_convertCountryName($address[self::COMPANY_COUNTRY]),
                'email' => $address[self::COMPANY_EMAIL],
                'postcode' => '',
            );
            $addressModel->setData($data);
            try {
                $addressModel->setId($id)->save();
                printf ("Data Address updated successfully. \n");

            } catch (Exception $e){
                echo $e->getMessage();
            }
        }else {
            $data = array(
                'street' => $address[self::COMPANY_ADDRESS],
                'city' => $address[self::COMPANY_CITY],
                'telephone' => $address[self::COMPANY_TEL],
                'country_id' => $this->_convertCountryName($address[self::COMPANY_COUNTRY]),
                'email' => $address[self::COMPANY_EMAIL],
                'postcode' => '',
            );
            $addressModel->setData($data);
            $addressModel->save();
        }
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
            echo $file . "\n";
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
     * Convert Country cyrillic name to iSO code
     * @param $name
     * @return string ISO code
     */

    protected function _convertCountryName($name){
        if(!$this->_countries){
            $this->_initConvertCountry();
        }
        return $this->_countries[$name];
    }

    protected function _initConvertCountry(){
        $headers = null;
        $csvFilePath = __DIR__ . DS . 'countries.csv';
        $csvFile = new Varien_File_Csv();
        if (false !== ($file = fopen($csvFilePath, 'r'))) {
            while (false !== ($data = fgetcsv($file, 10000, ';', '"'))) {
                if(!$headers){
                    $headers = $data;
                    continue;
                }
                $this->_countries[$data[2]] = $data[6];
            }
            fclose($file);
        }
    }

    protected function _formatDescription($text){
        $text = preg_replace('/^\s*/iUs', '', $text);
        $text = preg_replace('/\s*$/iUs', '', $text);
        $text = stripslashes($text);
        $text = preg_replace('/\[b\](.*)\[\/b\]/iUs', '<b>$1</b>', $text);
        $text = preg_replace('/\[i\](.*)\[\/i\]/iUs', '<i>$1</i>', $text);
        $text = preg_replace('/\[u\](.*)\[\/u\]/iUs', '<u>$1</u>', $text);
        $text = preg_replace('/\[img:(.*)\]/iUs', '<img src="$1"/>', $text);
        $strings = explode(PHP_EOL, $text);
        $i = 0;
        $result = '';
        $startUl = false;
        while(isset($strings[$i])){
            if(preg_match('/^\s*(-|•)\s*/u', $strings[$i])){
                if(!$startUl){
                    $result .= PHP_EOL .'<ul>' . PHP_EOL;
                    $startUl = true;
                }
                $result .= preg_replace('/^\s*(-|•)\s*/u', '<li>', $strings[$i]);
                $result .= '</li>'. PHP_EOL;
            }/*elseif(preg_match_all('/(?!([^\s]*):)([^;]*)(;|.$)/u', $strings[$i], $out, PREG_SET_ORDER)) {
            if(!$startUl){
                $position = strpos($strings[$i], ":");
                $result .= '<b>' . substr($strings[$i], 0, $position) . '</br>';
                $result .= PHP_EOL .'<ul>' . PHP_EOL;
                $startUl = true;
            }
            print_r($strings[$i]);print_r($out);die();
            foreach ($out as $_str) {
                $result .= '<li>' . $_str . '</li>'. PHP_EOL;
            }
        }*/else{
                if($startUl){
                    $result .= '</ul>' . PHP_EOL;
                    $startUl = false;
                }
                $result .= '<p>' . $strings[$i] . '</p>' . PHP_EOL;
            }
            $i++;
        }
        if($startUl){
            $result .= PHP_EOL . '</ul>' . PHP_EOL;
            $startUl = false;
        }
        return $result;
    }


    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f company_import.php -- --file <csv_file> --mode <company | product>
  help                        This help
USAGE;
    }
}

$shell = new Mage_Shell_CompanyImport();
$shell->run();