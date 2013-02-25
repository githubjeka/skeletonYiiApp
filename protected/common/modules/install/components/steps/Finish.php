<?php

use \Install\extensions\helpers\AbstractStepBehavior;
use common\modules\Install\InstallModule;

/**
 * Environment.php file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

class Finish extends AbstractStepBehavior
{
    public function onStep()
    {
        $this->_header = Yii::t('install', 'Installation complete');
        $this->_nextBtn = false;
        $this->_prevBtn = false;
        ob_start();
        $this->validate();
        ob_get_clean();
        $this->renderView();
    }

    public function validate()
    {
        try {
            InstallModule::wrapperConsoleRun(array('\Yiic', 'migrate', '--interactive=0'));
            $configFile = \Yii::getPathOfAlias('root'). '/common/config/params-prod.php';
            $content = file_get_contents($configFile);
            $content = preg_replace("/\'install\'\s*\=\>\s*.*/", "'install'=>false,", $content);
            file_put_contents($configFile, $content);
            return true;
        } catch (Exception $msg) {
            $this->_errors = array($msg->getMessage());
            return false;
        }
    }
}
