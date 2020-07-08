
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
(nextval('id_pais_sec'),'Indonesia','Indones',1),
(nextval('id_pais_sec'),'Reino Unido','Ingles',2),
(nextval('id_pais_sec'),'Estados Unidos','Americano',3),
(nextval('id_pais_sec'),'Canada','Canadiense',3),
(nextval('id_pais_sec'),'Brasil','Brasileño',4),
(nextval('id_pais_sec'),'Suiza','Suizo',NULL),
(nextval('id_pais_sec'),'Francia','Frances',NULL),
(nextval('id_pais_sec'),'Rusia','Ruso',NULL),
(nextval('id_pais_sec'),'España','Español',NULL),
(nextval('id_pais_sec'),'Colombia','Colombiano',NULL),
(nextval('id_pais_sec'),'Argentina','Argentino',NULL); /* NOTA: Faltan paises por agregar */

INSERT INTO rdj_proveedores (id,nombre,correo,telefono,url_web,id_asociacion) VALUES
(nextval('id_proveedores_sec'),'Firmenich','contact@firmenich.com','+62 213863977','www.firmenich.com',NULL),
(nextval('id_proveedores_sec'),'Kelhar & Ko (Keva)','sprabhu.aromatics@keva.co.in','+91 22221649163','www.keva.co.in',NULL),
(nextval('id_proveedores_sec'),'Privi Organics Ltd','info@privi.co.in','+91 2233043500','www.privi.com',NULL),
(nextval('id_proveedores_sec'),'Indesso','contactus@indesso.com','+62 213863975','www.indesso.com',1),
(nextval('id_proveedores_sec'),'Emerald Perfomance Materials','kalama@emeraldmaterials.com','+1 3609547100','www.emeraldmaterials.com',2),
(nextval('id_proveedores_sec'),'Prinova','info@prinovausa.com','+1 6308680300','www.prinovausa.com',3);

INSERT INTO rdj_metodos_pagos (id,id_proveedor,tipo,num_cuotas,porcentaje,meses) VALUES
(nextval('id_metodo_pago_sec'),1,'c',NULL,NULL,NULL),
(nextval('id_metodo_pago_sec'),1,'p',2,50,2),
(nextval('id_metodo_pago_sec'),1,'p',5,20,1),
(nextval('id_metodo_pago_sec'),2,'c',NULL,NULL,NULL),
(nextval('id_metodo_pago_sec'),2,'p',10,10,1),
(nextval('id_metodo_pago_sec'),2,'p',8,12.5,2),
(nextval('id_metodo_pago_sec'),3,'c',NULL,NULL,NULL),
(nextval('id_metodo_pago_sec'),3,'p',3,33.33,3),
(nextval('id_metodo_pago_sec'),3,'p',5,20,2),
(nextval('id_metodo_pago_sec'),4,'c',NULL,NULL,NULL),
(nextval('id_metodo_pago_sec'),4,'p',2,50,1),
(nextval('id_metodo_pago_sec'),4,'p',4,25,1),
(nextval('id_metodo_pago_sec'),5,'c',NULL,NULL,NULL),
(nextval('id_metodo_pago_sec'),5,'p',5,20,2),
(nextval('id_metodo_pago_sec'),5,'p',3,33.33,4),
(nextval('id_metodo_pago_sec'),6,'c',NULL,NULL,NULL),
(nextval('id_metodo_pago_sec'),6,'p',8,12.5,1),
(nextval('id_metodo_pago_sec'),6,'p',2,50,6);

INSERT INTO rdj_productores (id,nombre,correo,telefono,url_web,id_asociacion) VALUES
(nextval('id_productores_sec'),'Givaudan','sales@givaudan.com','+1 630-773-8484','www.givaudan.com',NULL),
(nextval('id_productores_sec'),'Memphis','sac@memphisbr.com','+51 3535-3500','www.memphisbr.com',4),
(nextval('id_productores_sec'),'Chanel','chanel_info@chanel.com','+1 800-550-0005','www.chanel.com/us',3);

INSERT INTO rdj_productores_paises (id_productor, id_pais) VALUES
(1,7), (1,9), (1,2), (2,5), (2,10), (2,11), (3,3), (3,4), (3,6);

