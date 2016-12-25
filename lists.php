<?php

if(! isset($_GET["field"])) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

if (! in_array(strtolower($_GET["field"]), 
	       array(
		       "clase_via",
		       "nombre_via",
		       "cod_distrito",
		       "cod_barrio",
		       "codigo_postal"))) {
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