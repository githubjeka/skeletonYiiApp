<?php
/**
 * userModule
 *
 * Default controller controllers/IOController.php
 *
 *  @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

namespace common\modules\users;

\Yii::setPathOfAlias('users', dirname(__FILE__));

class UsersModule extends \CWebModule
{
    public $defaultController = 'IO';
    public $controllerNamespace = '\users\controllers';

    /**
     * @var string
     */
//	public $moduleName = 'users';

    public function init()
    {
        \Yii::trace('Loaded "users" module.');

        $this->setImport(
            array(
                'users.components.*',
                'users.models.*',
            )
        );

        parent::init();
    }

    /**
     * Init admin-level models, componentes, etc...
     */
    public function initAdmin()
    {
        \Yii::trace('Init users module admin resources.');
        parent::initAdmin();
    }
}