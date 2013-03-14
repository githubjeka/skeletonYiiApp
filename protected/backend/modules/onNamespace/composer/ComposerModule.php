<?php
namespace backend\modules\onNamespace\composer;

/**
 * ComposerModule
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class ComposerModule extends \CWebModule
{
    public $defaultController = 'modules';
    public $controllerNamespace = '\composer\controllers';

    public static function getPath()
    {
        return dirname(__FILE__);
    }
}