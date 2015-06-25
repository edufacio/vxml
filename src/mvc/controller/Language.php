<?php
class Language {
    const esES = 'es-ES';
    const enUS = 'en-US';

	private static $availableLanguages = array(
		self::esES => self::esES,
		self::enUS => self::enUS,
	);

	public static function isLanguageValid($language)
	{
		return isset(self::$availableLanguages[$language]);
	}
}

Class InvalidLanguageException extends InvalidArgumentException {

	function __construct($language)
	{
		parent::__construct("$language is not valid language for system");
	}
}