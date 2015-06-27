<?php

class Injector
{
	private static $bindings = array();

	public static function get($className)
	{
		if (self::isBound($className)) {
			return self::getBinding($className);
		} else {
			$params = func_get_args();
			array_shift($params);
			return self::createInstance($className, $params);
		}
	}

	public static function bind($className, $object)
	{
		self::$bindings[$className] = $object;
	}

	private static function isBound($className)
	{
		return isset(self::$bindings[$className]);
	}

	private function getBinding($className)
	{
		return self::$bindings[$className];
	}

	private function createInstance($className, array $params)
	{
		if (empty($params)) {
			return new $className();
		} else {
			$class = new ReflectionClass($className);
			return $class->newInstanceArgs($params);
		}
	}
}