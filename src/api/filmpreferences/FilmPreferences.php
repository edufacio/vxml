<?php
class FilmPreferences extends StorageObject
{
	/**
	 * @param $rawRecord
	 *
	 * @return FilmPreferences
	 */
	public static function createFromRecord($rawRecord) {
		return Injector::get('FilmPreferences', $rawRecord);
	}

	/**
	 * @return FilmPreferences
	 */
	public static function create() {
		return Injector::get('FilmPreferences', array());
	}
	public function setPhone($phone)
	{
		return $this->set(FilmPreferencesStorage::PHONE, $phone);
	}

	public function getPhone()
	{
		return $this->get(FilmPreferencesStorage::PHONE);
	}

	public function hasPhone() {
		return $this->exist(FilmPreferencesStorage::PHONE);
	}

	public function setDirectors(array $directors)
	{
		return $this->set(FilmPreferencesStorage::DIRECTORS, json_encode($directors));
	}

	public function getDirectors()
	{
		if ($this->hasDirectors()) {
			return json_decode($this->get(FilmPreferencesStorage::DIRECTORS));
		}
		return array();
	}

	public function addDirector($director)
	{
		$directors = $this->getDirectors();
		$directors[] = $director;
		return $this->setDirectors(array_unique($directors));
	}

	public function hasDirectors() {
		return $this->exist(FilmPreferencesStorage::DIRECTORS);
	}

	public function setActors(array $actors)
	{
		return $this->set(FilmPreferencesStorage::ACTORS, json_encode($actors));
	}

	public function getActors()
	{
		if ($this->hasActors()) {
			return json_decode($this->get(FilmPreferencesStorage::ACTORS));
		}
		return array();
	}

	public function addActor($actor)
	{
		$actors = $this->getActors();
		$actors[] = $actor;
		return $this->setActors(array_unique($actors));
	}

	public function hasActors() {
		return $this->exist(FilmPreferencesStorage::ACTORS);
	}

	public function setGenres(array $genres)
	{
		return $this->set(FilmPreferencesStorage::GENRES, json_encode($genres));
	}

	public function getGenres()
	{
		if ($this->hasGenres()) {
			return json_decode($this->get(FilmPreferencesStorage::GENRES));
		}
		return array();
	}

	public function addGenre($genre)
	{
		$genres = $this->getActors();
		$genres[] = $genre;
		return $this->setActors(array_unique($genres));
	}

	public function hasGenres() {
		return $this->exist(FilmPreferencesStorage::GENRES);
	}

	public function setCinemas(array $cinemas)
	{
		return $this->set(FilmPreferencesStorage::GENRES, json_encode($cinemas));
	}

	public function getCinemas()
	{
		if ($this->hasCinemas()) {
			return json_decode($this->get(FilmPreferencesStorage::CINEMA));
		}
		return array();
	}

	public function addCinema($cinema)
	{
		$cinemas = $this->getCinemas();
		$cinemas[] = $cinema;
		return $this->setCinemas(array_unique($cinemas));
	}

	public function hasCinemas() {
		return $this->exist(FilmPreferencesStorage::CINEMA);
	}
}