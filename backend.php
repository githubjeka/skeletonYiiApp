<?php
/**
 * index.php
 * Input file to the backend
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

if (PHP_VERSION_ID < 50300): ?>
<h3>
    Для работы нужен PHP 5.3<br/>
    У вас установлен: <?php echo phpversion(); ?>
</h3>
<?php else:

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
endif; ?>