<?php
/**
 * params-prod.php
 * Parameters for settings config of backend application
 * For you local param need create file "params-local.php" in this folder
 *
 * $rootBackend - Name prefix url, by which the backend application is available.
 * Note: .htaccess
 * <pre>
 *   RewriteEngine On *
 *   RewriteRule ^backend backend.php [L]
 * <pre>
 * @var $rootBackend string
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 **/

$paramsLocalFile = dirname(__FILE__) . '/params-local.php';
$paramsLocal = file_exists($paramsLocalFile) ? require($paramsLocalFile) : array();

$paramsCommonFile = Yii::getPathOfAlias('common') . '/config/params-prod.php';

$paramsCommon = file_exists($paramsCommonFile) ? require($paramsCommonFile) : array();

$rootBackend = 'backend';

return CMap::mergeArray(
    CMap::mergeArray(
        array(
            'url.rules' => array(
                $rootBackend => '',
                $rootBackend . '/<_c>' => '<_c>',
                $rootBackend . '/<_c>/<_a>' => '<_c>/<_a>',
                $rootBackend . '/<_m>/<_c>/<_a>' => '<_m>/<_c>/<_a>',
            ),
        ),
        $paramsLocal
    ),
    $paramsCommon
);