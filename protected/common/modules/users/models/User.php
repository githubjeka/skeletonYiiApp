<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $username
 * @property string $login_ip
 * @property integer $login_attempts
 * @property integer $login_time
 * @property string $validation_key
 * @property string $password_strategy
 * @property boolean $requires_new_password
 * @property integer $create_id
 * @property integer $create_time
 * @property integer $update_id
 * @property integer $update_time
 * @property integer $delete_id
 * @property integer $delete_time
 * @property integer $status
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

namespace users\models;

use users\components\UserIdentityComponent;

class User extends \CActiveRecord
{
    public $newPassword;
    public $confirmPassword;
    public $rememberUser = false;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return \CActiveRecord the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'users';
    }

    /**
     * Behaviors
     * Validate username.
     * Validate rules for password.
     * show APasswordStrategy property
     * @return array
     */
    public function behaviors()
    {
        $pathToBehaviors = 'users.extensions.behaviors.';
        \Yii::import($pathToBehaviors . 'password.*');
        \Yii::import($pathToBehaviors . 'username.*');
        return array(
            'UsernameBehavior' => array(
                'class' => 'UsernameBehavior',
            ),
            // Password behavior strategy
            'APasswordBehavior' => array(
                'class' => 'APasswordBehavior',
                'defaultStrategyName' => 'bcrypt',
                'strategies' => array(
                    'bcrypt' => array(
                        'class' => 'ABcryptPasswordStrategy',
                        'minLength' => 8,
                    ),
                ),
            )
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $passStrategy = get_class($this->getStrategy());

        return array(
            array('username', 'LoginValidator', 'on' => 'login, register'),
            // login scenario
            array('password', 'safe', 'on' => 'login'),
            array('rememberUser', 'boolean', 'on' => 'login'),
            // register and changePassword scenario
            array('newPassword, confirmPassword', 'required', 'on' => 'changePassword, register'),
            array('newPassword', $passStrategy, 'minLength' => $this->getStrategy()->minLength, 'on' => 'changePassword, register'),
            array('newPassword', 'compare', 'compareAttribute' => 'confirmPassword', 'on' => 'changePassword, register'),
            // search scenario
            array('id, username, create_time, login_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => \Yii::t('users', 'Username'),
            'password' => \Yii::t('users', 'Password'),
            'create_time' => \Yii::t('users', 'Created Time'),
            'login_time' => \Yii::t('users', 'Login Time'),
            'rememberUser' => \Yii::t('users', 'Remember me next time'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return \CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new \CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('login_time', $this->create_time, true);

        return new \CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }
}