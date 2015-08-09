<?php
class Caller extends StorageObject
{
	const REMEMBER_TRUE = 1;
	const REMEMBER_FALSE = 0;

	/**
	 * @param $rawRecord
	 *
	 * @return Caller
	 */
	public static function createFromRecord($rawRecord) {
		return Injector::get('Caller', $rawRecord);
	}

	/**
	 * @return Caller
	 */
	public static function create() {
		return Injector::get('Caller', array());
	}

	public function setCallerId($callerId)
	{
		$this->set(CallerStorage::CALLER_ID, $callerId);
		return $this;
	}

	public function getCallerId()
	{
		return $this->get(CallerStorage::CALLER_ID);
	}

	public function hasCallerId() {
		return $this->exist(CallerStorage::CALLER_ID);
	}

	public function setFirstLoginTime($firstLoginTime)
	{
		$this->set(CallerStorage::FIRST_LOGIN_TIME, $firstLoginTime);
		return $this;
	}

	public function hasFirstLoginTime()
	{
		return $this->exist(CallerStorage::FIRST_LOGIN_TIME);
	}

	public function getFirstLoginTime()
	{
		return $this->get(CallerStorage::FIRST_LOGIN_TIME);

	}

	public function setLastLoginTime($lastLoginTime)
	{
		$this->set(CallerStorage::LAST_LOGIN_TIME, $lastLoginTime);

		return $this;
	}

	public function getLastLoginTime()
	{
		return $this->get(CallerStorage::LAST_LOGIN_TIME);
	}

	public function hasLastLoginTime()
	{
		return $this->exist(CallerStorage::LAST_LOGIN_TIME);
	}

	public function setPhone($phone)
	{
		$this->set(CallerStorage::PHONE, $phone);
		return $this;
	}

	public function hasPhone()
	{
		return $this->exist(CallerStorage::PHONE);
	}

	public function getPhone()
	{
		return $this->get(CallerStorage::PHONE);
	}

	public function isAutoLoginEnabled()
	{
		return $this->get(CallerStorage::REMEMBER, self::REMEMBER_FALSE) == self::REMEMBER_TRUE;
	}

	public function setAutoLogin($autologin) {
		return $this->set(CallerStorage::REMEMBER, $autologin);
	}
}