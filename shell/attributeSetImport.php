<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/23/16
 * Time: 12:25 PM
 */
//ini_set('memory_limit', '4096M');
require_once 'abstract.php';

class Mage_Shell_AttributeSetImport extends Mage_Shell_Abstract {

    const ATTRIBUTE_SET = 0;
    const ATTRIBUTES = 1;
    const ATTRIBUTE_LABELS_ADMIN = 2;
    const ATTRIBUTE_LABELS_DEFAULT = 3;

    protected $_attributeSetId;

    /**
     * Run script
     *
     */
    public function run(){
        if ($this->getArg('file')) {
            $path = $this->getArg('file');
            echo 'reading data from ' . $path . PHP_EOL;
            if (false !== ($file = fopen($path, 'r'))) {
                while (false !== ($data = fgetcsv($file, 10000, ',', '"'))) {
                    printf("\n ---  mem usage: %d   ---\n",memory_get_usage());
                    printf("-- Attribute set - %s --\n", preg_replace('/\W+/u', '_', trim($data[self::ATTRIBUTE_SET])));
                    $this->createAttributeSet($data);
                }
                fclose($file);
            }
        } else {
            echo $this->usageHelp();
        }
    }

    protected function collectAttributes($data, $delimiter = ';'){
        return explode($delimiter, $data);
    }

