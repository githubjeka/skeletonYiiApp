<?php
namespace Install\extensions\helpers;

/**
 * AbstractStepBehavior.php file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
abstract class AbstractStepBehavior extends \CBehavior
{
    abstract public function onStep();

    public function renderView()
    {
        \Yii::app()->controller->render('welcome');
    }
}