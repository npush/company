<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 11/1/16
 * Time: 2:43 PM
 */

require_once 'abstract.php';

class Mage_Shell_AddAttrToSet extends Mage_Shell_Abstract{

    /**
     * Run script
     *
     */
    public function run(){
        $this->addAttrToSet('tooltops', 'Rulletka');
        //$this->addAttrToSet('tooltops', 'General');
    }

    public function addAttrToSet($attrCode, $attrGroupCode){
        if(!$attrCode || !$attrGroupCode) return;
        // This is because the you adding the attribute to catalog_products entity
        // ( there is different entities in magento ex : catalog_category, order,invoice... etc )
        $attSet = Mage::getModel('eav/entity_type')
            ->getCollection()
            ->addFieldToFilter('entity_type_code','catalog_product')
            ->getFirstItem();

        // this is the attribute sets associated with this entity
        $attSetCollection = Mage::getModel('eav/entity_type')
            ->load($attSet->getId())
            ->getAttributeSetCollection();
        $attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setCodeFilter($attrCode)
            ->getFirstItem();
        $attCode = $attributeInfo->getAttributeCode();
        $attId = $attributeInfo->getId();
        foreach ($attSetCollection as $a)
        {
            if($a->getAttributeSetName() == 'Default') continue;
            $set = Mage::getModel('eav/entity_attribute_set')->load($a->getId());
            $setId = $set->getId();
            $group = Mage::getModel('eav/entity_attribute_group')
                ->getCollection()
                ->addFieldToFilter('attribute_set_id',$setId)
                ->addFieldToFilter('attribute_group_name', $attrGroupCode)
                ->setOrder('attribute_group_id',"ASC")
                ->getFirstItem();
            $groupId = $group->getId();
            $newItem = Mage::getModel('eav/entity_attribute');
            $newItem->setEntityTypeId($attSet->getId()) // catalog_product eav_entity_type id ( usually 10 )
                ->setAttributeSetId($setId) // Attribute Set ID
                ->setAttributeGroupId($groupId) // Attribute Group ID ( usually general or whatever based on the query i automate to get the first attribute group in each attribute set )
                ->setAttributeId($attId) // Attribute ID that need to be added manually
                ->setSortOrder(10) // Sort Order for the attribute in the tab form edit
                ->save();
            echo "Attribute ".$attCode." Added to Attribute Set ".$set->getAttributeSetName()." in Attribute Group ".$group->getAttributeGroupName()."<br>\n";
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f addAttrToSet.php -- [options]

    --attr <Attribute code>
    --set  <Attribute set code>

  help                        This help
USAGE;
    }
}

$shell = new Mage_Shell_AddAttrToSet();
$shell->run();