<?php

class Cinema {
	private $id;
	private $name;
	private $details;

	function __construct($id, $name, $details)
	{
		$this->details = $details;
		$this->id = $id;
		$this->name = $name;
	}


	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}
}