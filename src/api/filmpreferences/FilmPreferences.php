<?php
class FilmPreferences extends StorageObject
{
	/**
	 * @param $rawRecord
	 *
	 * @return FilmPreferences
	 */
	public static function createFromRecord($rawRecord)
	{
		return Injector::get('FilmPreferences', $rawRecord);
	}

	/**
	 * @return FilmPreferences
	 */
	public static function create()
	{
		return Injector::get('FilmPreferences', array());
	}

	/**
	 * @param $phone
	 *
	 * @return $this
	 */
	public function setPhone($phone)
	{
		return $this->set(FilmPreferencesStorage::PHONE, $phone);
	}

	/**
	 * @return null
	 */
	public function getPhone()
	{
		return $this->get(FilmPreferencesStorage::PHONE);
	}

	/**
	 * @return bool
	 */
	public function hasPhone()
	{
		return $this->exist(FilmPreferencesStorage::PHONE);
	}

	/**
	 * @param array $favouriteDirectors
	 *
	 * @return $this
	 */
	public function setFavouriteDirectors(array $favouriteDirectors)
	{
		return $this->set(FilmPreferencesStorage::FAVOURITE_DIRECTORS, json_encode($favouriteDirectors));
	}

	/**
	 * @return array|mixed
	 */
	public function getFavouriteDirectors()
	{
		if ($this->exist(FilmPreferencesStorage::FAVOURITE_DIRECTORS)) {
			return json_decode($this->get(FilmPreferencesStorage::FAVOURITE_DIRECTORS), true);
		}
		return array();
	}

	/**
	 * @param $FavouriteDirector
	 *
	 * @return $this
	 */
	public function addFavouriteDirector($FavouriteDirector)
	{
		$FavouriteDirectors = $this->getFavouriteDirectors();
		$FavouriteDirectors[] = $FavouriteDirector;
		return $this->setFavouriteDirectors(array_unique($FavouriteDirectors));
	}

	/**
	 * @return bool
	 */
	public function hasFavouriteDirectors()
	{
		$FavouriteDirectors = $this->getFavouriteDirectors();
		return !empty($FavouriteDirectors);
	}

	/**
	 * @param array $favouriteActors
	 *
	 * @return $this
	 */
	public function setFavouriteActors(array $favouriteActors)
	{
		return $this->set(FilmPreferencesStorage::FAVOURITE_ACTORS, json_encode($favouriteActors));
	}

	/**
	 * @return array|mixed
	 */
	public function getFavouriteActors()
	{
		if ($this->exist(FilmPreferencesStorage::FAVOURITE_ACTORS)) {
			return json_decode($this->get(FilmPreferencesStorage::FAVOURITE_ACTORS), true);
		}
		return array();
	}

	/**
	 * @param $favouriteActor
	 *
	 * @return $this
	 */
	public function addFavouriteActor($favouriteActor)
	{
		$favouriteActors = $this->getFavouriteActors();
		$favouriteActors[] = $favouriteActor;
		return $this->setFavouriteActors(array_unique($favouriteActors));
	}

	/**
	 * @return bool
	 */
	public function hasFavouriteActors()
	{
		$favouriteActors = $this->getFavouriteActors();
		return !empty($favouriteActors);
	}

	/**
	 * @param array $favouriteGenres
	 *
	 * @return $this
	 */
	public function setFavouriteGenres(array $favouriteGenres)
	{
		return $this->set(FilmPreferencesStorage::FAVOURITE_GENRES, json_encode($favouriteGenres));
	}

	/**
	 * @return array|mixed
	 */
	public function getFavouriteGenres()
	{
		if ($this->exist(FilmPreferencesStorage::FAVOURITE_GENRES)) {
			return json_decode($this->get(FilmPreferencesStorage::FAVOURITE_GENRES), true);
		}
		return array();
	}

	/**
	 * @param $favouriteGenre
	 *
	 * @return $this
	 */
	public function addFavouriteGenre($favouriteGenre)
	{
		$FavouriteGenres = $this->getFavouriteGenres();
		$FavouriteGenres[] = $favouriteGenre;
		return $this->setFavouriteGenres(array_unique($FavouriteGenres));
	}

	/**
	 * @return bool
	 */
	public function hasFavouriteGenres()
	{
		$FavouriteGenres = $this->getFavouriteGenres();
		return !empty($FavouriteGenres);
	}

