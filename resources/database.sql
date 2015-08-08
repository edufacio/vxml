DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `phone` INT UNSIGNED NOT NULL,
  `password` INT UNSIGNED,
  `province_id` VARCHAR(32),
  `start_favourite_schedule` TINYINT,
  `end_favourite_schedule` TINYINT,
  `register_status` TINYINT,
  `register_time` INT UNSIGNED,
  PRIMARY KEY (`phone`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` VARCHAR(64),
  `phone` INT UNSIGNED,
  `session_time` INT UNSIGNED,
  `status_session` TINYINT,
  PRIMARY KEY (`session_id`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `callers`;
CREATE TABLE IF NOT EXISTS `callers` (
  `caller_id` INT UNSIGNED NOT NULL,
  `phone` INT UNSIGNED,
  `first_login_time` INT UNSIGNED,
  `last_login_time` INT UNSIGNED,
  `remember_flag` TINYINT,
  PRIMARY KEY (`caller_id`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `film_preferences`;
CREATE TABLE IF NOT EXISTS `film_preferences` (
  `phone` INT UNSIGNED NOT NULL,
  `directors` BLOB NOT NULL,
  `actors` BLOB NOT NULL,
  `genres` BLOB NOT NULL,
  `cinema` BLOB NOT NULL,
  PRIMARY KEY (`phone`)
) DEFAULT CHARSET=utf8;
