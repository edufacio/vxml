<?php
class Session extends StorageObject
{
	/**
	 * @param $rawRecord
	 *
	 * @return Session
	 */
	public static function createFromRecord($rawRecord) {
		return Injector::get('Session', $rawRecord);
	}

	/**
	 * @return Session
	 */
	public static function create() {
		return Injector::get('Session', array());
	}

	public function setSessionId($sessionId)
	{
		$this->set(SessionStorage::SESSION_ID, $sessionId);
		return $this;
	}

	public function getSessionId()
	{
		return $this->get(SessionStorage::SESSION_ID);
	}

	public function hasSessionId() {
		return $this->exist(SessionStorage::SESSION_ID);
	}

	public function setSessionStatus($sessionStatus)
	{
		$this->set(SessionStorage::SESSION_STATUS, $sessionStatus);
		return $this;
	}

	public function hasSessionStatus()
	{
		return $this->exist(SessionStorage::SESSION_STATUS);
	}

	public function getSessionStatus()
	{
		return $this->get(SessionStorage::SESSION_STATUS);

	}

	public function setSessionTime($sessionTime)
	{
		$this->set(SessionStorage::SESSION_TIME, $sessionTime);

		return $this;
	}

	public function getSessionTime()
	{
		return $this->get(SessionStorage::SESSION_TIME);
	}

	public function hasSessionTime()
	{
		return $this->exist(SessionStorage::SESSION_ID);
	}

	public function setPhone($phone)
	{
		$this->set(SessionStorage::PHONE, $phone);
		return $this;
	}

	public function hasPhone()
	{
		return $this->exist(SessionStorage::PHONE);
	}

	public function getPhone()
	{
		return $this->get(SessionStorage::PHONE);
	}
}