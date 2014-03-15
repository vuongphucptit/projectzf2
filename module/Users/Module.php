<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Users;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Users\Model\User;
use Users\Model\UserTable;

class Module 
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    //Get service config for module
    public function getServiceConfig()
    {
    	return array(
    		'abstract_factories' => array(),
    		'aliases' => array(),
    		'factories' => array(
    			//AUTHSERVICE
    			'AuthService' => function ($sm) {
    				$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    				$dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'user','email','password', 'MD5(?)');
    							
    				$authService = new AuthenticationService();
    				$authService->setAdapter($dbTableAuthAdapter);
    				return $authService;
    			},
    			//DB
    			'UserTable' =>  function($sm) {
    					$tableGateway = $sm->get('UserTableGateway');
    					$table = new UserTable($tableGateway);
    					return $table;
    			},
    			'UserTableGateway' => function ($sm) {
    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    					$resultSetPrototype = new ResultSet();
    					$resultSetPrototype->setArrayObjectPrototype(new User());
    					return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
    			},
    			//FORMS
    			'LoginForm' => function ($sm) {
    				$form = new \Users\Form\LoginForm();
    				$form->setInputFilter($sm->get('LoginFilter'));
    				return $form;
    			},
    			'RegisterForm' => function ($sm) {
    				$form = new \Users\Form\RegisterForm();
    				$form->setInputFilter($sm->get('RegisterFilter'));
    				return $form;
    			},
    			'UserEditForm' => function ($sm) {
    					$form = new \Users\Form\UserEditForm();
    					$form->setInputFilter($sm->get('UserEditFilter'));
    					return $form;
    				},
    			//FILTERS
    			'LoginFilter' => function ($sm) {
    				return new \Users\Form\LoginFilter();
    			},
    			'RegisterFilter' => function ($sm) {
    				return new \Users\Form\RegisterFilter();
    			},
    			'UserEditFilter' => function ($sm) {
    				return new \Users\Form\UserEditFilter();	
    			},
    			
    		),
    		'invokables' => array(),
    		'services' => array(),
    		'shared' => array(),
    	);
    }
}
