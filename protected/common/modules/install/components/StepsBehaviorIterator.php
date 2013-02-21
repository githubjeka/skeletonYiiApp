<?php
namespace Install\components;

use common\modules\Install\InstallModule;

\Yii::import('install.components.steps.*');

/**
 * StepsBehaviorIterator.php file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

class StepsBehaviorIterator extends \CBehavior
{
    protected $currentStep;

    public function __construct()
    {
        $this->getSteps();
        $this->currentStep = $this->getSteps()->current();

        if (array_key_exists('Install\\extensions\\helpers\\AbstractStepBehavior', class_parents($this->currentStep))) {
            $this->attachBehavior('step', $this->currentStep);
            $this->step->onStep();
        }
    }

    public function setCurrentStep($currentStep)
    {
        $this->currentStep = $currentStep;
    }

    public function getCurrentStep()
    {
        return $this->currentStep;
    }

    protected function getSteps()
    {
        $steps = array(
            'Welcome',
            'Environment',
        );
        return new \ArrayIterator($steps);
    }
}