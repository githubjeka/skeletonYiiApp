<?php
/**
 * IOController.php
 * Input Output Controller.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
namespace Users\controllers;

use users\models\User;
use users\components\UserIdentityComponent;

class SesameController extends \Controller
{
    public $defaultAction = 'login';

    /**
     * Render Login Form for Guest, else redirect to profile page
     */
    public function actionLogin()
    {
        if (\Yii::app()->user->isGuest) {

            $model = new User('login');

            if (isset($_POST['users\models\User'])) {
                $postFormAttributes = $_POST['users\models\User'];
                $model->attributes = $postFormAttributes;

                if ($model->validate()) {
                    $identityComponent = new UserIdentityComponent($model->username, $model->password);
                    if ($identityComponent->authenticate()) {

                        $duration = $model->rememberUser ? 3600 * 24 * 30 : 0; // 30 days
                        \Yii::app()->user->login($identityComponent, $duration); // \CWebUser->login()
                        $this->redirect(\Yii::app()->user->returnUrl);
                    }

                    $model->addErrors($identityComponent->errorsValidation);
                }

            }

            $this->render('login', array('model' => $model));

        } else {
            $this->redirect(array('/users/profile'));
        }
    }

    /**
     * Logout user if not Guest
     */
    public function actionLogout()
    {
        if (!\Yii::app()->user->isGuest) {
            \Yii::app()->user->logout();
        }
        $this->redirect('/');
    }

}
