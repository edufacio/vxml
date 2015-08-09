<?php
/**
 * Created by JetBrains PhpStorm.
 * User: econtreras
 * Date: 8/8/15
 * Time: 11:06 AM
 * To change this template use File | Settings | File Templates.
 */

class CurrentSession
{

	private $session;
	private $callerId;
	private $currentPhone;
	private $status;
	private static $instance;

	function __construct($session, $callerId, $currentPhone, $status)
	{
		$this->callerId = $callerId;
		$this->currentPhone = $currentPhone;
		$this->session = $session;
		$this->status = $status;
	}

	/**
	 * @return CurrentSession
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = self::initialize(null,null,null,null);
		}

		return self::$instance;
	}

	/**
	 * @param $session
	 * @param $callerId
	 * @param $currentPhone
	 * @param $status
	 *
	 * @return CurrentSession
	 */
	public static function initialize($session, $callerId, $currentPhone, $status)
	{
		self::$instance = Injector::get('CurrentSession', $session, $callerId, $currentPhone, $status);
		return self::$instance;
	}

	public function getCallerId()
	{
		return $this->callerId;
	}

	public function getCurrentPhone()
	{
		return $this->currentPhone;
	}

	public function getSession()
	{
		return $this->session;
	}

	public function isLogged()
	{
		return $this->currentPhone !== null && $this->status == SessionStatus::LOGGED;
	}

	public function isRegistering()
	{
		return $this->currentPhone !== null && $this->status == SessionStatus::REGISTERING;
	}


}