<?php

function ucwords_specific ($string, $delimiters = '', $encoding = NULL) 
{ 
	if ($encoding === NULL) { $encoding = mb_internal_encoding();} 

	if (is_string($delimiters)) 
	{ 
		$delimiters =  str_split( str_replace(' ', '', $delimiters)); 
	} 

	$delimiters_pattern1 = array(); 
	$delimiters_replace1 = array(); 
	$delimiters_pattern2 = array(); 
	$delimiters_replace2 = array(); 
	foreach ($delimiters as $delimiter) 
	{ 
		$uniqid = uniqid(); 
		$delimiters_pattern1[]   = '/'. preg_quote($delimiter) .'/'; 
		$delimiters_replace1[]   = $delimiter.$uniqid.' '; 
		$delimiters_pattern2[]   = '/'. preg_quote($delimiter.$uniqid.' ') .'/'; 
		$delimiters_replace2[]   = $delimiter; 
	} 

	// $return_string = mb_strtolower($string, $encoding); 
	$return_string = $string; 
	$return_string = preg_replace($delimiters_pattern1, $delimiters_replace1, $return_string); 

	$words = explode(' ', $return_string); 

	foreach ($words as $index => $word) 
	{ 
		$words[$index] = mb_strtoupper(mb_substr($word, 0, 1, $encoding), $encoding).mb_substr($word, 1, mb_strlen($word, $encoding), $encoding); 
	} 

	$return_string = implode(' ', $words); 

	$return_string = preg_replace($delimiters_pattern2, $delimiters_replace2, $return_string); 

	return $return_string; 
} 

if(! isset($_POST["clase_via"]) || ! isset($_POST["nombre_via"]) || ! isset($_POST["distrito"]) || ! isset($_POST["barrio"]) || ! isset($_POST["codigo_postal"])) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

$arrayWhere = array();

if ($_POST["clase_via"]!="")
	$arrayWhere[] = "clase_via='".$_POST["clase_via"]."'";

if ($_POST["nombre_via"]!="")
	$arrayWhere[] = "nombre_via='".$_POST["nombre_via"]."'";

if ($_POST["distrito"]!="")
	$arrayWhere[] = "nombre_distrito='".$_POST["distrito"]."'";

if ($_POST["barrio"]!="")
	$arrayWhere[] = "nombre_barrio='".$_POST["barrio"]."'";

if ($_POST["codigo_postal"]!="")
	$arrayWhere[] = "codigo_postal='".$_POST["codigo_postal"]."'";

$sqlite3 = new \SQLite3("callejero-madrid.sqlite");

$query = $sqlite3->query("SELECT * FROM CALLEJERO WHERE ".join(" AND ", $arrayWhere)." ORDER BY direccion_completa ;");

header ("Content-Type: application/x-osm+xml");
header('Content-Disposition: attachment; filename="callejero-import.osm"');

echo "<?xml version='1.0' encoding='UTF-8'?>
<osm version='0.6' upload='true' generator='davefx_osm_callejero_importer'>\n";

$i=0;

while ($data = $query->fetchArray(SQLITE3_ASSOC)) {
	$i = $i - 1;

	$number = $data["literal_numeracion"];
	if (substr($number,0,3) == "NUM") {
		$number = ltrim(substr($number, 3), "0");
	} else if (subst($number, 0, 3) == "KM.") {
		
	}

	$street = ucwords_specific(mb_strtolower($data["clase_via"]), "'-")." ".$data["particula_via"]." ".ucwords_specific(mb_strtolower($data["nombre_via"]), "'-");

	echo "	<node id='$i' action='modify' visible='true' lat='".$data["Lat"]."' lon='".$data["Lon"]."'>
                <tag k='addr:city' v='Madrid' />
                <tag k='addr:country' v='ES' />
		<tag k='addr:housenumber' v='".$number."' />
		<tag k='addr:postcode' v='".$data["codigo_postal"]."' />
		<tag k='addr:street' v='".$street."' />
	</node>\n";
	
	
};

echo "</osm>";

?>