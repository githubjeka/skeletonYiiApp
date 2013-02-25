<?php
namespace Install\models;

/**
 * InstallConfigureForm file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
class InstallConfigureForm extends \CFormModel
{
    const CONFIG_LOCAL = 'local';
    const CONFIG_PROD = 'production';

    public $specConfig = self::CONFIG_PROD;
    public $installDemoData = false;
    public $dbHost = 'localhost';
    public $dbName = 'airily';
    public $dbUserName;
    public $dbPassword;

    public function rules()
    {
        return array(
            array('dbHost, dbName, dbUserName', 'required'),
            array('dbPassword', 'checkDbConnection'),
        );
    }

    public function checkDbConnection()
    {
        if (!$this->hasErrors()) {
            try {
                $connection = new \PDO("mysql:host={$this->dbHost}", $this->dbUserName, $this->dbPassword);
                $connection->exec("CREATE DATABASE `$this->dbName` character set utf8 COLLATE utf8_general_ci;");
                $connection = new \CDbConnection($this->getDsn(), $this->dbUserName, $this->dbPassword);
                $connection->connectionStatus;
                $connection = null;
            } catch (\PDOException $e) {
                $this->addError('dbPassword', \Yii::t('install', 'Ошибка подключения к БД ' . $e->getMessage()));
            }
            catch (\CDbException $e) {
                $this->addError('dbPassword', $e->getMessage());
            }
        }
    }

    /**
     * @return string DSN connection
     */
    public function getDsn()
    {
        return strtr(
            'mysql:host={host};dbname={db_name}',
            array(
                '{host}' => $this->dbHost,
                '{db_name}' => $this->dbName,
            )
        );
    }

    /**
     * @return bool
     */
    public function install()
    {
        if ($this->hasErrors()) {
            return false;
        }

        $root = \Yii::getPathOfAlias('root');
        $directories = \SplFixedArray::fromArray(
            array(
                $root . '/common/config/params-prod.php',
            )
        );
        foreach ($directories as $configFile) {
            $this->writeConnectionSettings($configFile);
        }

        return true;
    }

    /**
     * Write connection settings to the main.php
     */
    private function writeConnectionSettings($configFile)
    {
        $content = file_get_contents($configFile);
        $content = preg_replace(
            "/\'db.connectionString\'\s*\=\>\s*\'.*\'/",
            "'db.connectionString'=>'{$this->getDsn()}'",
            $content
        );
        $content = preg_replace("/\'db.username\'\s*\=\>\s*\'.*\'/", "'db.username'=>'{$this->dbUserName}'", $content);
        $content = preg_replace("/\'db.password\'\s*\=\>\s*\'.*\'/", "'db.password'=>'{$this->dbPassword}'", $content);
        $content = preg_replace("/\'db.name\'\s*\=\>\s*\'.*\'/", "'db.name'=>'{$this->dbName}'", $content);
        file_put_contents($configFile, $content);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'siteName' => \Yii::t('install', 'Site name'),
            'installDemoData' => \Yii::t('install', 'Install demo data'),
            'dbHost' => \Yii::t('install', 'Host'),
            'dbName' => \Yii::t('install', 'DB name'),
            'dbUserName' => \Yii::t('install', 'User name'),
            'dbPassword' => \Yii::t('install', 'Password'),
        );
    }


}
