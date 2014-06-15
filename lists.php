<?php

if(! isset($_GET["field"])) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

if (! in_array(strtolower($_GET["field"]), 
	       array(
		       "cod_numero",
		       "cod_via", 
		       "clase_via",
		       "particula_via",
		       "nombre_via",
		       "literal_numeracion",
		       "cod_distrito",
		       "nombre_distrito",
		       "cod_barrio",
		       "nombre_barrio",
		       "seccion_censal",
		       "codigo_postal",
		       "seccion_carteria",
		       "zona_ser",
		       "categoria_fiscal",
		       "direccion_completa",
		       "tipo_via",
		       "situacion_via",
		       "denominacion_via",
		       "tipologia",
		       "zona_valor"))) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

$sqlite3 = new \SQLite3("callejero-madrid.sqlite");

$query = $sqlite3->query("SELECT DISTINCT ".$_GET["field"]." from CALLEJERO ORDER BY ".$_GET["field"].";");

$result = array();

while ($data = $query->fetchArray(SQLITE3_NUM)) {
	$result[] = $data;
};

header("Content-Type: application/json");
echo json_encode($result);

?>