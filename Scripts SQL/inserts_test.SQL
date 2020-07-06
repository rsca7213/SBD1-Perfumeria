
/*
    INSERTS de la base de datos del proyecto de perfumes
    Grupo #2
    Seccion 002
    Ricardo Salvatorelli (C.I.: 26.967.602)
    Jose Manuel Ramirez (C.I.: 26.902.002)
    David Zacarias (C.I.: 27.588.099)
*/


/* INSERT de las asociaciones utilizadas en la tabla rdj_asociaciones*/

INSERT INTO rdj_asociaciones (id,nombre,region) VALUES
(nextval('id_asoc_sec'),'AFFI - Asosiasi Flavor dan Fragran Indonesia','ap'),
(nextval('id_asoc_sec'),'International Fragance Association UK','eu'),
(nextval('id_asoc_sec'),'Fragrance Creators Association','an'),
(nextval('id_asoc_sec'),'ABIFRA - Associaçao Brasileira das Industrias de Oleos','al');

INSERT INTO rdj_paises (id,nombre,nacionalidad,id_asociacion) VALUES
(nextval('id_pais_sec'),'Indonesia','Indonesa',1),
(nextval('id_pais_sec'),'Reino Unido','Inglesa',2),
(nextval('id_pais_sec'),'Estados Unidos','Americana',3),
(nextval('id_pais_sec'),'Canada','Canadiense',3),
(nextval('id_pais_sec'),'Brasil','Brasileña',4); /* NOTA: Faltan paises por agregar */