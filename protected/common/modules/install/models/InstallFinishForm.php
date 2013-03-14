<?php
namespace Install\models;
//\\Yii::import('application.modules.store.models.*');
//\\Yii::import('application.modules.core.models.*');
//\\Yii::import('application.modules.users.models.*');

use common\modules\Install\InstallModule;
/**
 * Model to configure admin access
 *
 *  @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class InstallFinishForm extends \CFormModel
{
	public $siteName;
	public $adminLogin;
	public $adminEmail;
	public $adminPassword;

	public function rules()
	{
		return array(
			array('siteName, adminLogin, adminEmail, adminPassword', 'required'),
			array('adminEmail', 'email'),
			array('adminLogin', 'length', 'max'=>255),
			array('adminPassword', 'length', 'min'=>8, 'max'=>40),
		);
	}

	/**
	 * @return bool
	 */
	public function install()
	{
		if($this->hasErrors())
			return false;
        \WebApplication::consoleCommandRun(array('\Yiic', 'migrate', '--interactive=0'));
        return true;
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'siteName'      => \Yii::t('install', 'Название сайта'),
			'adminLogin'    => \Yii::t('install', 'Логин'),
			'adminEmail'    => \Yii::t('install', 'Email'),
			'adminPassword' => \Yii::t('install', 'Пароль'),
		);
	}
}







