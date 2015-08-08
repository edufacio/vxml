<?php

class CallerStorage extends DbStorage{
	const PHONE = 'phone';
	const CALLER_ID = 'caller_id';
	const FIRST_LOGIN_TIME = 'first_login_time';
	const LAST_LOGIN_TIME = 'last_login_time';
	const REMEMBER = 'remember_flag';

	private static $instance;

	/**
	 * @return CallerStorage
	 */
	public static function getInstance() {
		if (self::$instance === NULL) {
			self::$instance = Injector::get(__CLASS__);
		}
		return self::$instance;
	}
	/**
	 * @return array with the columns of the table
	 */
	protected function getColumnsName()
	{
		return array(
			self::PHONE,
			self::CALLER_ID,
			self::FIRST_LOGIN_TIME,
			self::LAST_LOGIN_TIME,
			self::REMEMBER
		);
	}

	/**
	 * @return String the class in order to encapsualte a row
	 */
	protected function getStorageObjectClass()
	{
		return 'Caller';
	}

	/**
	 * @return String
	 */
	protected function getTableName()
	{
		return 'callers';
	}

	/**
	 * @return array with the primary keys
	 */
	protected function getPrimaryKeyColumns()
	{
		return array(self::CALLER_ID);
	}
}