	/**
	 * @param array $dislikedDirectors
	 *
	 * @return $this
	 */
	public function setDislikedDirectors(array $dislikedDirectors)
	{
		return $this->set(FilmPreferencesStorage::DISLIKED_DIRECTORS, json_encode($dislikedDirectors));
	}

	/**
	 * @return array|mixed
	 */
	public function getDislikedDirectors()
	{
		if ($this->exist(FilmPreferencesStorage::DISLIKED_DIRECTORS)) {
			return json_decode($this->get(FilmPreferencesStorage::DISLIKED_DIRECTORS), true);
		}
		return array();
	}

	/**
	 * @param $DislikedDirector
	 *
	 * @return $this
	 */
	public function addDislikedDirector($DislikedDirector)
	{
		$DislikedDirectors = $this->getDislikedDirectors();
		$DislikedDirectors[] = $DislikedDirector;
		return $this->setDislikedDirectors(array_unique($DislikedDirectors));
	}

	/**
	 * @return bool
	 */
	public function hasDislikedDirectors()
	{
		$DislikedDirectors = $this->getDislikedDirectors();
		return !empty($DislikedDirectors);
	}

	/**
	 * @param array $dislikedActors
	 *
	 * @return $this
	 */
	public function setDislikedActors(array $dislikedActors)
	{
		return $this->set(FilmPreferencesStorage::DISLIKED_ACTORS, json_encode($dislikedActors));
	}

	/**
	 * @return array|mixed
	 */
	public function getDislikedActors()
	{
		if ($this->exist(FilmPreferencesStorage::DISLIKED_ACTORS)) {
			return json_decode($this->get(FilmPreferencesStorage::DISLIKED_ACTORS), true);
		}
		return array();
	}

	/**
	 * @param $dislikedActor
	 *
	 * @return $this
	 */
	public function addDislikedActor($dislikedActor)
	{
		$dislikedActors = $this->getDislikedActors();
		$dislikedActors[] = $dislikedActor;
		return $this->setDislikedActors(array_unique($dislikedActors));
	}

	/**
	 * @return bool
	 */
	public function hasDislikedActors()
	{
		$dislikedActors = $this->getDislikedActors();
		return !empty($dislikedActors);
	}

	/**
	 * @param array $dislikedGenres
	 *
	 * @return $this
	 */
	public function setDislikedGenres(array $dislikedGenres)
	{
		return $this->set(FilmPreferencesStorage::DISLIKED_GENRES, json_encode($dislikedGenres));
	}

	/**
	 * @return array|mixed
	 */
	public function getDislikedGenres()
	{
		if ($this->exist(FilmPreferencesStorage::DISLIKED_GENRES)) {
			return json_decode($this->get(FilmPreferencesStorage::DISLIKED_GENRES), true);
		}
		return array();
	}

	/**
	 * @param $dislikedGenre
	 *
	 * @return $this
	 */
	public function addDislikedGenre($dislikedGenre)
	{
		$DislikedGenres = $this->getDislikedGenres();
		$DislikedGenres[] = $dislikedGenre;
		return $this->setDislikedGenres(array_unique($DislikedGenres));
	}

	/**
	 * @return bool
	 */
	public function hasDislikedGenres()
	{
		$DislikedGenres = $this->getDislikedGenres();
		return !empty($DislikedGenres);
	}

	/**
	 * @param array $cinemas
	 *
	 * @return $this
	 */
	public function setCinemas(array $cinemas)
	{
		return $this->set(FilmPreferencesStorage::FAVOURITE_GENRES, json_encode($cinemas));
	}

	/**
	 * @return array|mixed
	 */
	public function getCinemas()
	{
		if ($this->exist(FilmPreferencesStorage::CINEMA)) {
			return json_decode($this->get(FilmPreferencesStorage::CINEMA), true);
		}
		return array();
	}

	/**
	 * @param $cinema
	 *
	 * @return $this
	 */
	public function addCinema($cinema)
	{
		$cinemas = $this->getCinemas();
		$cinemas[] = $cinema;
		return $this->setCinemas(array_unique($cinemas));
	}

	/**
	 * @return bool
	 */
	public function hasCinemas()
	{
		$cinemas = $this->getCinemas();
		return !empty($cinemas);
	}

	public function getCinemaNames($provinceId) {
		$cinemas = FilmAffinityApi::getInstance()->getCinemas($provinceId, $this->getCinemas());
		$cinemaNames = array();
		foreach ($cinemas as $cinemaId => $cinema) {
			$cinemaNames[$cinemaId] = $cinema->getName();
		}
		return $cinemaNames;
	}
}