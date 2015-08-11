<?php

class InputSanitizer
{
	public static function toInt($input)
	{
		return intval(preg_replace("/[^0-9]/", "", $input));
	}
}