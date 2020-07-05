
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
(1,'AFFI - Asosiasi Flavor dan Fragran Indonesia','ap'),
(2,'International Fragance Association UK','eu'),
(3,'Fragrance Creators Association','an'),
(4,'ABIFRA - Associaçao Brasileira das Industrias de Oleos','al');

INSERT INTO rdj_paises (id,nombre,nacionalidad,id_asociacion) VALUES
(1,'Indonesia','Indonesa',1),
(2,'Reino Unido','Inglesa',2),
(3,'Estados Unidos','Americana',3),
(4,'Canada','Canadiense',3),
(5,'Brasil','Brasileña',4); /* NOTA: Faltan paises por agregar */