<?php
/**
 * Controller.php
 * Backend Controller
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

    /**
     * Initialisation Controller
     * Check install app and redirect to install page, if true.
     */
    public function init()
    {
        if (Yii::app()->params['install'] == true) {
            $this->redirect(Yii::app()->getBaseUrl() . '/backend.php?r=install');
        }
        parent::init();
    }

    /**
     * Set Flash Message
     * @param $message
     */
    public function addFlashMessage($message)
    {
        $currentMessages = Yii::app()->user->getFlash('messages');

        if (!is_array($currentMessages)) {
            $currentMessages = array();
        }

        Yii::app()->user->setFlash('messages', CMap::mergeArray($currentMessages, array($message)));
    }
}