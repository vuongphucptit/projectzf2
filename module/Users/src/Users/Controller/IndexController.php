<?php
namespace Users\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController 
{
    public function indexAction()
    {
        $view = new ViewModel();
        return $view;
    }
    
    public function editAction()
    {
        $view = new ViewModel();
        $view->setTemplate('users/index/new-user');
        return $view;
    }
    
    public function processAction()
    {
        $view = new ViewModel();
        $view->setTemplate('users/index/login');
        return $view;
    }
    
    public function deleteAction()
    {
        $view = new ViewModel();
        $view->setTemplate('users/index/login');
        return $view;
    }
}