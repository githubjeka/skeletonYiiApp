<?php
namespace Install\controllers;

use Install\components\StepsBehaviorIterator;

/**
 * InstallController file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

class InstallController extends \CController
{
    public $defaultAction = 'step';
    public $layout = 'install.views.layout.install';

    public function init() {
        if (\Yii::app()->params['install']==false) {
            $this->redirect($this->createAbsoluteUrl('/site'));
        }
        parent::init();
    }

    public function actionStep()
    {
        $this->attachBehavior('iteratorStep', new StepsBehaviorIterator());
        $this->iteratorStep->run();
    }
}
