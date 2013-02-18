<?php
/**
 * backend.php
 * Input file to the backend application
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

if (YII_DEBUG) {
    error_reporting(-1);
    ini_set('display_errors', true);
}

date_default_timezone_set('UTC');

chdir(dirname(__FILE__) . '/protected');

$root = dirname(__FILE__) . '/protected';
$common = $root . '/common';

require_once($common . '/lib/Yii/yii.php');
$config = require('backend/config/main.php');
require_once($common . '/components/WebApplication.php');


$app = Yii::createApplication('WebApplication', $config);

$app->run();