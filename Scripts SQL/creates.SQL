/*
    Para ejecutar este archivo, abrir la consola de comandos en esta carpeta y escribir:
    psql -U username -d nombreBD -f nombreArchivo
    
    Ej.: psql -U postgres -d test -f creates.SQL
*/

CREATE TABLE RDJ_familias_olfativas (
    id NUMERIC(2,0) NOT NULL,
    nombre varchar(20) NOT NULL,
    CONSTRAINT nombrefamilia_unique UNIQUE(nombre),
    CONSTRAINT familias_pk PRIMARY KEY (id)
);

CREATE TABLE RDJ_palabras_claves (
    id NUMERIC(2,0) NOT NULL,
    palabra varchar(20) NOT NULL,
    CONSTRAINT palabraclave_unique UNIQUE(palabra),
    CONSTRAINT palabras_pk PRIMARY KEY (id)
);

CREATE TABLE RDJ_paises (
    id NUMERIC(3,0) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    nacionalidad VARCHAR(50) NOT NULL,
    CONSTRAINT nombrepais_unique UNIQUE(nombre),
    CONSTRAINT nac_unique UNIQUE(nacionalidad),
    CONSTRAINT paises_pk PRIMARY KEY (id)
);

CREATE TABLE RDJ_asociaciones (
    id NUMERIC(2,0) NOT NULL,
    nombre VARCHAR(30) NOT NULL,
    region VARCHAR(40) NOT NULL,
    id_pais NUMERIC(3,0) NOT NULL,
    CONSTRAINT asociaciones_pk PRIMARY KEY (id),
    CONSTRAINT nombre_asoc_unique UNIQUE(nombre),
    CONSTRAINT pais_asoc_unique UNIQUE(id_pais)
);

ALTER TABLE RDJ_asociaciones ADD CONSTRAINT pais_asoc_fk FOREIGN KEY (id_pais) REFERENCES RDJ_paises (id);