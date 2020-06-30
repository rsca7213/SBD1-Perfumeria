/*
    Para ejecutar este archivo, abrir la consola de comandos en esta carpeta y escribir:
    psql -U username -d nombreBD -f nombreArchivo
    
    Ej.: psql -U postgres -d test -f inserts.SQL
*/


INSERT INTO RDJ_paises(id,nombre,nacionalidad) VALUES 
(1,'Venezuela','Venezolano'),
(2,'Estados Unidos','Gringo'),
(3,'Argentina','Argentino');

INSERT INTO RDJ_asociaciones(id,nombre,region,id_pais) VALUES
(1,'Fragance Creators USA','América del Norte',2);