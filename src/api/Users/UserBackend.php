<?php
/**
 * Created by JetBrains PhpStorm.
 * User: econtreras
 * Date: 8/8/15
 * Time: 7:21 PM
 * To change this template use File | Settings | File Templates.
 */

class UserBackend
{
	private static $instance;

	/**
	 * @return UserBackend
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = Injector::get('UserBackend');
		}
		return self::$instance;
	}

	public function passwordIsCorrect($phone, $password)
	{
		/* @var $user User */
		$user = UserStorage::getInstance()->find(array(UserStorage::PHONE => $phone), User::create());
		return $user->getPassword() == $password;
	}

	public function exists($phone)
	{
		/* @var $user User */
		$user = UserStorage::getInstance()->find(array(UserStorage::PHONE => $phone));
		return $user !== null;
	}

	public function createUser($phone, $password)
	{
		/* @var $user User */
		$user = User::create();
		$user->setPhone($phone)->setPassword($password)->setRegisterTime(time());
		return UserStorage::getInstance()->save($user);
	}

	/**
	 * @param $phone
	 * @param $password
	 *
	 * @return User
	 */
	public function getUser($phone)
	{
		return UserStorage::getInstance()->find(array(UserStorage::PHONE => $phone), User::create()->setPhone($phone));
	}

	public function saveProvince($phone, $province)
	{
		$user = $this->getUser($phone);
		$user->setProvinceId($province);
		UserStorage::getInstance()->save($user);
	}

	public function saveSchedule($phone, $startSchedule, $endSchedule)
	{
		$user = $this->getUser($phone);
		$user->setStartFavouriteSchedule($startSchedule);
		$user->setEndFavouriteSchedule($endSchedule);
		UserStorage::getInstance()->save($user);
	}

	public function getFavouriteDirectors($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		return $filmPreferences->getFavouriteDirectors();
	}

	public function addFavouriteDirector($phone, $director)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->addFavouriteDirector($director);
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function deleteFavouriteDirectors($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->setFavouriteDirectors(array());
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function getFavouriteActors($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		return $filmPreferences->getFavouriteActors();
	}

	/**
	 * @param $phone
	 *
	 * @return FilmPreferences
	 */
	public function getPreferences($phone)
	{
		return FilmPreferencesStorage::getInstance()
			->find(array(FilmPreferencesStorage::PHONE => $phone), FilmPreferences::create()->setPhone($phone));
	}

	public function addFavouriteActor($phone, $actor)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->addFavouriteActor($actor);
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function deleteFavouriteActors($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->setFavouriteActors(array());
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function getFavouriteGenres($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		return $filmPreferences->getFavouriteGenres();
	}

	public function addFavouriteGenre($phone, $genre)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->addFavouriteGenre($genre);
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function deleteFavouriteGenres($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->setFavouriteGenres(array());
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function getFavouriteCinemas($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		return $filmPreferences->getCinemas();
	}

	public function addFavouriteCinemas($phone, $cinema)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->addCinema($cinema);
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function deleteFavouriteCinemas($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->setCinemas(array());
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}
	/////////////////////////
	public function getDislikedDirectors($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		return $filmPreferences->getDislikedDirectors();
	}

	public function addDislikedDirector($phone, $director)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->addDislikedDirector($director);
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function deleteDislikedDirectors($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->setDislikedDirectors(array());
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function getDislikedActors($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		return $filmPreferences->getDislikedActors();
	}

	public function addDislikedActor($phone, $actor)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->addDislikedActor($actor);
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function deleteDislikedActors($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->setDislikedActors(array());
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function getDislikedGenres($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		return $filmPreferences->getDislikedGenres();
	}

	public function addDislikedGenre($phone, $genre)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->addDislikedGenre($genre);
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function deleteDislikedGenres($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		$filmPreferences->setDislikedGenres(array());
		FilmPreferencesStorage::getInstance()->save($filmPreferences);
	}

	public function getDislikedCinemas($phone)
	{
		$filmPreferences = $this->getPreferences($phone);
		return $filmPreferences->getCinemas();
	}

	public function getShowTimes(User $user)
	{
		$cinemas = $this->getPreferences($user->getPhone())->getCinemas();
		return FilmAffinityApi::getInstance()->getShowtimes($user->getProvinceId(), $cinemas, $user->getStartFavouriteSchedule(), $user->getEndFavouriteSchedule());
	}
}