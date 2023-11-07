USE calendar;

CREATE TABLE IF NOT EXISTS `user` (`id` INT(255) NOT NULL AUTO_INCREMENT UNIQUE, `user_id` CHAR(10) NOT NULL UNIQUE, `password` CHAR(100) NOT NULL, `session_id` CHAR(30), `session_expire` INT(255));
CREATE TABLE IF NOT EXISTS `group` (`id` INT(255) NOT NULL AUTO_INCREMENT UNIQUE, `group_name` CHAR(30) NOT NULL);
CREATE TABLE IF NOT EXISTS `color` (`id` INT(255) NOT NULL AUTO_INCREMENT UNIQUE, `color_name` CHAR(30) NOT NULL);

CREATE TABLE IF NOT EXISTS `user_detail` (`id` INT(255) NOT NULL AUTO_INCREMENT UNIQUE, `name` CHAR(20) NOT NULL, `user_id` INT(255) NOT NULL, `color_id` INT(255) NOT NULL DEFAULT 1, `group_id` INT(255), FOREIGN KEY (`user_id`) REFERENCES `user`(`id`), FOREIGN KEY (`color_id`) REFERENCES `color`(`id`), FOREIGN KEY (`group_id`) REFERENCES `group`(`id`));

CREATE TABLE IF NOT EXISTS `schedule` (`id` INT(255) NOT NULL AUTO_INCREMENT UNIQUE, `user_id` INT(255) NOT NULL, `date` CHAR(20) NOT NULL, `delete_flag` TINYINT(1) NOT NULL DEFAULT 0,FOREIGN KEY (`user_id`) REFERENCES `user`(`id`));
CREATE TABLE IF NOT EXISTS `schedule_detail` (`id` INT(255) NOT NULL AUTO_INCREMENT UNIQUE, `schedule_id` INT(255), `title` CHAR(30), `detail` CHAR(100), FOREIGN KEY (`schedule_id`) REFERENCES `schedule`(`id`));
