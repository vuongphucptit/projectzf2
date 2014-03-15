<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Users\Model\User;
use Users\Model\UserTable;
class UserManagerController extends AbstractActionController 
{
    public function indexAction()
    {
    	$userTable = $this->getServiceLocator()->get('UserTable');
    	$users = $userTable->fetchAll();
        $viewModel = new ViewModel(array(
        	'users' => $users,
        ));
        return $viewModel;
    }
    
    public function editAction()
    {
    	$userTable = $this->getServiceLocator()->get('UserTable');
    	$user = $userTable->getUser($this->params()->fromRoute('id')); 
    	$form = $this->getServiceLocator()->get('UserEditForm');
    	$form->bind($user);
    	$viewModel  = new ViewModel(array('form' => $form, 'user_id' => $this->params()->fromRoute('id')));
    	return $viewModel;   
    }
    
    public function deleteAction()
    {
    	$id = (int) $this->params()->fromRoute('id');
    	$this->getServiceLocator()->get('UserTable')->deleteUser($id);
    	$this->redirect()->toRoute('users/user-manager');
    }
    
    public function processAction()
    {
        if (!$this->request->isPost()) {
            return $this->redirect()->toRoute('users/user-manager', array('action' => 'edit'));
        }

        $post = $this->request->getPost();
        $userTable = $this->getServiceLocator()->get('UserTable');       
        $user = $userTable->getUser($post->id);
        
		$form = $this->getServiceLocator()->get('UserEditForm');
		$form->bind($user);	
        $form->setData($post);
        
        if (!$form->isValid()) {
            $model = new ViewModel(array(
                'error' => true,
                'form'  => $form,
            ));
            $model->setTemplate('users/user-manager/edit');
            return $model;
        }
		
        // Save user
        $this->getServiceLocator()->get('UserTable')->saveUser($user);
        
        return $this->redirect()->toRoute('users/user-manager');
    }
}