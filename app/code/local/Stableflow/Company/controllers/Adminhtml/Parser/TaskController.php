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
        $this->_getSession()->setCompanyId($companyId);
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new task action
     */
    public function newTaskAction()
    {
        $this->_forward('editTask');
    }

    public function editTaskAction()
    {
        $taskId = (int) $this->getRequest()->getParam('task_id');
        $task = $this->_initTask();
        if ($taskId && !$task->getId()) {
            $this->_getSession()->addError(Mage::helper('company')->__('This Task no longer exists.'));
            $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
            return;
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveTaskAction()
    {
        $model = $this->_initTask();
        $path = $this->_uploadFile();
        $file = $path['file'];
        $data = $this->getRequest()->getPost();
        try{
            if ($model->getCreatedAt == NULL || $model->getUpdatedAt() == NULL) {
                $model->setCreatedAt(now())->setUpdateAt(now());
            } else {
                $model->setUpdatedAt(now());
            }
            $model->setData('config_id', $data['config_id']);
            $model->setData('status_id', $data['status_id']);
            $model->setData('name', $file);
            $model->save();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('company')->__('Task was successfully saved'));
            Mage::getSingleton('adminhtml/session')->setFormData(false);
            $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
        }catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
        }
    }

    public function deleteTaskAction()
    {
        if ($id = $this->getRequest()->getParam('task_id')) {
            $task = Mage::getModel('company/parser_task')->load($id);
            try {
                $task->delete();
                $this->_getSession()->addSuccess(Mage::helper('company')->__('The task has been deleted.'));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()))
        );
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
                $this->_getSession()->addError(Mage::helper('company')->__('Error file upload...'));
                $this->_getSession()->addError($e->getMessage());
            }
        }
    }

    public function addTaskToQueueAction()
    {
        $taskId = $this->getRequest()->getParam('task_id');
        $queue = Mage::getModel('company/parser_queue');
        if($queue->checkInQueue($taskId)){
            $this->_getSession()->addSuccess(Mage::helper('company')->__('The task all ready in Queue.'));
            $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
            return;
        }
        try{
            $queue->setData('task_id', $taskId);
            $queue->setStatus(Stableflow_Company_Model_Parser_Queue_Status::STATUS_IN_PROGRESS);
            $queue->save();
            $task = $this->_initTask();
            $task->setStatus(Stableflow_Company_Model_Parser_Task_Status::STATUS_IN_QUEUE);
            $task->save();
            $this->_getSession()->addSuccess(Mage::helper('company')->__('The task has been added to Queue.'));
            $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
        }catch (Exception $e){
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/company_company/edit', array('id' => $this->_getSession()->getCompanyId()));
        }
    }

    public function taskListAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function runTaskByIdAction()
    {
        $task = $this->_initTask();
        // Check ? remove from queue
        if(Mage::getModel('company/parser')->runTask($task)){
            echo "OK";
            die();
        }else{
            echo "error";
            die();
        }
    }

    public function isAjax()
    {
        if ($this->isXmlHttpRequest()) {
            return true;
        }
        if ($this->getParam('ajax') || $this->getParam('isAjax')) {
            return true;
        }
        return false;
    }
}