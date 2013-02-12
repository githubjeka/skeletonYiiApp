<?php
/**
 * main.php
 * Configuration settings of backend application
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 **/

$backendConfigDir = dirname(__FILE__);

$root = $backendConfigDir . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..';

// Setup default path aliases.
Yii::setPathOfAlias('root', $root);
Yii::setPathOfAlias('common', $root . DIRECTORY_SEPARATOR . 'common');
Yii::setPathOfAlias('console', $root . DIRECTORY_SEPARATOR . 'console');
Yii::setPathOfAlias('backend', $root . DIRECTORY_SEPARATOR . 'backend');
Yii::setPathOfAlias('www', $root . DIRECTORY_SEPARATOR . 'backend' . DIRECTORY_SEPARATOR . 'www');
Yii::setPathOfAlias('modules', dirname(__FILE__) . '/../modules/offNamespace/');

$params = require_once($backendConfigDir . DIRECTORY_SEPARATOR . 'params-prod.php');

$mainLocalFile = $backendConfigDir . DIRECTORY_SEPARATOR . 'main-local.php';
$mainLocalConfiguration = file_exists($mainLocalFile) ? require ($mainLocalFile) : array();

return CMap::mergeArray(
    array(
        'name' => 'Skeleton Airily',
        // @see http://www.yiiframework.com/doc/api/1.1/CApplication#basePath-detail
        'basePath' => 'backend',
        'modulePath' => Yii::getPathOfAlias('modules'),
        'params' => $params,
        // preload components required before running applications
        // @see http://www.yiiframework.com/doc/api/1.1/CModule#preload-detail
        'preload' => array('log'),
        // @see http://www.yiiframework.com/doc/api/1.1/CApplication#language-detail
        'language' => 'en',

        'theme' => 'default',

        // @see http://www.yiiframework.com/doc/api/1.1/YiiBase#import-detail
        'import' => array(
            'common.components.*',
            'common.extensions.*',
            'common.models.*',
            'application.components.*',
            'application.controllers.*',
            'application.models.*',
        ),
        // @see http://www.yiiframework.com/doc/api/1.1/CModule#setModules-detail
        'modules' => array(
            'install' => array(
                'class' => '\common\modules\install\InstallModule',
            ),
            'users' => array(
                'class' => '\common\modules\users\UsersModule',
            ),
            /*
            'gii' => array(
                'class' => 'system.gii.GiiModule',
                'password'=>'1',
                'generatorPaths' => array(
                    'bootstrap.gii'
                )
            )
            */
        ),
        'components' => array(
            'user' => $params['usersComponent'],
            'themeManager' => array(
                'basePath' => $root . '/backend/www/themes',
                'baseUrl' => '/protected/backend/www/themes',
            ),
            'assetManager' => array(
                'basePath' => $root . '/backend/www/assets',
                'baseUrl' => '/protected/backend/www/assets',
            ),
            'errorHandler' => array(
                // @see http://www.yiiframework.com/doc/api/1.1/CErrorHandler#errorAction-detail
                'errorAction' => 'site/error'
            ),
            'db' => array(
                'connectionString' => $params['db.connectionString'],
                'username' => $params['db.username'],
                'password' => $params['db.password'],
                'schemaCachingDuration' => YII_DEBUG ? 0 : 86400000, // 1000 days
                'enableParamLogging' => YII_DEBUG,
                'enableProfiling' => YII_DEBUG,
                'charset' => 'utf8',
            ),
            'log' => array(
                'class' => 'CLogRouter',
                'routes' => array(
                    array(
                        'class' => 'CProfileLogRoute',
                        'report' => 'summary',
                    ),
                ),
            ),
        ),
    ),
    $mainLocalConfiguration
);