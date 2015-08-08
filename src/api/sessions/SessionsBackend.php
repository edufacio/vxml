<?php
/**
 * Created by JetBrains PhpStorm.
 * User: econtreras
 * Date: 8/8/15
 * Time: 11:33 AM
 * To change this template use File | Settings | File Templates.
 */

class SessionsBackend {

	private static $instance;

	/**
	 * @return SessionsBackend
	 */
	public static  function getInstance() {
		if (self::$instance === null) {
			self::$instance = Injector::get('SessionsBackend');
		}
		return self::$instance;
	}

	public function inizializateSession($sessionId, $callerId) {
		$phone = null;
		$status = null;
		/* @var $session Session */
		$session = SessionStorage::getInstance()->find(array(SessionStorage::SESSION_ID => $sessionId));
		if ($session !== null) {
			$phone = $session->getPhone();
			$status = $session->getSessionStatus();
			if ($session->getSessionStatus() == SessionStatus::LOGED)
				$this->storeCallerIfNeeded($callerId, $session->getPhone());
		} else {
			/* @var $caller Caller */
			$caller = CallerStorage::getInstance()->find(array(CallerStorage::CALLER_ID => $callerId));
			if ($caller !== null) {
				$phone = $caller->getPhone();
				if ($caller->isRememberActive()) {
					$status = SessionStatus::LOGED;
				} else {
					$status = SessionStatus::UNLOGED;
				}
			}
		}

		CurrentSession::initialize($sessionId, $callerId, $phone, $status);
	}

	private function storeCallerIfNeeded($callerId, $phone) {
	{
		/* @var $caller Caller */
		$storage = CallerStorage::getInstance();
		$caller = $storage->find(array(CallerStorage::CALLER_ID => $callerId));

		if ($caller === null || $caller->getPhone() !== $phone) {
			$newCallerInfo = Caller::create();
			$newCallerInfo->setPhone($phone);
			$newCallerInfo->setLastLoginTime(time());
			$newCallerInfo->setFirstLoginTime(time());
			$storage->save($newCallerInfo);
		}
	}

	}
}