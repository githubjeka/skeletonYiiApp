<?php

use \Install\extensions\helpers\AbstractStepBehavior;

/**
 * Environment.php file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

class DbConnect extends AbstractStepBehavior
{
    private $_m;

    public function __construct()
    {
        $this->_m = new \Install\models\InstallConfigureForm();
    }

    public function onStep()
    {
        $this->_header = Yii::t('install', 'DB connection');
        $this->_nextBtn=false;
        if (isset($_POST['connect']) && isset($_POST['Install\models\InstallConfigureForm'])) {
            $this->_m->attributes = $_POST['Install\models\InstallConfigureForm'];
            if ($this->_m->validate()) {
                if ($this->_m->install()) {
                    $this->_nextBtn=true;
                }
            }
        }

        $this->renderView(array('model' => $this->_m,'valid'=>$this->_nextBtn));
    }

    public function validate()
    {
        return true;
    }
}
