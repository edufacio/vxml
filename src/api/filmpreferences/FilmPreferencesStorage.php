<?php

class FilmPreferencesStorage extends DbStorage{
	const PHONE = 'phone';
	const FAVOURITE_DIRECTORS = 'favourite_directors';
	const FAVOURITE_ACTORS = 'favourite_actors';
	const FAVOURITE_GENRES = 'favourite_genres';
	const DISLIKED_DIRECTORS = 'disliked_directors';
	const DISLIKED_ACTORS = 'disliked_actors';
	const DISLIKED_GENRES = 'disliked_genres';
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
			self::FAVOURITE_DIRECTORS,
			self::FAVOURITE_ACTORS,
			self::FAVOURITE_GENRES,
			self::DISLIKED_ACTORS,
			self::DISLIKED_DIRECTORS,
			self::DISLIKED_GENRES,
			self::CINEMA,
		);
	}

	/**
	 * @return String the class in order to encapsualte a row
	 */
	protected function getStorageObjectClass()
	{
		return 'FilmPreferences';
	}

	/**
	 * @return String
	 */
	protected function getTableName()
	{
		return 'film_preferences';
	}

	/**
	 * @return array with the primary keys
	 */
	protected function getPrimaryKeyColumns()
	{
		return array(self::PHONE);
	}
}