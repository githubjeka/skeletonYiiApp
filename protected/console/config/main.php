<?php
/**
  * Configuration file for console applications
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

$consoleConfigDir = dirname(__FILE__);

$root = $consoleConfigDir . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..';

// Setup some default path aliases. These alias may vary from projects.
Yii::setPathOfAlias('root', $root);
Yii::setPathOfAlias('common', $root . DIRECTORY_SEPARATOR . 'common');

//Yii::setPathOfAlias('frontend', $root . DIRECTORY_SEPARATOR . 'frontend');
//Yii::setPathOfAlias('backend', $root . DIRECTORY_SEPARATOR . 'backend');

$params = require_once($consoleConfigDir . DIRECTORY_SEPARATOR . 'params-prod.php');

$mainLocalFile = $consoleConfigDir . DIRECTORY_SEPARATOR . 'main-local.php';
$mainLocalConfiguration = file_exists($mainLocalFile) ? require($mainLocalFile) : array();

return CMap::mergeArray(
	array(
		'basePath' => 'console',

		'params' => $params,

		'preload' => array('log'),

		'import' => array(
			'common.components.*',
			'common.extensions.*',
			'common.models.*',
			'application.components.*',
			'application.models.*',
		),

		'commandMap' => array(
			'migrate' => array(
				'class' => 'system.cli.commands.MigrateCommand',
				'migrationPath' => 'root.console.migrations'
			)
		),
		'components' => array(
			'log' => array(
				'class' => 'CLogRouter',
				'routes' => array(
					'main' => array(
						'class' => 'CFileLogRoute',
						'levels' => 'error, warning',
						'filter' => 'CLogFilter'
					)
				)
			),
            'db'=> array(
                'connectionString' => $params['db.connectionString'],
                'username' => $params['db.username'],
                'password' => $params['db.password'],
                'schemaCachingDuration' => YII_DEBUG ? 0 : 86400000, // 1000 days
                'enableParamLogging' => YII_DEBUG,
                'charset' => 'utf8'
            ),
		),
	),
	$mainLocalConfiguration
);

