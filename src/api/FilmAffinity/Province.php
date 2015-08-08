<?php

class Province
{
	static private $provinces = array(
		"ES-C" => "A Coruña",
		"ES-AB" => "Albacete",
		"ES-A" => "Alicante",
		"ES-AL" => "Almería",
		"ES-VI" => "Álava",
		"ES-O" => "Asturias",
		"ES-AV" => "Ávila",
		"ES-BA" => "Badajoz",
		"ES-B" => "Barcelona",
		"ES-BI" => "Vizcaya",
		"ES-BU" => "Burgos",
		"ES-CC" => "Cáceres",
		"ES-CA" => "Cádiz",
		"ES-S" => "Cantabria",
		"ES-CS" => "Castellón",
		"ES-CE" => "Ceuta",
		"ES-CR" => "Ciudad Real",
		"ES-CO" => "Córdoba",
		"ES-CU" => "Cuenca",
		"ES-SS" => "Gipuzcoa",
		"ES-GI" => "Girona",
		"ES-GR" => "Granada",
		"ES-GU" => "Guadalajara",
		"ES-H" => "Huelva",
		"ES-HU" => "Huesca",
		"ES-PM" => "Balears",
		"ES-J" => "Jaén",
		"ES-LO" => "La Rioja",
		"ES-GC" => "Las Palmas",
		"ES-LE" => "León",
		"ES-L" => "Lleida",
		"ES-LU" => "Lugo",
		"ES-M" => "Madrid",
		"ES-MA" => "Málaga",
		"ES-ML" => "Melilla",
		"ES-MU" => "Murcia",
		"ES-NA" => "Navarra",
		"ES-OR" => "Orense",
		"ES-P" => "Palencia",
		"ES-PO" => "Pontevedra",
		"ES-SA" => "Salamanca",
		"ES-TF" => "Santa Cruz de Tenerife",
		"ES-SG" => "Segovia",
		"ES-SE" => "Sevilla",
		"ES-SO" => "Soria",
		"ES-T" => "Tarragona",
		"ES-TE" => "Teruel",
		"ES-TO" => "Toledo",
		"ES-V" => "Valencia",
		"ES-VA" => "Valladolid",
		"ES-ZA" => "Zamora",
		"ES-Z" => "Zaragoza",
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
		if (in_array($province, self::$provinces)) {
			return self::$cinemas[$city];
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