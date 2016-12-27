<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 9/1/16
 * Time: 1:55 PM
 */

require_once 'abstract.php';

class Mage_Shell_AttributeValueImport extends Mage_Shell_Abstract{

    /**
     * Run script
     *
     */
    public function run(){
        if ($this->getArg('file')) {
            $path = $this->getArg('file');
            echo 'reading data from ' . $path . PHP_EOL;
            $file = fopen($path, 'r');
            if (false !== ($file = fopen($path, 'r'))) {
                while (false !== ($data = fgetcsv($file, 10000, ',', '"'))) {
                    echo "Attribute: " . $data[0]. "\n";
                    $this->setAttributeValue($data);
                }
                fclose($file);
            }
        } else {
            echo $this->usageHelp();
        }
    }

    protected function setAttributeValue($_data){
        $attributeCode = preg_replace('/\W+/', '_', trim(strtolower($this->_rus2translit(trim($_data[0])))));
        if($_attrLen = strlen($attributeCode) > 30){
            $attributeCode = substr($attributeCode, 0, -($_attrLen - 30));
        }
        $attributeModel = Mage::getModel('eav/entity_attribute')
            ->loadByCode(Mage::getModel('eav/entity')
            ->setType('catalog_product')
            ->getTypeId(), $attributeCode);

        if (!is_object($attributeModel) || is_null($attributeModel->getAttributeCode())) {
            $data = array(
                'is_global' => '2', // this can be global-1 or store view-1 website-2 dependent
                'frontend_input' => 'boolean', // this can be text, textarea, select, date, boolean, multiselect,price,media_image,wee
                'default_value_text' => '', // the default value depending on the input type
                'default_value_yesno' => '0', // the default value depending on the input type
                'default_value_date' => '', // the default value depending on the input type
                'default_value_textarea' => '', // the default value depending on the input type
                'is_unique' => '0', //boolean 0 or 1
                'is_required' => '0',//boolean 0 or 1
                'frontend_class' => '',
                'is_searchable' => '1', //boolean 0 or 1
                'is_visible_in_advanced_search' => '1', //boolean 0 or 1
                'is_comparable' => '0', //boolean 0 or 1
                'is_used_for_promo_rules' => '0', //boolean 0 or 1
                'is_html_allowed_on_front' => '1', //boolean 0 or 1
                'is_visible_on_front' => '1', //boolean 0 or 1 - if this should load in the product detail view
                'used_in_product_listing' => '1', //boolean 0 or 1 - if this should be loaded in the category or search listing
                'used_for_sort_by' => '0', //boolean 0 or 1
                'is_configurable' => '0', //boolean 0 or 1 - if this will be used as a super-attribute for configurable products
                'is_filterable' => '1', //boolean 0 or 1 - if this should be used in the filtered navigation
                'is_filterable_in_search' => '1', //boolean 0 or 1
                'backend_type' => 'boolean', // the available values are int, varchar, boolean, text
                'default_value' => '',
            );
            $labelText = trim($_data[0]);
            $data['apply_to'] = array('simple'); // the product type this attribute should apply
            $data['attribute_code'] = $attributeCode;

            // the label for each of your store views
            $data['frontend_label'] = array(
                0 => $labelText,
                1 => $labelText,
                3 => '',
                2 => '',
                4 => '',
            );
            //comment string
            //$data['option']['values'] = explode(';', $_data[1]);
            $attmodel = Mage::getModel('catalog/resource_eav_attribute');
            $attmodel->setEntityTypeId(Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId());
            $attmodel->setIsUserDefined(1);
            $attmodel->addData($data);
            //print_r($attmodel);
            $attmodel->save();
            // comment
            /*$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

            // here is a re-occurence of the attribute values in case they did not get properly in the first time as no attribute id was available.

            $attributeModel = Mage::getModel('eav/entity_attribute')
                ->loadByCode(Mage::getModel('eav/entity')
                    ->setType('catalog_product')->getTypeId(), $attributeCode);
            foreach ($data['option']['values'] as &$dt) {
                $arr['attribute_id'] = $attributeModel->getId();
                $arr['value']['option_name'][0] = $dt;
                $setup->addAttributeOption($arr);
            }*/
            // end comment
        }
    }

    protected function _rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',  'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f attributeSetImport.php -- --file <csv_file>
  help                        This help
USAGE;
    }
}

$shell = new Mage_Shell_AttributeValueImport();
$shell->run();