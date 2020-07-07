
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
(1,'Indonesia','Indones',1),
(2,'Reino Unido','Ingles',2),
(3,'Estados Unidos','Americano',3),
(4,'Canada','Canadiense',3),
(5,'Brasil','Brasileño',4),
(6,'Suiza','Suizo',NULL),
(7,'Francia','Frances',NULL),
(8,'Rusia','Ruso',NULL);/* NOTA: Faltan paises por agregar */

INSERT INTO rdj_proveedores (id,nombre,correo,telefono,url_web,id_asociacion) VALUES
(1,'Firmenich','contact@firmenich.com','+62 213863977','www.firmenich.com',NULL),
(2,'Kelhar & Ko (Keva)','sprabhu.aromatics@keva.co.in','+91 22221649163','www.keva.co.in',NULL),
(3,'Privi Organics Ltd','info@privi.co.in','+91 2233043500','www.privi.com',NULL),
(4,'Indesso','contactus@indesso.com','+62 213863975','www.indesso.com',1),
(5,'Emerald Perfomance Materials','kalama@emeraldmaterials.com','+1 3609547100','emeraldmaterials.com',2),
(6,'Prinova','info@prinovausa.com','+1 6308680300','www.prinovausa.com',3);


INSERT INTO rdj_productores (id,nombre,correo,telefono,url_web,id_asociacion) VALUES
(1,'Givaudan','sales@givaudan.com','+1 630-773-8484','www.givaudan.com',NULL),
(2,'Memphis','sac@memphisbr.com','+51 3535-3500','www.memphisbr.com',4),
(3,'Chanel','chanel_info@chanel.com','+1 800-550-0005','www.chanel.com/us',3
INSERT INTO rdj_miembros_ifra (id,fecha_inicio,tipo,fecha_fin,id_proveedor,id_productor) VALUES
(1,'2017-11-01','p',NULL,1,NULL),
(2,'2014-07-19','p',NULL,NULL,1),
(3,'2019-12-29','s',NULL,2,NULL),
(4,'2016-09-09','s',NULL,3,NULL),
(5,'2017-01-25','a',NULL,4,NULL),
(6,'2020-06-16','a',NULL,5,NULL),
(7,'2020-01-15','a',NULL,6,NULL),
(8,'2017-10-18','a',NULL,NULL,2),
(9,'2017-02-20','a',NULL,NULL,3);

INSERT INTO rdj_perfumistas (id,pri_nombre,pri_apellido,seg_apellido,id_pais,seg_nombre) VALUES
(1,'Xavier','Renard','',7,NULL),
(2,'Veronica','Kato','',5,NULL),
(3,'John','Gamba','',3,NULL),
(4,'Ellen','Molner','',3,NULL),
(5,'Carlos','Lütz','Pozzer',5,NULL),
(6,'Ilse','Kuhlmann','',5,NULL),
(7,'Geraldo','Caruccio','',5,NULL),
(8,'Rodolfo','Gros','',5,NULL),
(9,'Domingos','Caruccio','',5,NULL),
(10,'Jacques','Polge','',7,NULL),
(11,'Ernest','Beaux','',8,NULL);

INSERT INTO rdj_perfumes (id,nombre,genero,edad,monolitico,id_productor) VALUES
(1,'Nicole Miller','u','ad',FALSE,1),
(2,'Homem Verum','m','ad',FALSE,1),
(3,'Eternal Magic','f','ad',FALSE,1),
(4,'Alma de Flores Clássico','f','ad',FALSE,2),
(5,'Essencia de Jazmin','f','ad',TRUE,2),
(6,'Essencia de Champagne','u','ad',FALSE,2),
(7,'Allure Homme Sport','m','ad',TRUE,3),
(8,'Le 1940 Rouge de Chanel','f','ad',FALSE,3),
(9,'Antaeus','m','ad',TRUE,3);

INSERT INTO rdj_perfumes_perfumistas (id_perfume,id_perfumista) VALUES
(1,1),
(2,2),
(2,3),
(3,4),
(4,5),
(4,6),
(4,7),
(4,8),
(4,9),
(5,5),
(5,6),
(5,7),
(5,8),
(5,9),
(6,5),
(6,6),
(6,7),
(6,8),
(6,9),
(7,10),
(8,11),
(9,10);

INSERT INTO rdj_intensidades (id,id_perfume,tipo,porcentaje,descripcion)VALUES
(1,1,'EdP',15,NULL),
(2,1,'EdT',10,NULL),
(3,2,'EdP',15,NULL),
(4,3,'EdT',10,NULL),
(5,4,'EdC',5,NULL),
(6,5,'EdT',10,NULL),
(7,6,'EdT',10,NULL),
(8,7,'EdT',10,NULL),
(9,7,'EdP',15,NULL),
(10,8,'P',NULL,NULL),
(11,9,'EdT',10,NULL);

INSERT INTO rdj_presentaciones_perfumes (id,id_perfume,id_intensidad,volumen)VALUES
(1,1,1,100),
(2,1,2,100),
(3,1,2,125),
(4,2,3,100),
(5,3,4,50),
(6,4,5,120),
(7,5,6,160),
(8,6,7,160),
(9,7,8,50),
(10,7,8,100),
(11,7,8,150),
(12,7,9,50),
(13,7,9,100),
(14,8,10,100),
(15,9,11,50),
(16,9,11,100);

