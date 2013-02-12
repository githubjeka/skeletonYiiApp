<?php
Yii::setPathOfAlias('users', 'common/modules/users/');
use \users\models\User;

class m121026_121101_install extends CDbMigration
{
    public function up()
    {
        $this->execute(
            'CREATE TABLE IF NOT EXISTS `users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `username` varchar(45) DEFAULT NULL,
                `password` varchar(255) DEFAULT NULL,
                `salt` varchar(255) DEFAULT NULL,
                `password_strategy` varchar(50) DEFAULT NULL,
                `requires_new_password` tinyint(1) DEFAULT NULL,
                `email` varchar(255) DEFAULT NULL,
                `login_attempts` int(11) DEFAULT NULL,
                `login_time` int(11) DEFAULT NULL,
                `login_ip` varchar(32) DEFAULT NULL,
                `validation_key` varchar(255) DEFAULT NULL,
                `create_id` int(11) DEFAULT NULL,
                `create_time` int(11) DEFAULT NULL,
                `update_id` int(11) DEFAULT NULL,
                `update_time` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `username` (`username`),
                UNIQUE KEY `email` (`email`)
		    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8'
        );

        $this->execute(
            'CREATE TABLE IF NOT EXISTS `user_profiles` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `full_name` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
            ALTER TABLE `user_profiles`
            ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;'
        );

        $adminUser = new User('register');
        $adminUser->username = 'admin';
        $adminUser->password = 'admincms';
        $adminUser->newPassword = $adminUser->password;
        $adminUser->confirmPassword = $adminUser->newPassword;

        if ($adminUser->save()) {
            $adminUser->refresh();
            return true;
        }
        var_dump($adminUser->getErrors());
        return false;

//        $adminProfile = new \users\models\UserProfile();
//        $adminProfile->user_id=$adminUser->id;
//        $adminProfile->full_name='Administrator';
//        $adminProfile->save();

    }

    public function down()
    {
        $this->execute('DROP TABLE IF EXISTS `user_profiles`');
        $this->execute('DROP TABLE IF EXISTS `users`');
    }
}