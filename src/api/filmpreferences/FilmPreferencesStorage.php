<?php

class FilmPreferencesStorage extends DbStorage{
	const PHONE = 'phone';
	const DIRECTORS = 'directors';
	const ACTORS = 'actors';
	const GENRES = 'genres';
	const CINEMA = 'cinema';

	private static $instance;

	/**
	 * @return FilmPreferencesStorage
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
			self::DIRECTORS,
			self::ACTORS,
			self::GENRES,
			self::CINEMA,
		);
	}

	/**
	 * @return String the class in order to encapsualte a row
	 */
	protected function getStorageObjectClass()
	{
		return 'FavouriteDetails';
	}

	/**
	 * @return String
	 */
	protected function getTableName()
	{
		return 'favourite_details';
	}

	/**
	 * @return array with the primary keys
	 */
	protected function getPrimaryKeyColumns()
	{
		return array(self::PHONE);
	}
}