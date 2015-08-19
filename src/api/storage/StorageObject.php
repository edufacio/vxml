<?php
/**
 * Created by JetBrains PhpStorm.
 * User: econtreras
 * Date: 8/2/15
 * Time: 7:13 PM
 * To change this template use File | Settings | File Templates.
 */

abstract class StorageObject {
	protected  $rawRecord;

	function __construct($rawRecord)
	{
		$this->rawRecord = $rawRecord;
	}

	public static function createFromRecord($rawRecord) {
		throw new Exception('Not Implemented');
	}

	public static function create() {
		throw new Exception('Not Implemented');
	}

	public function  getRawRecord() {
		return $this->rawRecord;
	}

	protected function get($column, $default = null) {
		if ($this->exist($column)) {
			return $this->rawRecord[$column];
		} else {
			return $default;
		}
	}

	protected function set($column, $value) {
		$this->rawRecord[$column] = $value;
		return $this;
	}

	protected function exist($column) {
		return isset($this->rawRecord[$column]) && $this->rawRecord[$column] !== null;
	}
}