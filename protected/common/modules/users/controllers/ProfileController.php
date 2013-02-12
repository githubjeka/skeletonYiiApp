<?php
/**
 * Profile, order and other user data. *
 */

namespace users\controllers;

use \users\models\User;

class ProfileController extends \Controller
{
    /**
     * Check if user is authenticated
     * @param \CAction $action
     * @return bool
     */
    protected function beforeAction($action)
    {
        if (\Yii::app()->user->isGuest) {
            $this->redirect(\Yii::app()->createUrl('/users'));
        }
        return true;
    }

    /**
     * Display profile start page
     */
    public function actionIndex()
    {
        $user = new User('changePassword');

        if ($user->attributes = \Yii::app()->request->getPost('users\models\User')) {

            if ($user->validate()) {

                $currentUser=\Yii::app()->user->getModel();
                if ($currentUser->verifyPassword($_POST['users\models\User']['password']) === true) {
                    if ($currentUser->changePassword($user->newPassword)) {
                        $this->addFlashMessage(\Yii::t('users', 'Password changed successfully.'));
                        $this->refresh();
                    }
                }
                $user->addErrors($currentUser->getErrors());
            }
        }

        $this->render(
            'index',
            array(
                'user' => $user,
            )
        );
    }
}
