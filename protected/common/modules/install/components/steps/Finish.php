<?php

use \Install\extensions\helpers\AbstractStepBehavior;

/**
 * Environment.php file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

class Finish extends AbstractStepBehavior
{
    public function onStep()
    {
        $this->renderView();
    }

    public function validate() {
        return true;
    }
}
