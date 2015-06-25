<?php
class KeyPhone {
    const KEY_1 = 'dtmf-1';
    const KEY_2 = 'dtmf-2';
    const KEY_3 = 'dtmf-3';
    const KEY_4 = 'dtmf-4';
    const KEY_5 = 'dtmf-5';
    const KEY_6 = 'dtmf-6';
    const KEY_7 = 'dtmf-7';
    const KEY_8 = 'dtmf-8';
    const KEY_9 = 'dtmf-9';
    const KEY_0 = 'dtmf-0';
    const KEY_STAR = 'dtmf-star';

	private static $keyByNumber = array(
		0 => self::KEY_0,
		1 => self::KEY_1,
		2 => self::KEY_2,
		3 => self::KEY_3,
		4 => self::KEY_4,
		5 => self::KEY_5,
		6 => self::KEY_6,
		7 => self::KEY_7,
		8 => self::KEY_8,
		9 => self::KEY_9,
		'*' => self::KEY_STAR,
	);

	private static $keyNames = array(
		self::KEY_0 => 'cero',
		self::KEY_1 => 'uno',
		self::KEY_2 => 'dos',
		self::KEY_3 => 'tres',
		self::KEY_4 => 'cuatro',
		self::KEY_5 => 'cinco',
		self::KEY_6 => 'seis',
		self::KEY_7 => 'siete',
		self::KEY_8 => 'ocho',
		self::KEY_9 => 'nueve',
		self::KEY_STAR => 'asterisco',
	);

	public static function isKeyPhone($keyPhone)
	{
		return in_array($keyPhone, self::$keyByNumber);
	}

	public static function toDigit($keyPhone)
	{
		if (self::isKeyPhone($keyPhone)) {
			return array_search($keyPhone, self::$keyByNumber);
		}
		return null;
	}

	public static function toNumberName($keyPhone)
	{
		if (self::isKeyPhone($keyPhone)) {
			return self::$keyNames[$keyPhone];
		}
		return null;
	}

	public static function fromDigit($digit)
	{
		return isset(self::$keyByNumber[$digit]) ? self::$keyByNumber[$digit] : null;
	}
}