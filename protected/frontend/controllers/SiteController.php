<?php
/**
 * SiteController.php
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

class SiteController extends Controller
{
    public function accessRules()
    {
        return array(
            // not logged in users should be able to login and view captcha images as well as errors
            array('allow', 'actions' => array('index', 'error')),
            // logged in users can do whatever they want to
            array('allow', 'users' => array('@')),
            // not logged in users can't do anything except above
            array('deny'),
        );
    }

    /* open on startup */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
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
        $this->redirect(array('/users/io/logout'));
    }
}