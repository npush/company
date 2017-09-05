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
     * @see Mage_Core_Controller_Varien_Action::_construct()
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Stableflow_Company_Task');
    }

    protected function _initTask()
    {
        $taskId  = (int) $this->getRequest()->getParam('task_id');
        $task = Mage::getModel('company/parser_task');

        if ($taskId) {
            $task->load($taskId);
        }
        Mage::register('current_task', $task);
        return $task;
    }


    /**
     * View List of all task`s
     */
    public function taskAction()
    {
        $companyId = $this->getRequest()->getParam('id');
        Mage::register('current_company', $companyId);
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new task action
     *
     * @return void
     */
    public function newTaskAction()
    {
        $this->_forward('editTask');
    }

    public function editTaskAction()
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

    public function saveTaskAction()
    {
        $model = $this->_initTask();
        $path = $this->_uploadFile();
        $file = $path['file'];
        try{
            if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                $model->setCreatedTime(now())->setUpdateTime(now());
            } else {
                $model->setUpdateTime(now());
            }
            $model->setData('config_id', 1);
            $model->setData('name', $file);
            $model->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('company')->__('Task was successfully saved'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            $this->_redirect('*/company_company/edit', array('id' => $this->getRequest()->getParam('id')));
        }catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/company_company/edit', array('id' => $this->getRequest()->getParam('id')));
        }
    }

    public function deleteTaskAction()
    {

    }

    public function massDeleteTaskAction()
    {

    }

    public function massStatusTaskAction()
    {

    }

    protected function _uploadFile()
    {
        if(isset($_FILES['name']['name']) && $_FILES['name']['name'] != '') {
            try {
                /* Starting upload */
                $uploader = new Stableflow_Company_Model_Parser_Uploader('name');
                $uploader->init();
                $path = $uploader->save($_FILES['name']['name'] );
                return $path;
            } catch (Exception $e) {

            }
        }
    }


    public function taskListAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}