<?php
/**
 * UserIdentity.php
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
namespace users\components;

use users\models\User;

class UserIdentityComponent extends \CUserIdentity
{
    /**
     * @var integer id of logged user
     */
    private $_id;
    public $errorsValidation = array();

    /**
     * Authenticates username and password
     * @return boolean CUserIdentity::ERROR_NONE if successful authentication
     */
    public function authenticate()
    {
        $user = User::model()->findByAttributes(array('username' => $this->username));

        if ($user->verifyPassword($this->password)) {
            $this->_id = $user->id;
            return true;
        }

        $this->errorsValidation = $user->getErrors();
        return false;
    }

    /**
     * @return integer id of the logged user, null if not set
     */
    public function getId()
    {
        return $this->_id;
    }
}