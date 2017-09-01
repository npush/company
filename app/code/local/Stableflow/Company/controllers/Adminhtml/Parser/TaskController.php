<?php

/**
 * Created by nick
 * Project magento1.dev
 * Date: 8/31/17
 * Time: 11:52 AM
 */
class Stableflow_Company_Adminhtml_Parser_TaskController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author nick
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Stableflow_Company_Task');
    }

    protected function _initTask()
    {
        $taskId  = (int) $this->getRequest()->getParam('id');
        $task    = Mage::getModel('company/parser_task');

        if ($taskId) {
            $task->load($taskId);
        }
        Mage::register('current_task', $task);
        return $task;
    }

    public function taskListAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function companyTaskAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function companyTaskAddAction()
    {
        $this->_initTask();
        $config = Mage::getModel('company/parser_config')->getConfigCollection(1);
        foreach($config as $_config){
            $values[] =  array(
                'value'     => $_config->getId(),
                'label'     => $_config->getData('description'),
            );
        }
        Mage::register('config', $values);
        $this->loadLayout();
        $this->renderLayout();
    }
}