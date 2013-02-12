<?php
/**
 * index.php
 * Input file to the frontend
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

chdir(dirname(__FILE__).'/protected');

require_once('common' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Yii' . DIRECTORY_SEPARATOR . 'yii.php');
$config = require('frontend' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'main.php');
require_once('common' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'WebApplication.php');

$app = Yii::createApplication('WebApplication', $config);

$app->run();