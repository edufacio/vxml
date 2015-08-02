<?php

class Province
{
	static private $cities = array(
		"ES-M" => "Madrid",
		"ES-B" => "Barcelona",
		"ES-C" => "A Coruña",
		"ES-AB" => "Albacete",
		"ES-A" => "Alicante/Alacant",
		"ES-AL" => "Almería",
		"ES-VI" => "Araba/Álava",
		"ES-O" => "Asturias",
		"ES-AV" => "Ávila",
		"ES-BA" => "Badajoz",
		"ES-B" => "Barcelona",
		"ES-BI" => "Vizcaya",
		"ES-BU" => "Burgos",
		"ES-CC" => "Cáceres",
		"ES-CA" => "Cádiz",
		"ES-S" => "Cantabria",
		"ES-CS" => "Castellón/Castelló",
		"ES-CE" => "Ceuta",
		"ES-CR" => "Ciudad Real",
		"ES-CO" => "Córdoba",
		"ES-CU" => "Cuenca",
		"ES-SS" => "Gipuzkoa",
		"ES-GI" => "Girona",
		"ES-GR" => "Granada",
		"ES-GU" => "Guadalajara",
		"ES-H" => "Huelva",
		"ES-HU" => "Huesca",
		"ES-PM" => "Illes Balears",
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
		"ES-OR" => "Ourense",
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
		"ES-V" => "Valencia/València",
		"ES-VA" => "Valladolid",
		"ES-ZA" => "Zamora",
		"ES-Z" => "Zaragoza",
	);

	public static function getCityName($city)
	{
		if (isset($city)) {
			return self::$cinemas[$city];
		}
	}

	public static function getCityId($city)
	{
		if (isset($city)) {
			return self::$cinemas[$city];
		}
	}
}

Class InvalidCinemaIdException extends InvalidArgumentException
{

	function __construct($cinemaId)
	{
		parent::__construct("");
	}
}