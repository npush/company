<?php

class Stableflow_Pricelists_Model_Pricelist extends Mage_Core_Model_Abstract {

    protected $uploadDir = 'pricelists';

    protected function _construct() {
        $this->_init('pricelists/pricelist');
    }

    public function getLettersRange() {
        return array(
            'A', 'B', 'C', 'D', 'E', 'F',
            'G', 'H', 'I', 'J', 'K', 'L',
            'M', 'N', 'N', 'O', 'P', 'Q',
            'R', 'S', 'T', 'U', 'V', 'W',
            'X', 'Y', 'Z'
        );
    }

    public static function getTypes(){
        return array(
            'name' => Mage::helper('stableflow_pricelists')->__('Name'),
            'price' => Mage::helper('stableflow_pricelists')->__('Price'),
//            'another_price' => Mage::helper('stableflow_pricelists')->__('Another'),
            'code' => Mage::helper('stableflow_pricelists')->__('Code'),
            'manufacturer' => Mage::helper('stableflow_pricelists')->__('Manufacturer'),
        );
    }

    public static function translateFileName($filename){
        $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' ');
        $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya', '_');
        $filename = str_replace($rus, $lat, $filename);

        return $filename;
    }

    public function getConfig() {
        return unserialize($this->configurations);
    }

    public function getRow() {
        $config = $this->getConfig();
        return $config['row'];
    }

    public function getMapping() {
        $config = $this->getConfig();
        return $config['mapping'];
    }

    public function getPathToFile() {
        return $this->uploadDir . DS . $this->getFilename();
    }

    public function setConfig($configuration) {
        $this->configurations = serialize($configuration);
    }

    /**
     * Save Model
     * @var array $params
     * @var bool $approve
     * @return Mage_Core_Model_Abstract
     * */
    public function saveModel(array $params, $approve = false) {

        if ($approve) {
            $this->setStatus(Stableflow_Pricelists_Model_Resource_Pricelist::STATUS_APPROVED);
        }

        $config = $params['config'];
        if (empty($params['config'])) {
            $config = $params['config']['value'] = [
                'letter' => '',
                'column' => ''
            ];
        }

        $row = $params['row'];
        $arrToSerialize = array();
        foreach ($config['value'] as $values) {
            $column = $values['column'];
            $letter = $values['letter'];
            $arrToSerialize['mapping'][$column] = $letter;
        }

        $arrToSerialize = array_merge($arrToSerialize, ['row' => $row]);
        $this->setConfig($arrToSerialize);
        $this->setDate('NOW');

        return $this->save();
    }
}
