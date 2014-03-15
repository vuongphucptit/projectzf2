<?php
namespace Users\Controller;

use Zend\Filter\Null;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Users\Form\LoginForm;
use Users\Form\LoginFilter;
use Users\Model\User;
use Users\Model\UserTable;

class LoginController extends AbstractActionController
{
	protected $storage;
	protected $authService;
	
	public function getAuthService()
	{
		if(!$this->authService) {
			$this->authService = $this->getServiceLocator()->get('AuthService');
		}
		return $this->authService;
	}
	
	public function indexAction()
	{
		/*$form = new LoginForm();
		$viewModel = new ViewModel(array('form' => $form));
		return $viewModel;*/
		
		/*
		 * Using getServiceLocator() built form 
		 */
		$form = $this->getServiceLocator()->get('LoginForm');
		$viewModel  = new ViewModel(array('form' => $form)); 
		return $viewModel; 
	}
	
	public function logoutAction()
	{
		$this->getAuthService()->clearIdentity();
		return $this->redirect()->toRoute(NULL, array('controller' => 'login', 'action' => 'index'));
	}
	
	public function processAction()
	{
		if(!$this->request->isPost()) {
			return $this->redirect()->toRoute('users/login');
		}
		
		$post = $this->request->getPost();
		
		$form = $this->getServiceLocator()->get('LoginForm');
		$form->setData($post);
		
		if(!$form->isValid()) {
			$viewModel = new ViewModel(
				array(
					'form' => $form,
					'error' => true
				)
			);
			$viewModel->setTemplate('users/login/index');
			return $viewModel;
		} else {
			//Check authentication
			$this->getAuthService()->getAdapter()
								   ->setIdentity($this->request->getPost('email'))
								   ->setCredential($this->request->getPost('password'));
			$result = $this->getAuthService()->authenticate();
			if ($result->isValid()) {
				echo 'fuck';
				$this->getAuthService()->getStorage()->write($this->request->getPost('email'));
				return $this->redirect()->toRoute(NULL, array('controller' => 'login', 'action' => 'confirm'));
			} else {
				$viewModel = new ViewModel(
					array(
						'error' => true,
						'form' => $form
					)
				);
				$viewModel->setTemplate('users/login/index');
				return $viewModel;
			}
			
		}
	}
	
	public function confirmAction()
	{
		$user_email = $this->getAuthService()->getStorage()->read();
		$viewModel = new ViewModel(
			array(
				'user_email' => $user_email 
			)
		);
		$this->layout('layout/myaccount');
		return $viewModel;
	}
}