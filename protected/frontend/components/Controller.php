<?php
/**
 * Controller.php
 * Frontend Controller
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class Controller extends CController
{
    /**
     * Breadcrumbs for all backend views pages
     * @var array
     */
    public $breadcrumbs = array();

    /**
     * Menu for all backend views pages
     * @var array
     */
    public $menu = array();

    public function init()
    {
        if (Yii::app()->params['install'] == true) {
            $this->redirect(Yii::app()->getBaseUrl() . '/backend.php?r=install');
        }
        parent::init();
    }

}
