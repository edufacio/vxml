<?php

class FilmCacheStorage extends DbStorage{
	const FILM_ID = 'film_id';
	const CONTENT = 'content';
	const EXPIRATION = 'expiration';
	private static $instance;

	/**
	 * @return FilmCacheStorage
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
			self::FILM_ID,
			self::CONTENT,
			self::EXPIRATION,
		);
	}

	/**
	 * @return String the class in order to encapsualte a row
	 */
	protected function getStorageObjectClass()
	{
		return 'FilmCache';
	}

	/**
	 * @return String
	 */
	protected function getTableName()
	{
		return 'film_cache';
	}

	/**
	 * @return array with the primary keys
	 */
	protected function getPrimaryKeyColumns()
	{
		return array(self::FILM_ID);
	}

	/**
	 * @param $filmId
	 *
	 * @return FilmCache
	 */
	public function getCache($filmId) {
		return $this->find(array(self::FILM_ID => $filmId), FilmCache::create()->setFilmId($filmId));
	}
}