<?php

class SessionTime {
	private $cinema;
	private $times = array();

	function __construct($cinemas, $times)
	{
		$this->cinema = $cinemas;
		$this->times = $times;
	}

	/**
	 * @return Cinema
	 */
	public function getCinema()
	{
		return $this->cinema;
	}

	public function getTimes()
	{
		return $this->times;
	}


}