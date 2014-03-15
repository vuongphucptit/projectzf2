<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\Index' => 'Users\Controller\IndexController',
            'Users\Controller\Register' => 'Users\Controller\RegisterController',
            'Users\Controller\Login' => 'Users\Controller\LoginController', 
			'Users\Controller\UserManager' => 'Users\Controller\UserManagerController',           
        ),
    ),
    'router' => array(
        'routes' => array(
            'users' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/users',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Users\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
					'login' 	   => array(
                		'type'    => 'segment',
                		'may_terminate' => true,
                		'options' => array(
                			'route'    => '/login[/:action]',
                			'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                        		'controller' => 'Users\Controller\Login',
                        		'action'     => 'index',
                        	), 
                		),
                	),
                	'register'     => array(
                		'type'    => 'segment',
                		'may_terminate' => true,
               			'options' => array(
               				'route'    => '/register[/:action]',
               				'constraints' => array(
               					'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
               				),
               				'defaults' => array(
               					'controller' => 'Users\Controller\Register',
               					'action'     => 'index',
               				),
               			),
                	),
                	'user-manager' => array(
                		'type'    => 'segment',
                		'may_terminate' => true,
                		'options' => array(
                			'route'       => '/user-manager[/][:action][/:id]',
                			'constraints' => array(
                         		'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         		'id'     => '[0-9]+',
                			),
                			'defaults' => array(
                				'controller' => 'Users\Controller\UserManager',
                				'action'     => 'index',
                			),
                		),
                	),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'users' => __DIR__ . '/../view',
        ),
    ),
    'module_config' => array(
    	'upload_location'   		=> __DIR__ . '/../data/uploads',
    	'search_index'				=> __DIR__ . '/../data/search_index',
    	'image_upload_location'		=> __DIR__ . '/../data/images',
    )
);
