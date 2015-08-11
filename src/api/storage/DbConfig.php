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
			self::HOST => 'mysql7.000webhost.com',
			//self::HOST => '127.0.0.1',
			self::USER => 'a6081555_user',
			self::DB_NAME => 'a6081555_db',
			self::PASSWD => 'vxmlPFC6',
		);
	}
}