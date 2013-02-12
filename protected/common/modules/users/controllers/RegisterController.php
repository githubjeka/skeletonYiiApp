<?php
/**
 *
 * RegisterController.php file. *
 * Realize user register
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

namespace users\controllers;

use \users\models\User;
use \users\components\UserIdentityComponent;

class RegisterController extends \Controller
{
    public $defaultAction = 'register';

    public function init()
    {
        if (!\Yii::app()->user->isGuest) {
            \Yii::app()->request->redirect('/');
        }
        parent::init();
    }

    /**
     * @return string
     */
    public function allowedActions()
    {
        return 'register';
    }

    /**
     * Creates account for new users
     */
    public function actionRegister()
    {
        $user = new User('register');
        if (isset($_POST['users\models\User'])) {
            $user->attributes = $_POST['users\models\User'];
            if ($user->validate()) {
                $user->password = $user->newPassword;
                $user->save();
                $identity = new UserIdentityComponent($user->username, $_POST['users\models\User']['newPassword']);
                if ($identity->authenticate()) {
                    \Yii::app()->user->login($identity, \Yii::app()->user->rememberTime);
                    $this->redirect(array('/users/profile'));
                }

            }
        }

        $this->render('register', array('user' => $user));
    }

}
