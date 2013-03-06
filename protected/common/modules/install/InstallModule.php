<?php
namespace common\modules\Install;

\Yii::setPathOfAlias('Install', dirname(__FILE__));


/**
 * InstallModule
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class InstallModule extends \CWebModule
{
    public $defaultController = 'install';
    public $controllerNamespace = '\install\controllers';

    public static function isInstall()
    {
        if (\Yii::app()->params['install'] === false) {
            \Yii::app()->controller->redirect(\Yii::app()->createUrl('/site/index'));
        }
    }
}