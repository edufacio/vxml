<?php

class UserStorage extends DbStorage{
	const PHONE = 'phone';
	const PASSWORD = 'password';
	const PROVINCE_ID = 'province_id';
	const START_SCHEDULE = 'start_favourite_schedule';
	const END_SCHEDULE = 'end_favourite_schedule';
	const REGISTER_STATUS = 'register_status';
	const REGISTER_TIME = 'register_time';

	private static $instance;

	/**
	 * @return UserStorage
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
			self::PASSWORD,
			self::PROVINCE_ID,
			self::START_SCHEDULE,
			self::END_SCHEDULE,
			self::REGISTER_STATUS,
			self::REGISTER_TIME,
		);
	}

	/**
	 * @return String the class in order to encapsualte a row
	 */
	protected function getStorageObjectClass()
	{
		return 'User';
	}

	/**
	 * @return String
	 */
	protected function getTableName()
	{
		return 'users';
	}

	/**
	 * @return array with the primary keys
	 */
	protected function getPrimaryKeyColumns()
	{
		return array(self::PHONE);
	}
}