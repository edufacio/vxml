<?php
/**
 * Created by JetBrains PhpStorm.
 * User: econtreras
 * Date: 6/25/15
 * Time: 10:11 PM
 * To change this template use File | Settings | File Templates.
 */

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
			$class = new ReflectionClass($className);
			return $class->newInstanceArgs($params);
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
}