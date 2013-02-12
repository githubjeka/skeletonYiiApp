<?php
/**
 * ConfigController file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

namespace Install\controllers;

use Install\models\ConfigForm;

class ConfigController extends \CController
{
    public $layout = 'install.views.layout.layout2col';

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
                'allow',
                'actions' => array('index', 'login', 'logout', 'contact', 'captcha', 'error', 'test'),
                'users' => array('@'),
            ),
            array('deny'),
        );
    }

    public function actionIndex()
    {
        $this->render('index');
    }

}
