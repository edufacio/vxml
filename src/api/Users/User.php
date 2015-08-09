<?php
class User extends StorageObject
{
	/**
	 * @param $rawRecord
	 *
	 * @return User
	 */
	public static function createFromRecord($rawRecord) {
		return Injector::get('User', $rawRecord);
	}

	/**
	 * @return User
	 */
	public static function create() {
		return Injector::get('User', array());
	}

	public function setProvinceId($provinceId)
	{
		return $this->set(UserStorage::PROVINCE_ID, $provinceId);
	}

	public function getProvinceId()
	{
		return $this->get(UserStorage::PROVINCE_ID);
	}

	public function hasProvinceId() {
		return $this->exist(UserStorage::PROVINCE_ID);

	}

	public function setEndFavouriteSchedule($endFavouriteSchedule)
	{
		return $this->set(UserStorage::END_SCHEDULE, $endFavouriteSchedule);
	}

	public function hasEndFavouriteSchedule()
	{
		return $this->exist(UserStorage::END_SCHEDULE);
	}

	public function getEndFavouriteSchedule()
	{
		return $this->get(UserStorage::END_SCHEDULE);
	}

	/**
	 * @param $password
	 *
	 * @return $this
	 */
	public function setPassword($password)
	{
		return $this->set(UserStorage::PASSWORD, $password);
	}

	public function hasPassword()
	{
		return $this->exist(UserStorage::PASSWORD);
	}

	public function getPassword()
	{
		return $this->get(UserStorage::PASSWORD);
	}

	public function setPhone($phone)
	{
		return $this->set(UserStorage::PHONE, $phone);
	}

	public function hasPhone()
	{
		return $this->exist(UserStorage::PHONE);
	}

	public function getPhone()
	{
		return $this->get(UserStorage::PHONE);
	}

	public function setRegisterStatus($registerStatus)
	{
		return $this->set(UserStorage::REGISTER_STATUS, $registerStatus);
	}

	public function hasRegisterStatus()
	{
		return $this->exist(UserStorage::REGISTER_STATUS);

	}

	public function getRegisterStatus()
	{
		return $this->get(UserStorage::REGISTER_STATUS);


	}

	/**
	 * @param $registerTime
	 *
	 * @return $this
	 */
	public function setRegisterTime($registerTime)
	{
		return $this->set(UserStorage::REGISTER_TIME, $registerTime);
	}

	public function hasRegisterTime()
	{
		return $this->exist(UserStorage::REGISTER_TIME);
	}

	public function getRegisterTime()
	{
		return $this->get(UserStorage::REGISTER_TIME);
	}

	public function setStartFavouriteSchedule($startFavouriteSchedule)
	{
		return $this->set(UserStorage::START_SCHEDULE, $startFavouriteSchedule);
	}

	public function hasStartFavouriteSchedule()
	{
		return $this->exist(UserStorage::START_SCHEDULE);
	}

	public function getStartFavouriteSchedule()
	{
		return $this->get(UserStorage::START_SCHEDULE);
	}
}