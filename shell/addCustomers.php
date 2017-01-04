<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 1/4/17
 * Time: 11:34 AM
 */

require_once 'abstract.php';

class Mage_Shell_AddCustomers extends Mage_Shell_Abstract{

    protected $customers = [
        1 => [
            'login' => 'irina',
            'pass' => 'Irina2013',
            'first_name' => 'Ирина',
            'last_name' => 'Ждамарова',
            'email' => 'Irina.Zhdamarova@rulletka.biz'
        ],
        2 => [
            'login' => 'olga',
            'pass' => 'Olga2013',
            'first_name' => 'Ольга',
            'last_name' => 'Белоус',
            'email' => 'olga.belous@rulletka.biz'
        ]
    ];

    /**
     * Run script
     *
     */
    public function run(){
        foreach($this->customers as $customer){
            $this->addCustomer($customer);
        }
    }

    protected function addCustomer($customer){
        if(!Mage::getModel("admin/user")->loadByUsername($customer['login'])) {
            printf("Create new customer %s ", $customer['login']);
            /** @var  $customer Mage_Admin_Model_User */
            $user = Mage::getModel("admin/user")
                ->setFirstname($customer['first_name'])
                ->setLastname($customer['last_name'])
                ->setEmail($customer['email'])
                ->setUsername($customer['login'])
                ->setPassword($customer['pass'])
                ->setIsActive(1);

            try {
                $user->save();
                $user->setRoleIds(array(1))
                    ->setRoleUserId($user->getUserId())
                    ->saveRelations();

            } catch (Exception $e) {
                Zend_Debug::dump($e->getMessage());
            }
        }else{
            printf("Customer exist %s ", $customer['login']);
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f
  help                        This help
USAGE;
    }
}

$shell = new Mage_Shell_AddCustomers();
$shell->run();