    protected function createAttributeSet($_data){
        $data['set-name'] = $_data[self::ATTRIBUTE_SET];
        //$entityTypeId = Mage::getModel('catalog/product')->getResource()->getTypeId();

        $entityTypeId = Mage::getModel('eav/entity')
            ->setType('catalog_product')
            ->getTypeId();
        $attributeSetName = 'Default';
        //$attributeSetName = '_attribute_set';
        $attributeSetId = Mage::getModel('eav/entity_attribute_set')
            ->getCollection()
            ->setEntityTypeFilter($entityTypeId)
            ->addFieldToFilter('attribute_set_name', $attributeSetName)
            ->getFirstItem()
            ->getAttributeSetId();
        $cloneSetId = $attributeSetId; // Default Attribute set

        //make sure an attribute set with the same name doesn't already exist

        $model = Mage::getModel('eav/entity_attribute_set')
            ->getCollection()
            ->setEntityTypeFilter($entityTypeId)
            ->addFieldToFilter('attribute_set_name',$data['set-name'])
            ->getFirstItem();
        if(!is_object($model)){
            $model = Mage::getModel('eav/entity_attribute_set');
        }
        if(!is_numeric($model->getAttributeSetId())){
            $new = true;
        }
        $model->setEntityTypeId($entityTypeId);

        $model->setAttributeSetName($data['set-name']);
        $model->validate();
        $model->save();

        //if the set is new use the Magento Default Attributeset as a skeleton template for the default attributes
        if($new){
            $model->initFromSkeleton($cloneSetId)->save();
        }
        //in $model you have your attribute set already loaded

        $modelGroup = Mage::getModel('eav/entity_attribute_group');
        $modelGroup->setAttributeGroupName('Rulletka');
        $modelGroup->setAttributeSetId($model->getAttributeSetId());
        $model->setGroups(array($modelGroup));
        $model->save();

        $attributes = $this->collectAttributes($_data[self::ATTRIBUTES]);
        $attributeLabels = $this->collectAttributes($_data[self::ATTRIBUTE_LABELS_ADMIN]);
        $i = 0;
        //load your attribute if it exists, by your attributecode (alphanumeric and underscores only)
        foreach($attributes as $_attribute) {
            // Get sort order from name
            preg_match('/\{(\d+)\}/', $_attribute, $sortOrder);
            $sortOrder = (int)$sortOrder[1];
            // Replace new name
            $attributeCode = preg_replace('/\{(\d+)\}/', '', $_attribute);

            $attributeModel = Mage::getModel('eav/entity_attribute')
                ->loadByCode(Mage::getModel('eav/entity')
                    ->setType('catalog_product')->getTypeId(), $attributeCode);
            print_r("Attribute: {$attributeLabels[$i]} Code: {$attributeCode} Position: {$sortOrder} \n");
            if (!is_object($attributeModel) || is_null($attributeModel->getAttributeCode())) {
                print_r("not found: create ...  \n");
                print_r("+ -> Attribute: {$attributeLabels[$i]} Code: {$attributeCode} \n");
                $data = array(
                    'is_global' => '2', // this can be global or store view dependent
                    'frontend_input' => 'text', // this can be text, textarea, select, date, boolean, multiselect,price,media_image,wee
                    'default_value_text' => '', // the default value depending on the input type
                    'default_value_yesno' => '0', // the default value depending on the input type
                    'default_value_date' => '', // the default value depending on the input type
                    'default_value_textarea' => '', // the default value depending on the input type
                    'is_unique' => '0', //boolean 0 or 1
                    'is_required' => '0',//boolean 0 or 1
                    'frontend_class' => '',
                    'is_searchable' => '1', //boolean 0 or 1
                    'is_visible_in_advanced_search' => '1', //boolean 0 or 1
                    'is_comparable' => '1', //boolean 0 or 1
                    'is_used_for_promo_rules' => '0', //boolean 0 or 1
                    'is_html_allowed_on_front' => '1', //boolean 0 or 1
                    'is_visible_on_front' => '1', //boolean 0 or 1 - if this should load in the product detail view
                    'used_in_product_listing' => '1', //boolean 0 or 1 - if this should be loaded in the category or search listing
                    'used_for_sort_by' => '0', //boolean 0 or 1
                    'is_configurable' => '0', //boolean 0 or 1 - if this will be used as a super-attribute for configurable products
                    'is_filterable' => '0', //boolean 0 or 1 - if this should be used in the filtered navigation
                    'is_filterable_in_search' => '0', //boolean 0 or 1
                    'backend_type' => 'varchar', // the available values are int, varchar, boolean, text
                    'default_value' => '',
                );
                $labelText = $attributeLabels[$i];
                $data['apply_to'] = array('simple'); // the product type this attribute should apply
                $data['attribute_code'] = $attributeCode;

                // the label for each of your store views
                $data['frontend_label'] = array(
                    0 => $labelText,            // Admin
                    1 => $labelText,            //Default store
                );
                $data['option']['values'] = array();
                $attmodel = Mage::getModel('catalog/resource_eav_attribute');
                $attmodel->addData($data);

                // the atttribute set is loaded in $model and attribute group in $modelGroup

                $attmodel->setAttributeSetId($model->getAttributeSetId());
                $attmodel->setAttributeGroupId($modelGroup->getAttributeGroupId());

                $attmodel->setEntityTypeId(Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId());
                $attmodel->setIsUserDefined(1);

                $attmodel->save();
                //$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

                // here is a re-occurence of the attribute values in case they did not get properly in the first time as no attribute id was available.

                $attributeModel = Mage::getModel('eav/entity_attribute')
                    ->loadByCode(Mage::getModel('eav/entity')
                        ->setType('catalog_product')->getTypeId(), $attributeCode);
                /*foreach ($data['option']['values'] as &$dt) {
                    $arr['attribute_id'] = $attributeModel->getId();
                    $arr['value']['option_name'][0] = $dt;
                    $setup->addAttributeOption($arr);
                }*/

                $smodel = Mage::getModel('eav/entity_setup', 'core_setup');
                $attributeGroupId = $smodel->getAttributeGroup('catalog_product', $model->getAttributeSetId(), 'Rulletka');
                /*$smodel->addAttributeToSet(
                    'catalog_product',
                    $model->getAttributeSetId(),
                    $attributeGroupId['attribute_group_id'],
                    $attributeModel->getId(),
                    $sortOrder
                );*/
                $smodel->addAttributeToGroup(
                    'catalog_product',
                    $model->getAttributeSetId(),
                    $attributeGroupId['attribute_group_id'],
                    $attributeModel->getId(),
                    $sortOrder
                );

            } else {
                /*$data['option']['values'] = array();
                foreach ($data['option']['values'] as &$dt) {
                    $dt = trim($dt);
                }
                $setup = new Mage_Eav_Model_Entity_Setup('core_setup');

                foreach ($data['option']['values'] as &$dt) {
                    $arr['attribute_id'] = $attributeModel->getId();
                    $options = $attributeModel->getSource()->getAllOptions(false);
                    foreach ($options as $option) {
                        $existing_options[] = $option['label'];
                    }
                    if (!in_array($dt, $existing_options)) {
                        $arr['value']['option_name'][0] = $dt;
                        $setup->addAttributeOption($arr);
                    }
                }
                */
                $smodel = Mage::getModel('eav/entity_setup', 'core_setup');
                $attributeGroupId = $smodel->getAttributeGroup('catalog_product', $model->getAttributeSetId(), 'Rulletka');
                /*$smodel->addAttributeToSet(
                    'catalog_product',
                    $model->getAttributeSetId(),
                    $attributeGroupId['attribute_group_id'],
                    $attributeModel->getId(),
                    $sortOrder
                );*/
                $smodel->addAttributeToGroup(
                    'catalog_product',
                    $model->getAttributeSetId(),
                    $attributeGroupId['attribute_group_id'],
                    $attributeModel->getId(),
                    $sortOrder
                );
            }
            $i++;
        }
    }

    protected function _getAttributeSetId()
    {
        if (is_null($this->_attributeSetId)) {
            $this->_attributeSetId = Mage::getModel('eav/entity_type')->load('catalog/product', 'entity_model')->getDefaultAttributeSetId();
        }
        return $this->_attributeSetId;
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

$shell = new Mage_Shell_AttributeSetImport();
$shell->run();