/*
    Para ejecutar este archivo, abrir la consola de comandos en esta carpeta y escribir:
    psql -U username -d nombreBD -f nombreArchivo
    
    Ej.: psql -U postgres -d test -f creates.SQL

    Tambien se puede abrir psql y ejecutarlo con \i nombreArchivo pero es obligatorio que abras
    psql en la carpeta de los scripts
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
    CONSTRAINT nac_pais_unique UNIQUE(nacionalidad),
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

CREATE TABLE RDJ_productores (
    id NUMERIC(2,0) NOT NULL,
    nombre VARCHAR(80) NOT NULL,
    correo VARCHAR(255) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    url_web VARCHAR(255),
    id_asoc NUMERIC(2,0),
    CONSTRAINT productores_pk PRIMARY KEY (id),
    CONSTRAINT correo_productor_unique UNIQUE(correo),
    CONSTRAINT telefono_productor_unique UNIQUE(telefono),
    CONSTRAINT url_productor_unique UNIQUE(url_web)
);

CREATE TABLE RDJ_proveedores (
    id NUMERIC(2,0) NOT NULL,
    nombre VARCHAR(80) NOT NULL,
    correo VARCHAR(255) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    url_web VARCHAR(255),
    id_asoc NUMERIC(2,0),
    CONSTRAINT proveedores_pk PRIMARY KEY (id),
    CONSTRAINT correo_proveedor_unique UNIQUE(correo),
    CONSTRAINT telefono_proveedor_unique UNIQUE(telefono),
    CONSTRAINT url_proveedor_unique UNIQUE(url_web)
);

CREATE TABLE RDJ_miembros_IFRA (
    id NUMERIC(4,0) NOT NULL,
    fecha_inicio DATE NOT NULL,
    tipo CHAR(1) NOT NULL,
    id_prov NUMERIC(2,0),
    id_prod NUMERIC(2,0),
    fecha_fin DATE,
    CONSTRAINT miembros_IFRA_pk PRIMARY KEY (id),
    CONSTRAINT check_tipo_miembro CHECK (tipo in ('P','S','A'))
);

ALTER TABLE RDJ_asociaciones ADD CONSTRAINT pais_asoc_fk FOREIGN KEY (id_pais) REFERENCES RDJ_paises (id);
ALTER TABLE RDJ_productores ADD CONSTRAINT asoc_productor_fk FOREIGN KEY (id_asoc) REFERENCES RDJ_asociaciones (id);
ALTER TABLE RDJ_proveedores ADD CONSTRAINT asoc_proveedor_fk FOREIGN KEY (id_asoc) REFERENCES RDJ_asociaciones (id);
ALTER TABLE RDJ_miembros_IFRA ADD CONSTRAINT prod_membresia_fk FOREIGN KEY (id_prod) REFERENCES RDJ_productores (id);
ALTER TABLE RDJ_miembros_IFRA ADD CONSTRAINT prov_membresia_fk FOREIGN KEY (id_prov) REFERENCES RDJ_proveedores (id);