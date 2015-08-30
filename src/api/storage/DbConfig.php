<?php
/**
 * Created by JetBrains PhpStorm.
 * User: econtreras
 * Date: 7/29/15
 * Time: 11:48 PM
 * To change this template use File | Settings | File Templates.
 */

class DbConfig {
	const HOST = 'host';
	const USER = 'user';
	const PASSWD = 'passwd';
	const DB_NAME = 'dbName';

	/**
	 * @return DbConfig
	 */
	public static function create()
	{
		return Injector::get('DbConfig');
	}

	public function getDbConfig() {
		return array(
			self::HOST => 'localhost',
			//self::HOST => '127.0.0.1',
			self::USER => '1012833',
			self::DB_NAME => '1012833',
			self::PASSWD => 'edufraboPfc6',
		);
	}
}