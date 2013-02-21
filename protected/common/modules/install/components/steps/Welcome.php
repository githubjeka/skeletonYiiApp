<?php

use common\modules\Install\InstallModule;
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
        $this->renderView();
    }
}
