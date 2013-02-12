<?php
/**
 * params-prod.php
 * Application settings for the public repository
 * To change the local settings, create a file params-local.php in the same folder
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

$commonConfigDir = dirname(__FILE__);
$commonParamsLocalFile = $commonConfigDir . DIRECTORY_SEPARATOR . 'params-local.php';

$localCommonParams = file_exists($commonParamsLocalFile) ? require ($commonParamsLocalFile) : array();

return CMap::mergeArray(
    array(
        'install' => true,
        'db.name' => 'Skeleton Airily',
        'db.connectionString' => 'mysql:host=localhost;dbname=airily',
        'db.username' => 'root',
        'db.password' => '',
        // cache settings -if APC is not loaded, then use CDbCache
        'cache.core' => extension_loaded('apc') ?
            array(
                'class' => 'CApcCache',
            ) :
            array(
                'class' => 'CDbCache',
                'connectionID' => 'db',
                'autoCreateCacheTable' => true,
                'cacheTableName' => 'cache',
            ),
        'cache.content' => array(
            'class' => 'CDbCache',
            'connectionID' => 'db',
            'autoCreateCacheTable' => true,
            'cacheTableName' => 'cache',
        ),
        'usersComponent' => array(
            'loginUrl' => array('/users'),
            'allowAutoLogin' => true,
            'class' => 'users.components.WebUserComponent',
        ),
        'url.rules' => array(
            '<controller:\w+>/<id:\d+>' => '<controller>/view',
            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        ),
    ),
    $localCommonParams
);