<?php
/**
 * params-prod.php
 * Parameters for settings config of backend application
 * For you local param need create file "params-local.php" in this folder
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 **/

$paramsLocalFile = dirname(__FILE__) . '/params-local.php';
$paramsLocal = file_exists($paramsLocalFile) ? require($paramsLocalFile) : array();

$paramsCommonFile = Yii::getPathOfAlias('common') . '/config/params-prod.php';

$paramsCommon = file_exists($paramsCommonFile) ? require($paramsCommonFile) : array();

return CMap::mergeArray(
    CMap::mergeArray(
        array(),
        $paramsLocal
    ),
    $paramsCommon
);