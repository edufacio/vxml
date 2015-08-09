<?php
/**
 * Created by JetBrains PhpStorm.
 * User: econtreras
 * Date: 8/8/15
 * Time: 11:33 AM
 * To change this template use File | Settings | File Templates.
 */

class SessionsBackend
{

	private static $instance;

	/**
	 * @return SessionsBackend
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = Injector::get('SessionsBackend');
		}
		return self::$instance;
	}

	public function inizializateSession($sessionId, $callerId)
	{
		$phone = null;
		$status = null;
		/* @var $session Session */
		$session = SessionStorage::getInstance()->find(array(SessionStorage::SESSION_ID => $sessionId));
		if ($session !== null) {
			$phone = $session->getPhone();
			$status = $session->getSessionStatus();
		}

		CurrentSession::initialize($sessionId, $callerId, $phone, $status);
	}

	/**
	 * @return Caller
	 */
	public function getCallerInfoStored() {
		return CallerStorage::getInstance()->find(array(CallerStorage::CALLER_ID => CurrentSession::getInstance()->getCallerId()));
	}


	public function storePhoneLoginForCurrentCaller($autoLogin) {
		/* @var $caller Caller */
		$session = CurrentSession::getInstance();
		$caller = CallerStorage::getInstance()->find(array(CallerStorage::CALLER_ID => $session->getCallerId()));
		if ($caller === null) {
			$caller = Caller::create()->setCallerId($session->getCallerId())->setFirstLoginTime(time())->setLastLoginTime(time());
		}
		$caller->setPhone($session->getCurrentPhone());
		$caller->setAutoLogin($autoLogin);
		CallerStorage::getInstance()->save($caller);
	}

	public function deletePhoneLoginForCurrentCaller() {
		/* @var $caller Caller */
		$session = CurrentSession::getInstance();
		$caller = CallerStorage::getInstance()->find(array(CallerStorage::CALLER_ID => $session->getCallerId()));
		if ($caller !== null) {
			CallerStorage::getInstance()->delete($caller);
		}
	}

	public function hasPhoneStoredForCurrentCaller() {
		$caller = $this->getCallerInfoStored();
		return $caller != null && $caller->getPhone() !== null;
	}

	public function setUserLogging($phone)
	{
		$currentSession = CurrentSession::getInstance();
		$session = Session::create()->setSessionId($currentSession->getSession())->setPhone($phone)->setSessionTime(time())->setSessionStatus(SessionStatus::LOGGING);
		SessionStorage::getInstance()->save($session);
		CurrentSession::initialize($currentSession->getSession(), $currentSession->getCallerId(), $phone, SessionStatus::LOGGING);

	}

	public function setUserLogged($phone)
	{
		$currentSession = CurrentSession::getInstance();
		$session = Session::create()->setSessionId($currentSession->getSession())->setPhone($phone)->setSessionTime(time())->setSessionStatus(SessionStatus::LOGGED);
		SessionStorage::getInstance()->save($session);
		CurrentSession::initialize($currentSession->getSession(), $currentSession->getCallerId(), $phone, SessionStatus::LOGGED);
	}

	public function setUserRegistering($phone)
	{
		$currentSession = CurrentSession::getInstance();
		$session = Session::create()->setSessionId($currentSession->getSession())->setPhone($phone)->setSessionTime(time())->setSessionStatus(SessionStatus::REGISTERING);
		SessionStorage::getInstance()->save($session);
		CurrentSession::initialize($currentSession->getSession(), $currentSession->getCallerId(), $phone, SessionStatus::REGISTERING);
	}

	public function setUserUnlogged()
	{
		$currentSession = CurrentSession::getInstance();
		$session = Session::create()->setSessionId($currentSession->getSession())->setSessionTime(time())->setSessionStatus(SessionStatus::UNLOGGED);
		SessionStorage::getInstance()->save($session);

		CurrentSession::initialize($currentSession->getSession(), $currentSession->getCallerId(), null, SessionStatus::UNLOGGED);
	}
}