CREATE SCHEMA `veresz_canvas` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE `veresz_canvas`.`digits` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `target_digit` INT UNSIGNED NOT NULL,
  `predicted_digit` INT UNSIGNED NOT NULL,
  `confidence` FLOAT UNSIGNED NOT NULL,
  `submitted_at` DATETIME NOT NULL,
  `is_active` TINYINT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
PRIMARY KEY (`id`));

GRANT ALL PRIVILEGES ON `veresz_canvas`.* TO 'veresz_user';