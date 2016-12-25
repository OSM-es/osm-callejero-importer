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
  Ayuntamiento de Madrid (Relación de direcciones vigentes, con coordenadas) de http://datos.madrid.es/portal/site/egob/menuitem.c05c1f754a33a9fbe4b2e4b284f1a5a0/?vgnextoid=b3c41f3cf6a6c410VgnVCM2000000c205a0aRCRD&vgnextchannel=374512b9ace9f310VgnVCM100000171f5a0aRCRD&vgnextfmt=default
    
* Convertir datos descargados a UTF-8 (mediante recode o herramienta
  similar).

* Mediante Excel o LibreCalc, crear columnas LAT y LON a partir de
  longitud y latitud mediante las siguientes fórmulas:
  
 * Columna U (Lat):

   `=SUSTITUIR(IZQUIERDA($R2;ENCONTRAR("º";$R2)-1)+((1/60)*EXTRAEB($R2;    ENCONTRAR("º";$R2)+1;ENCONTRAR("'";$R2)-ENCONTRAR("º";$R2)-1))+    ((1/3600)*SUSTITUIR(EXTRAEB($R2;ENCONTRAR("'";$R2)+1;ENCONTRAR(" ";$R2)-ENCONTRAR("'";$R2)-3);".";","));",";".")`

 * Columna V (Lon)
  
  `=SUSTITUIR((-1) * (IZQUIERDA($S2;ENCONTRAR("º";$S2)-1)+((1/60)*EXTRAEB($S2; ENCONTRAR("º"; $S2)+1;ENCONTRAR("'";$S2)-ENCONTRAR("º";$S2)-1))+((1/3600)*SUSTITUIR(EXTRAEB($S2;ENCONTRAR("'";$S2)+1;ENCONTRAR(" ";$S2)-ENCONTRAR("'";$S2)-3);".";",")));",";".")`

* Eliminar primera fila (títulos de columna).

* Generar nuevo fichero en formato sqlite3: `sqlite3 fichero.sqlite`

* Crear tabla de callejero:  
  ```sql
  CREATE TABLE callejero (
      cod_via INTEGER, 
      clase_via TEXT, 
      particula_via TEXT, 
      nombre_via_sin_acentos TEXT, 
      nombre_via TEXT,
      clase_app TEXT,
      literal_numeracion TEXT,
      calificador TEXT,
      tipologia TEXT,
      cod_ndp INTEGER,
      cod_distrito INTEGER, 
      cod_barrio INTEGER, 
      codigo_postal INTEGER,
      utm_x_ed INTEGER,
      utm_y_ed INTEGER,
      utm_x_etrs INTEGER,
      utm_y_etrs INTEGER,
      latitud TEXT,
      longitud TEXT,
      angulo_rotulacion NUMBER(6,2),
      Lat NUMBER(10,6), 
      Lon NUMBER(10,6));
  ```
* Cargar CSV en base de datos  
  ```
  .mode csv  
  .separator ;  
  .import /ruta/a/DireccionesVigentes.csv callejero
  ```
   
* Crear índices en la base de datos

  ```sql
  create index clase_via_idx on callejero(clase_via);  
  create index nombre_via_idx on callejero(nombre_via);
  create index cod_distrito_idx on callejero(cod_distrito);
  create index cod_barrio_idx on callejero(cod_barrio);
  create index codigo_postal_idx on callejero(codigo_postal);
  ```
   
