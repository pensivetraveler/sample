<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-05-08 00:13:12 --> Severity: error --> Exception: Cannot use object of type CI_Config as array /usr/local/var/www/sample/application/migrations/20230506212341_add_code.php 41
ERROR - 2023-05-08 00:13:25 --> Severity: error --> Exception: Cannot use object of type CI_Config as array /usr/local/var/www/sample/application/migrations/20230506212341_add_code.php 41
ERROR - 2023-05-08 00:14:06 --> Severity: Notice --> Undefined property: CI_Config::$table_info_list /usr/local/var/www/sample/application/migrations/20230506212341_add_code.php 10
ERROR - 2023-05-08 00:14:06 --> Severity: Warning --> Invalid argument supplied for foreach() /usr/local/var/www/sample/application/migrations/20230506212341_add_code.php 15
ERROR - 2023-05-08 00:14:53 --> Severity: error --> Exception: Cannot use object of type CI_Config as array /usr/local/var/www/sample/application/migrations/20230506212341_add_code.php 10
ERROR - 2023-05-08 00:15:08 --> Severity: error --> Exception: Cannot use object of type CI_Config as array /usr/local/var/www/sample/application/migrations/20230506212341_add_code.php 10
ERROR - 2023-05-08 00:15:40 --> Severity: Warning --> array_key_exists(): The first argument should be either a string or an integer /usr/local/var/www/sample/application/migrations/20230506212341_add_code.php 41
ERROR - 2023-05-08 00:15:40 --> Severity: Warning --> array_key_exists(): The first argument should be either a string or an integer /usr/local/var/www/sample/application/migrations/20230506212341_add_code.php 41
ERROR - 2023-05-08 00:18:07 --> Severity: Warning --> array_key_exists(): The first argument should be either a string or an integer /usr/local/var/www/sample/application/migrations/20230506212341_add_code.php 40
ERROR - 2023-05-08 02:30:44 --> Query error: Invalid default value for 'reg_date' - Invalid query: CREATE TABLE `tb_media` (
	`media_idx` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`media_prop_gnb` VARCHAR(6) NOT NULL,
	`media_prop_idx` INT(5) UNSIGNED NOT NULL,
	`media_cd` VARCHAR(6) NOT NULL,
	`media_link` VARCHAR(2000) NOT NULL,
	`media_srt` INT(5) UNSIGNED NOT NULL,
	`media_file_idx` INT(5) UNSIGNED NOT NULL,
	`reg_user_idx` INT(5) UNSIGNED NOT NULL,
	`reg_date` DATETIME NOT NULL DEFAULT 'CURRENT_TIMESTAMP()',
	CONSTRAINT `pk_tb_media` PRIMARY KEY(`media_idx`)
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci
ERROR - 2023-05-08 03:30:01 --> Query error: Invalid default value for 'reg_date' - Invalid query: CREATE TABLE `tb_media` (
	`media_idx` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`media_prop_gnb` VARCHAR(6) NOT NULL,
	`media_prop_idx` INT(5) UNSIGNED NOT NULL,
	`media_cd` VARCHAR(6) NOT NULL,
	`media_link` VARCHAR(2000) NOT NULL,
	`media_srt` INT(5) UNSIGNED NOT NULL,
	`media_file_idx` INT(5) UNSIGNED NOT NULL,
	`reg_user_idx` INT(5) UNSIGNED NOT NULL,
	`reg_date` DATETIME NOT NULL DEFAULT 'CURRENT_TIMESTAMP()',
	CONSTRAINT `pk_tb_media` PRIMARY KEY(`media_idx`)
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci
ERROR - 2023-05-08 03:30:01 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /usr/local/var/www/sample/application/third_party/PHPExcel/PHPExcel/Calculation.php:3936) /usr/local/var/www/sample/system/core/Common.php 571
ERROR - 2023-05-08 03:34:12 --> Query error: Invalid default value for 'reg_date' - Invalid query: CREATE TABLE `tb_media` (
	`media_idx` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`media_prop_gnb` VARCHAR(6) NOT NULL,
	`media_prop_idx` INT(5) UNSIGNED NOT NULL,
	`media_cd` VARCHAR(6) NOT NULL,
	`media_link` VARCHAR(2000) NOT NULL,
	`media_srt` INT(5) UNSIGNED NOT NULL,
	`media_file_idx` INT(5) UNSIGNED NOT NULL,
	`reg_user_idx` INT(5) UNSIGNED NOT NULL,
	`reg_date` DATETIME NOT NULL DEFAULT 'CURRENT_TIMESTAMP()',
	CONSTRAINT `pk_tb_media` PRIMARY KEY(`media_idx`)
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci
ERROR - 2023-05-08 03:42:44 --> Query error: Invalid default value for 'reg_date' - Invalid query: CREATE TABLE `tb_media` (
	`media_idx` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`media_prop_gnb` VARCHAR(6) NOT NULL,
	`media_prop_idx` INT(5) UNSIGNED NOT NULL,
	`media_cd` VARCHAR(6) NOT NULL,
	`media_link` VARCHAR(2000) NOT NULL,
	`media_srt` INT(5) UNSIGNED NOT NULL,
	`media_file_idx` INT(5) UNSIGNED NOT NULL,
	`reg_user_idx` INT(5) UNSIGNED NOT NULL,
	`reg_date` DATETIME NOT NULL DEFAULT 'CURRENT_TIMESTAMP()',
	CONSTRAINT `pk_tb_media` PRIMARY KEY(`media_idx`)
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci
ERROR - 2023-05-08 03:43:16 --> Query error: Invalid default value for 'reg_date' - Invalid query: CREATE TABLE `tb_media` (
	`media_idx` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`media_prop_gnb` VARCHAR(6) NOT NULL,
	`media_prop_idx` INT(5) UNSIGNED NOT NULL,
	`media_cd` VARCHAR(6) NOT NULL,
	`media_link` VARCHAR(2000) NOT NULL,
	`media_srt` INT(5) UNSIGNED NOT NULL,
	`media_file_idx` INT(5) UNSIGNED NOT NULL,
	`reg_user_idx` INT(5) UNSIGNED NOT NULL,
	`reg_date` DATETIME NOT NULL DEFAULT 'CURRENT_TIMESTAMP()',
	CONSTRAINT `pk_tb_media` PRIMARY KEY(`media_idx`)
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci
ERROR - 2023-05-08 03:44:01 --> Query error: Invalid default value for 'reg_date' - Invalid query: CREATE TABLE `tb_media` (
	`media_idx` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`media_prop_gnb` VARCHAR(6) NOT NULL,
	`media_prop_idx` INT(5) UNSIGNED NOT NULL,
	`media_cd` VARCHAR(6) NOT NULL,
	`media_link` VARCHAR(2000) NOT NULL,
	`media_srt` INT(5) UNSIGNED NOT NULL,
	`media_file_idx` INT(5) UNSIGNED NOT NULL,
	`reg_user_idx` INT(5) UNSIGNED NOT NULL,
	`reg_date` DATETIME NOT NULL DEFAULT 'CURRENT_TIMESTAMP()',
	CONSTRAINT `pk_tb_media` PRIMARY KEY(`media_idx`)
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci
ERROR - 2023-05-08 03:53:43 --> Query error: Invalid default value for 'reg_date' - Invalid query: CREATE TABLE `tb_media` (
	`media_idx` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`media_prop_gnb` VARCHAR(6) NOT NULL,
	`media_prop_idx` INT(5) UNSIGNED NOT NULL,
	`media_cd` VARCHAR(6) NOT NULL,
	`media_link` VARCHAR(2000) NOT NULL,
	`media_srt` INT(5) UNSIGNED NOT NULL,
	`media_file_idx` INT(5) UNSIGNED NOT NULL,
	`reg_user_idx` INT(5) UNSIGNED NOT NULL,
	`reg_date` DATETIME NOT NULL DEFAULT 'CURRENT_TIMESTAMP()',
	CONSTRAINT `pk_tb_media` PRIMARY KEY(`media_idx`)
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci
ERROR - 2023-05-08 03:54:08 --> Severity: Notice --> Undefined index: fields /usr/local/var/www/sample/application/migrations/20230506212341_add_code.php 35
ERROR - 2023-05-08 03:54:57 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ') DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci' at line 2 - Invalid query: CREATE TABLE `tb_media` (
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci
ERROR - 2023-05-08 03:57:26 --> Query error: Invalid default value for 'reg_date' - Invalid query: CREATE TABLE `tb_media` (
	`media_idx` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`media_prop_gnb` VARCHAR(5) NOT NULL,
	`media_prop_idx` INT(5) UNSIGNED NOT NULL,
	`media_cd` VARCHAR(6) NOT NULL,
	`media_link` VARCHAR(2000) NOT NULL,
	`media_srt` INT(3) UNSIGNED NOT NULL DEFAULT 1,
	`media_file_idx` INT(5) UNSIGNED NOT NULL,
	`download_cnt` INT(3) NOT NULL DEFAULT 0,
	`reg_user_idx` INT(5) UNSIGNED NOT NULL,
	`reg_date` DATETIME NOT NULL DEFAULT 'CURRENT_TIMESTAMP',
	CONSTRAINT `pk_tb_media` PRIMARY KEY(`media_idx`)
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci
ERROR - 2023-05-08 03:59:54 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '0,1) NOT NULL DEFAULT 0,
	`image_width` INT(5) NOT NULL,
	`image_height` INT(5) ' at line 12 - Invalid query: CREATE TABLE `tb_file` (
	`file_idx` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`file_name` VARCHAR(1000) NOT NULL,
	`file_type` VARCHAR(50) NOT NULL,
	`file_path` VARCHAR(2000) NOT NULL,
	`full_path` VARCHAR(2000) NOT NULL,
	`raw_name` VARCHAR(1000) NOT NULL,
	`orig_name` VARCHAR(1000) NOT NULL,
	`client_name` VARCHAR(1000) NOT NULL,
	`file_ext` VARCHAR(10) NOT NULL,
	`file_size` VARCHAR(50) NOT NULL,
	`is_image` ENUM(0,1) NOT NULL DEFAULT 0,
	`image_width` INT(5) NOT NULL,
	`image_height` INT(5) NOT NULL,
	`image_type` VARCHAR(50) NOT NULL,
	`image_size_str` VARCHAR(200) NOT NULL,
	CONSTRAINT `pk_tb_file` PRIMARY KEY(`file_idx`)
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci
ERROR - 2023-05-08 04:00:36 --> Query error: Table 'tb_media' already exists - Invalid query: CREATE TABLE `tb_media` (
	`media_idx` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`media_prop_gnb` VARCHAR(5) NOT NULL,
	`media_prop_idx` INT(5) UNSIGNED NOT NULL,
	`media_cd` VARCHAR(6) NOT NULL,
	`media_link` VARCHAR(2000) NOT NULL,
	`media_srt` INT(3) UNSIGNED NOT NULL DEFAULT 1,
	`media_file_idx` INT(5) UNSIGNED NOT NULL,
	`download_cnt` INT(3) NOT NULL DEFAULT 0,
	`reg_user_idx` INT(5) UNSIGNED NOT NULL,
	`reg_date` DATETIME NOT NULL,
	CONSTRAINT `pk_tb_media` PRIMARY KEY(`media_idx`)
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci
ERROR - 2023-05-08 04:00:57 --> Severity: Notice --> Undefined index: fields /usr/local/var/www/sample/application/migrations/20230506212341_add_code.php 35
ERROR - 2023-05-08 04:01:24 --> Query error: Table 'tb_media' already exists - Invalid query: CREATE TABLE `tb_media` (
	`media_idx` INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`media_prop_gnb` VARCHAR(5) NOT NULL,
	`media_prop_idx` INT(5) UNSIGNED NOT NULL,
	`media_cd` VARCHAR(6) NOT NULL,
	`media_link` VARCHAR(2000) NOT NULL,
	`media_srt` INT(3) UNSIGNED NOT NULL DEFAULT 1,
	`media_file_idx` INT(5) UNSIGNED NOT NULL,
	`download_cnt` INT(3) NOT NULL DEFAULT 0,
	`reg_user_idx` INT(5) UNSIGNED NOT NULL,
	`reg_date` DATETIME NOT NULL,
	CONSTRAINT `pk_tb_media` PRIMARY KEY(`media_idx`)
) DEFAULT CHARACTER SET = utf8 COLLATE = utf8_general_ci
