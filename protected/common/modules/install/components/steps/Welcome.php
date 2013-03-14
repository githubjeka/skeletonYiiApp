<?php

use \Install\extensions\helpers\AbstractStepBehavior;

/**
 * Environment.php file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

class Welcome extends AbstractStepBehavior
{
    public function onStep()
    {
        $this->_prevBtn = false;
        $this->_header = \Yii::t('install', 'Welcome to ') . \Yii::app()->name;
        $this->renderView();
    }

    public function validate()
    {
        return true;
    }
}
