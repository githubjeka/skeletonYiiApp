<?php
/**
 * main.php
 * Configuration settings of frontend application
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 **/

$frontendConfigDir = dirname(__FILE__);

$root = $frontendConfigDir . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..';

// Setup default path aliases.
Yii::setPathOfAlias('root', $root);
Yii::setPathOfAlias('common', $root . DIRECTORY_SEPARATOR . 'common');
Yii::setPathOfAlias('console', $root . DIRECTORY_SEPARATOR . 'console');
Yii::setPathOfAlias('frontend', $root . DIRECTORY_SEPARATOR . 'frontend');
Yii::setPathOfAlias('www', $root . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR . 'www');

$params = require_once($frontendConfigDir . DIRECTORY_SEPARATOR . 'params-prod.php');

$mainLocalFile = $frontendConfigDir . DIRECTORY_SEPARATOR . 'main-local.php';
$mainLocalConfiguration = file_exists($mainLocalFile) ? require ($mainLocalFile) : array();

return CMap::mergeArray(
    array(
        // @see http://www.yiiframework.com/doc/api/1.1/CApplication#basePath-detail
        'basePath' => 'frontend',
        // set parameters
        'params' => $params,
        // preload components required before running applications
        // @see http://www.yiiframework.com/doc/api/1.1/CModule#preload-detail
        'preload' => array('log'),
        // @see http://www.yiiframework.com/doc/api/1.1/CApplication#language-detail
        'language' => 'en',
        'theme' => 'boilerplate',
        // setup import paths aliases
        // @see http://www.yiiframework.com/doc/api/1.1/YiiBase#import-detail
        'import' => array(
            'common.components.*',
            'common.extensions.*',
            'common.models.*',
            'application.components.*',
            'application.controllers.*',
            'application.models.*'
        ),
        // @see http://www.yiiframework.com/doc/api/1.1/CModule#setModules-detail
        'modules' => array(
            'users' => array(
                'class' => '\common\modules\users\UsersModule',
            ),
        ),
        'components' => array(
            'user' => $params['usersComponent'],
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
                'charset' => 'utf8'
            ),
            'themeManager' => array(
                'basePath' => $root . '/frontend/www/themes',
                'baseUrl' => '/protected/frontend/www/themes',
            ),
            'assetManager' => array(
                'basePath' => $root . '/frontend/www/assets',
                'baseUrl' => '/protected/frontend/www/assets',
            ),
        ),
    ),
    $mainLocalConfiguration
);