<?php

class ShowTime {
	private $film;
	private $sessionTimes = array();

	function __construct($film, $sessionTimes)
	{
		$this->film = $film;
		$this->sessionTimes = $sessionTimes;
	}

	/**
	 * @return Film
	 */
	public function getFilm()
	{
		return $this->film;
	}


	public function addSessionTime($sessionTime) {
		$this->sessionTimes[] = $sessionTime;
	}

	/**
	 * @return SessionTime[]
	 */
	public function getSessionTimes()
	{
		return $this->sessionTimes;
	}
}