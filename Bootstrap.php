<?php
define('BASE_PATH', dirname(__FILE__));
define('GRAMMAR_PATH', BASE_PATH . "/src/grammar/" );
define('GRAMMAR_CSV_PATH', GRAMMAR_PATH . "csv/" );
setlocale(LC_ALL,"es_ES");
Class Bootstrap
{
	const BOOTSTRAP_FILE = '/bootstrap.txt';
	private static $invalidPaths = array('.', '..');

	public static function load()
	{

		$requirePaths = explode("\n", trim(file_get_contents(BASE_PATH . self::BOOTSTRAP_FILE)));
		foreach ($requirePaths as $requirePath) {
			self::requirePath(dirname(__FILE__) . '/' . $requirePath);
		}
	}

	private static function requirePath($requirePath)
	{
		if (is_dir($requirePath)) {
			$requirePathChilds = scandir($requirePath);
			foreach ($requirePathChilds as $requirePathChild) {
				if (!in_array(basename($requirePathChild), self::$invalidPaths)) {
					self::requirePath($requirePath . "/" . $requirePathChild);
				}
			}
		} else {
			require_once  $requirePath;
		}
	}
}
