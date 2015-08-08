<?php

class SessionStorage extends DbStorage{
	const PHONE = 'phone';
	const SESSION_ID = 'session_id';
	const SESSION_STATUS = 'status_session';
	const SESSION_TIME = 'session_time';

	private static $instance;

	/**
	 * @return SessionStorage
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
			self::SESSION_ID,
			self::SESSION_STATUS,
			self::SESSION_TIME,
		);
	}

	/**
	 * @return String the class in order to encapsualte a row
	 */
	protected function getStorageObjectClass()
	{
		return 'Session';
	}

	/**
	 * @return String
	 */
	protected function getTableName()
	{
		return 'sessions';
	}

	/**
	 * @return array with the primary keys
	 */
	protected function getPrimaryKeyColumns()
	{
		return array(self::SESSION_ID);
	}
}