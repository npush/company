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
        $collection = Mage::getModel('company/company')
            ->getCollection()
            ->addAttributeToSelect('id');

        foreach ($collection as $model) {
            $id = $model->getId();
            printf("%s \n", $id);
            $model = Mage::getModel('company/company')
                ->load($id);
            $model->setData('updated_at', Mage::getSingleton('core/date')->gmtDate());
            $model->save($id);
        }
    }
}

$shell = new Resave_models();
$shell->run();