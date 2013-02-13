<?php
/**
 * SiteController.php
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

class SiteController extends Controller
{
    /**
     * @return array list of action filters (See CController::filter)
     */
    public function filters()
    {
        return array('accessControl');
    }

    /**
     * @return array rules for the "accessControl" filter.
     */
    public function accessRules()
    {
        return array(
            array(
                'allow', // Allow registration form for anyone
                'actions' => array('index', 'error'),
            ),
            array(
                'allow', // Allow all actions for logged in users ("@")
                'users' => array('@'),
            ),
            array('deny'), // Deny anything else
        );
    }

    /**
     * Action to render main backend page
     */
    public function actionIndex()
    {
        if (!Yii::app()->user->isGuest) {
            $this->render('index');
        } else {
            $this->redirect(Yii::app()->createUrl('/users'));
        }
    }

    /**
     * Action to render the error
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

    public function actionLogin()
    {
        $this->redirect(array('/users'));
    }

    public function actionLogout()
    {
        $this->redirect(array('/users/sesame/logout'));
    }
}