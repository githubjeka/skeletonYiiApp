<?php
namespace Users\controllers;

use users\models\User;

/**
 * User controller
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class AdminController extends \CController
{
    /**
     * Display users list
     */
    public function actionIndex()
    {
        $model = new User('search');
        $model->unsetAttributes();

        if (!empty($_GET['users\models\User'])) {
            $model->attributes = $_GET['users\models\User'];
        }

        $this->render(
            'list',
            array(
                'model' => $model,
            )
        );
    }

    /**
     * Delete user by Pk
     */
    public function actionDelete()
    {
        if (\Yii::app()->request->isPostRequest) {
            $model = User::model()->findByPk($_GET['id']);

            if ($model && ($model->id != \Yii::app()->user->id)) {
                $model->delete();
            } else {
                return false;
            }

            if (!\Yii::app()->request->isAjaxRequest) {
                $this->redirect('/users');
            }
        }
    }

}