INSERT INTO rdj_miembros_ifra (id,fecha_inicio,tipo,fecha_fin,id_proveedor,id_productor) VALUES
(nextval('id_miembro_ifra_sec'),'2017-11-01','p',NULL,1,NULL),
(nextval('id_miembro_ifra_sec'),'2014-07-19','p',NULL,NULL,1),
(nextval('id_miembro_ifra_sec'),'2019-12-29','s',NULL,2,NULL),
(nextval('id_miembro_ifra_sec'),'2016-09-09','s',NULL,3,NULL),
(nextval('id_miembro_ifra_sec'),'2017-01-25','a',NULL,4,NULL),
(nextval('id_miembro_ifra_sec'),'2020-06-16','a',NULL,5,NULL),
(nextval('id_miembro_ifra_sec'),'2020-01-15','a',NULL,6,NULL),
(nextval('id_miembro_ifra_sec'),'2017-10-18','a',NULL,NULL,2),
(nextval('id_miembro_ifra_sec'),'2017-02-20','a',NULL,NULL,3);

INSERT INTO rdj_perfumistas (id,pri_nombre,pri_apellido,seg_apellido,id_pais,seg_nombre) VALUES
(nextval('id_perfumista_sec'),'Xavier','Renard','',7,NULL),
(nextval('id_perfumista_sec'),'Veronica','Kato','',5,NULL),
(nextval('id_perfumista_sec'),'John','Gamba','',3,NULL),
(nextval('id_perfumista_sec'),'Ellen','Molner','',3,NULL),
(nextval('id_perfumista_sec'),'Carlos','Lütz','Pozzer',5,NULL),
(nextval('id_perfumista_sec'),'Ilse','Kuhlmann','',5,NULL),
(nextval('id_perfumista_sec'),'Geraldo','Caruccio','',5,NULL),
(nextval('id_perfumista_sec'),'Rodolfo','Gros','',5,NULL),
(nextval('id_perfumista_sec'),'Domingos','Caruccio','',5,NULL),
(nextval('id_perfumista_sec'),'Jacques','Polge','',7,NULL),
(nextval('id_perfumista_sec'),'Ernest','Beaux','',8,NULL);

INSERT INTO rdj_perfumes (id,nombre,genero,edad,monolitico,id_productor) VALUES
(nextval('id_perfume_sec'),'Nicole Miller','u','ad',FALSE,1),
(nextval('id_perfume_sec'),'Homem Verum','m','ad',FALSE,1),
(nextval('id_perfume_sec'),'Eternal Magic','f','ad',FALSE,1),
(nextval('id_perfume_sec'),'Alma de Flores Clássico','f','ad',FALSE,2),
(nextval('id_perfume_sec'),'Essencia de Jazmin','f','ad',TRUE,2),
(nextval('id_perfume_sec'),'Essencia de Champagne','u','ad',FALSE,2),
(nextval('id_perfume_sec'),'Allure Homme Sport','m','ad',TRUE,3),
(nextval('id_perfume_sec'),'Le 1940 Rouge de Chanel','f','ad',FALSE,3),
(nextval('id_perfume_sec'),'Antaeus','m','ad',TRUE,3);

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
(nextval('id_intensidad_sec'),1,'EdP',15,NULL),
(nextval('id_intensidad_sec'),1,'EdT',10,NULL),
(nextval('id_intensidad_sec'),2,'EdP',15,NULL),
(nextval('id_intensidad_sec'),3,'EdT',10,NULL),
(nextval('id_intensidad_sec'),4,'EdC',5,NULL),
(nextval('id_intensidad_sec'),5,'EdT',10,NULL),
(nextval('id_intensidad_sec'),6,'EdT',10,NULL),
(nextval('id_intensidad_sec'),7,'EdT',10,NULL),
(nextval('id_intensidad_sec'),7,'EdP',15,NULL),
(nextval('id_intensidad_sec'),8,'P',NULL,NULL),
(nextval('id_intensidad_sec'),9,'EdT',10,NULL);

INSERT INTO rdj_presentaciones_perfumes (id,id_intensidad,id_perfume,volumen)VALUES
(nextval('id_present_perfume_sec'),1,1,100),
(nextval('id_present_perfume_sec'),2,1,100),
(nextval('id_present_perfume_sec'),2,1,125),
(nextval('id_present_perfume_sec'),3,2,100),
(nextval('id_present_perfume_sec'),4,3,50),
(nextval('id_present_perfume_sec'),5,4,120),
(nextval('id_present_perfume_sec'),6,5,160),
(nextval('id_present_perfume_sec'),7,6,160),
(nextval('id_present_perfume_sec'),8,7,50),
(nextval('id_present_perfume_sec'),8,7,100),
(nextval('id_present_perfume_sec'),8,7,150),
(nextval('id_present_perfume_sec'),9,7,50),
(nextval('id_present_perfume_sec'),9,7,100),
(nextval('id_present_perfume_sec'),10,8,100),
(nextval('id_present_perfume_sec'),11,9,50),
(nextval('id_present_perfume_sec'),11,9,100);