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

    public static function wrapperConsoleRun($args = array())
    {
        $pathApp = \Yii::getPathOfAlias('application');
        \Yii::setPathOfAlias('application', \Yii::getPathOfAlias('console'));
        $commandPath = \Yii::getPathOfAlias('application') . '/commands';
        $runner = new \CConsoleCommandRunner();
        $runner->addCommands($commandPath);
        $commandPath = \Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
        $runner->addCommands($commandPath);
        ob_start();
        $runner->run($args);
        \Yii::app()->setBasePath($pathApp);
        return ob_get_clean();
    }

    public static function getPath()
    {
        return dirname(__FILE__);
    }
}