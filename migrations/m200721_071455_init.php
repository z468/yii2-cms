<?php

use yii\db\Migration;

/**
 * Class m200721_071455_init
 */
class m200721_071455_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE TABLE `auth_rule` (`name` VARCHAR(64) NOT NULL, `data` TEXT, `created_at` INT, `updated_at` INT, PRIMARY KEY (`name`)) ENGINE = InnoDB;");
        $this->execute("CREATE TABLE `auth_item` (`name` VARCHAR(64) NOT NULL, `type` INT NOT NULL, `description` TEXT, `rule_name` VARCHAR(64), `data` TEXT, `created_at` INT, `updated_at` INT, PRIMARY KEY (`name`), FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE, INDEX (`type`)) ENGINE = InnoDB;");
        $this->execute("CREATE TABLE `auth_item_child` (`parent` VARCHAR(64) NOT NULL, `child` VARCHAR(64) NOT NULL, PRIMARY KEY (`parent`, `child`), FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE, FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;");
        $this->execute("CREATE TABLE `auth_assignment` (`item_name` VARCHAR(64) NOT NULL, `user_id` VARCHAR(64) NOT NULL, `created_at` INT, PRIMARY KEY (`item_name`, `user_id`), FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;");
        $this->execute("CREATE TABLE `user` (`id` INT NOT NULL AUTO_INCREMENT, `admin` TINYINT(1) DEFAULT 0, `email` VARCHAR(100) DEFAULT NULL, `passwordHash` VARCHAR(60), `passwordChange` TINYINT(1) DEFAULT 0, `active` TINYINT(1) DEFAULT 1, `createDate` DATETIME DEFAULT NULL, `loginDate` DATETIME DEFAULT NULL, `loginIP` VARCHAR(15) DEFAULT NULL, `firstName` VARCHAR(50) DEFAULT NULL, `lastName` VARCHAR(50) DEFAULT NULL, `confirmed` TINYINT(1) DEFAULT 0, `mailing` TINYINT(1) DEFAULT 0, `passwordResetToken` VARCHAR(50) DEFAULT NULL, `confirmToken` VARCHAR(50) DEFAULT NULL, `authKey` VARCHAR(50) DEFAULT NULL, `comment` VARCHAR(200) DEFAULT NULL, `image` VARCHAR(200) DEFAULT NULL, PRIMARY KEY (`id`), UNIQUE KEY (`email`)) ENGINE = InnoDB;");
        $this->execute("CREATE TABLE `user_auth` (`id` INT NOT NULL AUTO_INCREMENT, `user_id` INT NOT NULL, `source` VARCHAR(50), `source_id` VARCHAR(50), PRIMARY KEY (`id`), FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;");
        $this->execute("INSERT INTO `user` SET `admin` = 1, `email` = 'admin', `passwordHash` = '\$2y\$13\$RVM11iDULdUONEe7G2vvH.oUVACQkRUtI.A3w3YeTfEr5fk1/yGHy', `passwordChange` = 1;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE `user_auth`;");
        $this->execute("DROP TABLE `user`;");
        $this->execute("DROP TABLE `auth_assignment`;");
        $this->execute("DROP TABLE `auth_item_child`;");
        $this->execute("DROP TABLE `auth_item`;");
        $this->execute("DROP TABLE `auth_rule`;");
    }
}
