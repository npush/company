<?php
/**
 * Created by nick
 * Project magento1.dev
 * Date: 4/13/17
 * Time: 4:06 PM
 */

require_once 'abstract.php';

class Resave_models extends Mage_Shell_Abstract
{
    public function run()
    {
        $collection = Mage::getModel('company/company')->getCollection()
            ->addAttributeToSelect('id');

        foreach ($collection as $model) {
            printf("%s \n", $model->getId());
            $model = Mage::getModel('company/company')->load($model->getId());
            $model->save();
        }
    }
}

$shell = new Resave_models();
$shell->run();