<?php
require_once dirname(__FILE__) . "/Bootstrap.php";
Bootstrap::load();
$router = new Router();
$router->request();
die;


//Consulta para sacar la informacion que necesitamos de la BD
//$consulta = mysql_query("SELECT * FROM actor WHERE nombre= '" . $_REQUEST["nombre"] . "'");

//******* TENGO QUE QUITAR EL ESPACIO DEL NOMBRE RECIBIDO Y PONER UN +, PARA QUE CUMPLA EL FORMATO DE LA URL
$nombre =  $_REQUEST["nombre"];
for($i=0;$i<strlen($nombre);$i++){
	if ($nombre[$i] == ' ')
		$nombre2=$nombre2."+"; 
	else
		$nombre2=$nombre2.$nombre[i];
}	

/*
*****************************
HAY QUE UTILIZAR $nombre2 PARA REALIZAR LA BUSQUEDA DE LA PELICULA QUE CONTENGA EL ACTOR PASADO POR PARAMETRO
*/

//Capturamos el contenido de la pagina web y lo guardamos en el fichero "actor.txt"
//$ch = curl_init("http://www.filmaffinity.com/es/search.php?stext=' . $nombre2 . '&stype=all");
$ch = curl_init("http://www.filmaffinity.com/es/search.php?stext=santiago+segura&stype=all");
$fp = fopen("actor.txt", "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);

fclose($fp);

//Abrimos el fichero en modo lectura
$ar=fopen("actor.txt","r") or
    die("No se pudo abrir el archivo");

//$i=0; // Variable para indicar el ID cuando insertamos en la base de datos
while (!feof($ar))
{    
	$lineaSalto=fgets($ar); //Lee una linea del fichero
    $clave="Resultados por reparto"; // Clave que nos indica donde se encuentran las peliculas por reparto

    //Buscamos una clave dentro de la linea
    if (eregi($clave, $lineaSalto)){
		while (!feof($ar))
		{    
			$lineaSalto=fgets($ar); //Lee una linea del fichero
			$clave="<b><a href"; // Clave que nos indica donde se encuentran el titulo de la pelicula

			//Buscamos una clave dentro de la linea
			if (eregi($clave, $lineaSalto)){	
				//Elimina todas las etiquetas HTML y PHP de una cadena
				$titulo = strip_tags($lineaSalto); 
				//Guardamos en un array las peliculas
				if ($i!=0)
					$array[]= $titulo;
				$i=$i+1;
			}
		}
    }
}
  
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>";
?>


<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml" xmlns:voxeo="http://community.voxeo.com/xmlns/vxml" xml:lang="es-MX"> 

<form id="cartelera">
	<field name="nombre">
        <prompt>
			Estas son las peliculas en las que actua el actor <?php echo $nombre; ?>
			<?php
				for($i=0;$i<count($array);$i++)
					echo $array[$i];
			?>
        </prompt>
    </field>
</form>
</vxml>
