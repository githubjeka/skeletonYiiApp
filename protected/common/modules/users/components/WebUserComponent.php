<?php
/**
 * WebUserComponent.php
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

use users\models\User;

class WebUserComponent extends CWebUser
{
    public $rememberTime = 2622600;
    private $_model;

    /**
     * @return string user email
     */
    public function getEmail()
    {
        $this->_loadUser($this->id);
        return $this->_model->email;
    }

    /**
     * @return string username
     */
    public function getUsername()
    {
        $this->_loadUser($this->id);
        return $this->_model->username;
    }

    /**
     * Load user model
     */
    protected function _loadUser($id = null)
    {
        if ($this->_model === null) {
            if ($id !== null) {
                $this->_model = User::model()->findByPk($id);
            }
        }
        return $this->_model;
    }

    /**
     * @return User
     */
    public function getModel()
    {
        $this->_loadUser($this->id);
        return $this->_model;
    }
}