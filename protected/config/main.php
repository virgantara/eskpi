<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'E-SKPI | UNIDA Gontor',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		// 'application.extensions.*'
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'12345',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(

		'user'=>array(
			// enable cookie-based authentication
			'class' => 'WebUser',
			'allowAutoLogin'=>true,
		),

		'excel'=>array(
	      	'class'=>'application.extensions.PHPExcel',
	    ),

	    'mailer' => array(
	       'class' => 'application.extensions.mailer.EMailer',
	       'pathViews' => 'application.views.email',
	       'pathLayouts' => 'application.views.email.layouts'
	    ),

	    'reCaptcha' => [
	        'name' => 'reCaptcha',
	        'class' => 'application.extensions.recaptcha.ReCaptcha',
	        'key' => '6Le7hrUUAAAAAG-KlH_58Ud6vQdDdGErZiu8X8Zl',
	        'secret' => '6Le7hrUUAAAAAI5VlvA78lJjcL9tG4CluPqkHBY2',
	    ],

		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/

		
		'helper' => array(
			'class' => 'application.extensions.helper.MyHelper'
		),
		'rest' => array(
			'class' => 'application.extensions.rest.MyRest',
			'id' => '22821',
			'secretkey' => '8uS2861A18',
			// 'id' => '25946',
			// 'secretkey' => '0kX4792E53',
			'baseurl_apigateway' => 'http://localhost:1926',
		),
		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__).'/database.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>YII_DEBUG ? null : 'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		'emails' => array(
			'oddy' => 'oddy@unida.gontor.ac.id',
			
		),
		// this is used in contact page
		'adminEmail'=>'skpi@unida.gontor.ac.id',
	),
);
