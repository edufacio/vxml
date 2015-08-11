<?php

class Province
{
	static private $provinces = array(
		"ES-C" => "a coruña",
		"ES-AB" => "albacete",
		"ES-A" => "alicante",
		"ES-AL" => "almería",
		"ES-VI" => "alava",
		"ES-O" => "asturias",
		"ES-AV" => "avila",
		"ES-BA" => "badajoz",
		"ES-B" => "barcelona",
		"ES-BI" => "vizcaya",
		"ES-BU" => "burgos",
		"ES-CC" => "caceres",
		"ES-CA" => "cadiz",
		"ES-S" => "cantabria",
		"ES-CS" => "castellón",
		"ES-CE" => "ceuta",
		"ES-CR" => "ciudad real",
		"ES-CO" => "cordoba",
		"ES-CU" => "cuenca",
		"ES-SS" => "gipuzcoa",
		"ES-GI" => "girona",
		"ES-GR" => "granada",
		"ES-GU" => "guadalajara",
		"ES-H" => "huelva",
		"ES-HU" => "guesca",
		"ES-PM" => "balears",
		"ES-J" => "jaen",
		"ES-LO" => "la rioja",
		"ES-GC" => "las palmas",
		"ES-LE" => "león",
		"ES-L" => "lleida",
		"ES-LU" => "lugo",
		"ES-M" => "madrid",
		"ES-MA" => "malaga",
		"ES-ML" => "melilla",
		"ES-MU" => "murcia",
		"ES-NA" => "navarra",
		"ES-OR" => "orense",
		"ES-P" => "palencia",
		"ES-PO" => "pontevedra",
		"ES-SA" => "salamanca",
		"ES-TF" => "santa cruz de tenerife",
		"ES-SG" => "segovia",
		"ES-SE" => "sevilla",
		"ES-SO" => "soria",
		"ES-T" => "tarragona",
		"ES-TE" => "teruel",
		"ES-TO" => "toledo",
		"ES-V" => "valencia",
		"ES-VA" => "valladolid",
		"ES-ZA" => "zamora",
		"ES-Z" => "zaragoza",
	);

	public static function getProvinceName($provinceId)
	{
		if (isset(self::$provinces[$provinceId])) {
			return self::$provinces[$provinceId];
		}
		throw new InvalidProvinceIdException($provinceId);
	}

	public static function getAllProvinces()
	{
		return self::$provinces;
	}
	public static function getProvinceId($province)
	{
		$province = strtolower($province);
		if (in_array($province, self::$provinces)) {
			return array_search($province, self::$provinces);
		}
		throw new InvalidProvinceIdException($province);
	}
}

Class InvalidProvinceIdException extends InvalidArgumentException
{

	function __construct($provinceId)
	{
		parent::__construct("Unknown PROVINCE_ID $provinceId");
	}
}

Class InvalidProvinceException extends InvalidArgumentException
{

	function __construct($province)
	{
		parent::__construct("Unknown Province $province");
	}
}