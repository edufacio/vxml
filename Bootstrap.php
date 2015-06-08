<?php
Class Bootstrap
{
	const BOOTSTRAP_FILE = 'bootstrap.txt';
	private static $invalidPaths = array('.', '..');

	public static function load()
	{
		$requirePaths = explode("\n", trim(file_get_contents(self::BOOTSTRAP_FILE)));
		foreach ($requirePaths as $requirePath) {
			self::requirePath(dirname(__FILE__) . '/' . $requirePath);
		}
	}

	private static function requirePath($requirePath)
	{
		if (is_dir($requirePath)) {
			$requirePathChilds = scandir($requirePath);
			foreach ($requirePathChilds as $requirePathChild) {
				if (!in_array(basename($requirePath), self::$invalidPaths)) {
					self::requirePath($requirePath . "/" . $requirePathChild);
				}
			}
		} else {
			require_once  $requirePath;
		}
	}
}