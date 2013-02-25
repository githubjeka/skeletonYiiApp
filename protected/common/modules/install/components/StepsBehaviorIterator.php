<?php
namespace Install\components;

use Install\extensions\helpers\AbstractStepBehavior;

\Yii::import('install.components.steps.*');

/**
 * StepsBehaviorIterator.php file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

class  StepsBehaviorIterator extends \CBehavior
{
    /**
     * @var array
     */
    private $_steps = array(
        'Welcome',
        'Environment',
        'DbConnect',
        'Finish',
    );

    /**
     *
     */
    public function run()
    {
        $stepsObject = new \ArrayIterator($this->_steps);

        if (isset(\Yii::app()->session['installStep'])) {
            $sN = (int)\Yii::app()->session['installStep'];
            $stepsObject->seek($sN);

            if (isset($_GET['btn'])) {

                if ($_GET['btn'] === 'prev') {
                    if ($sN > 0) {
                        $stepsObject->seek($sN - 1);
                    }
                }

                if ($_GET['btn'] === 'next') {
                    $step = $this->getStep($stepsObject->current());
                    if ($step->validate()) {
                        if ($sN < ($stepsObject->count() - 1)) {
                            $stepsObject->seek($sN + 1);
                        }
                    }
                }

                \Yii::app()->session['installStep'] = $stepsObject->key();
                \Yii::app()->controller->redirect(array('/install'));
            }
        }

        $step = $this->getStep($stepsObject->current());
        $step->onStep();
        \Yii::app()->session['installStep'] = $stepsObject->key();
    }

    /**
     * @param $step string
     * @return AbstractStepBehavior
     * @throws \Exception
     */
    private function getStep($step)
    {
        if (class_exists($step)) {
            $requiredClass = 'Install\extensions\helpers\AbstractStepBehavior';
            if (array_key_exists($requiredClass, class_parents($step))) {
                return $this->attachBehavior('step', $step);
            } else {
                throw new \Exception("Class {$step} should be extend AbstractStepBehavior");
            }
        } else {
            throw new \Exception("Class {$step} not found");
        }
    }


}