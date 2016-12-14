osm-callejero-importer
======================

Project for tool for importing Madrid's street catalog with policy numbers to OpenStreetMap.

It uses an included sqlite database composed after working in the address housenumbers extracted from http://datos.madrid.es. 

------

Herramienta de importación del catálogo de números de policía del Ayuntamiento de Madrid a OpenStreetMap.

Incluye una base de datos realizada a partir de la manipulación de los datos con números de portales extraídos de http://datos.madrid.es. 


Creación de base de datos
-------------------------

* Descargar CSV con conjunto de datos del Callejero oficial del
  Ayuntamiento de Madrid 
    
* Convertir datos descargados a UTF-8 (mediante recode o herramienta
  similar).

* Mediante Excel o LibreCalc, crear campos Lat y Lon a partir de
  longitud y latitud mediante las siguientes fórmulas:
  
 * Columna AA (Latitud):

   `=SUSTITUIR(IZQUIERDA($T1;ENCONTRAR("º";$T1)-1)+((1/60)*EXTRAEB($T1;
   ENCONTRAR("º";$T1)+1;ENCONTRAR("'";$T1)-ENCONTRAR("º";$T1)-1))+
   ((1/3600)*SUSTITUIR(EXTRAEB($T1;ENCONTRAR("'";$T1)+1;ENCONTRAR("
   ";$T1)-ENCONTRAR("'";$T1)-3);".";","));",";".")`

 * Columna AB (Longitud)
  
  `=SUSTITUIR((-1)*(IZQUIERDA($S1;ENCONTRAR("º";$S1)-1)+((1/60)*EXTRAEB($S1; ENCONTRAR("º"; $S1)+1;ENCONTRAR("'";$S1)-ENCONTRAR("º";$S1)-1))+((1/3600)*SUSTITUIR(EXTRAEB($S1;ENCONTRAR("'";$S1)+1;ENCONTRAR(" ";$S1)-ENCONTRAR("'";$S1)-3);".";",")));",";".")`

* Generar nuevo fichero en formato sqlite3: `sqlite3 fichero.sqlite`

* Crear tabla de callejero:  
  `CREATE TABLE "CALLEJERO" (cod_numero INTEGER PRIMARY KEY, cod_via INTEGER, clase_via TEXT, particula_via TEXT, nombre_via TEXT, literal_numeracion TEXT, cod_distrito INTEGER, nombre_distrito TEXT, cod_barrio INTEGER, nombre_barrio TEXT, seccion_censal INTEGER, codigo_postal INTEGER, seccion_carteria INTEGER, zona_ser INTEGER, categoria_fiscal INTEGER, direccion_completa TEXT, utm_x INTEGER, utm_y INTEGER, longitud TEXT, latitud TEXT, tipo_via TEXT, situacion_via TEXT, denominacion_via TEXT, parcela_catastral TEXT, tipologia TEXT, zona_valor TEXT, Lat NUMBER(10,6), Lon NUMBER(10,6));`
 
* Cargar CSV en base de datos  
  `.mode csv  
   .separator ;  
   .import /ruta/a/CALLEJERO.CSV CALLEJERO`
   
* Crear índices en la base de datos

  `create index clase_via_idx on CALLEJERO(clase_via);`  
  `create index nombre_via_idx on CALLEJERO(nombre_via);`    
  `create index nombre_distrito_idx on CALLEJERO(nombre_distrito);`  
  `create index nombre_barrio_idx on CALLEJERO(nombre_barrio);`  
  `create index codigo_postal_idx on CALLEJERO(codigo_postal);`
   
