<?php
/**
 * params-prod.php
 * Parameters for settings config of backend application
 * For you local param need create file "params-local.php" in this folder
 *
 * $rootFrontend - Name prefix url, by which the frontend application is available.
 * Note: .htaccess
 * <pre>
 *   RewriteEngine On *
 *   RewriteRule ^site index.php [L]
 * <pre>
 * @var $rootFrontend string
 * 
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 **/

$paramsLocalFile = dirname(__FILE__) . '/params-local.php';
$paramsLocal = file_exists($paramsLocalFile) ? require($paramsLocalFile) : array();

$paramsCommonFile = Yii::getPathOfAlias('common') . '/config/params-prod.php';

$paramsCommon = file_exists($paramsCommonFile) ? require($paramsCommonFile) : array();

$rootFrontend='site';

return CMap::mergeArray(
    CMap::mergeArray(
        array(
            'url.rules' => array(
                $rootFrontend => '',
                $rootFrontend . '/<_c>' => '<_c>',
                $rootFrontend . '/<_c>/<_a>' => '<_c>/<_a>',
                $rootFrontend . '/<_m>/<_c>/<_a>' => '<_m>/<_c>/<_a>',
            ),
        ),
        $paramsLocal
    ),
    $paramsCommon
);