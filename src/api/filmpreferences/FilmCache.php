<?php
class FilmCache extends StorageObject
{
	/**
	 * @param $rawRecord
	 *
	 * @return FilmCache
	 */
	public static function createFromRecord($rawRecord) {
		return Injector::get('FilmCache', $rawRecord);
	}

	/**
	 * @return FilmCache
	 */
	public static function create() {
		return Injector::get('FilmCache', array());
	}
	public function setFilmId($filmId)
	{
		return $this->set(FilmCacheStorage::FILM_ID, $filmId);
	}

	public function getFilmId()
	{
		return $this->get(FilmCacheStorage::FILM_ID);
	}

	public function hasFilmId() {
		return $this->exist(FilmCacheStorage::FILM_ID);
	}

	public function setContent(array $content)
	{
	    return $this->set(FilmCacheStorage::CONTENT, json_encode($content));
	}

	public function getContent()
	{
		if ($this->hasContent()) {
			return json_decode($this->get(FilmCacheStorage::CONTENT), true);
		}
		return array();
	}

	public function hasContent() {
		return $this->exist(FilmCacheStorage::CONTENT);
	}

	public function setExpiration($expiration)
	{
		return $this->set(FilmCacheStorage::EXPIRATION, $expiration);
	}

	public function getExpiration()
	{
		return $this->get(FilmCacheStorage::EXPIRATION);
	}

	public function hasExpiration() {
		return $this->exist(FilmCacheStorage::EXPIRATION);
	}

	public function isExpired() {
		return !$this->hasExpiration() || $this->getExpiration() < time();
	}
}