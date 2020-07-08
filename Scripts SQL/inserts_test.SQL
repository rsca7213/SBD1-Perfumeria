
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

INSERT INTO rdj_productores (id,nombre,correo,telefono,url_web,id_asociacion) VALUES
(nextval('id_productores_sec'),'Givaudan','sales@givaudan.com','+1 630-773-8484','www.givaudan.com',NULL),
(nextval('id_productores_sec'),'Memphis','sac@memphisbr.com','+51 3535-3500','www.memphisbr.com',4),
(nextval('id_productores_sec'),'Chanel','chanel_info@chanel.com','+1 800-550-0005','www.chanel.com/us',3);

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

INSERT INTO rdj_metodos_envios (id, id_proveedor, id_pais, duracion, precio, tipo) VALUES
(nextval('id_metodo_envio_sec'),1,2,3,150.35,'t'),
(nextval('id_metodo_envio_sec'),1,3,7,300.99,'m'),
(nextval('id_metodo_envio_sec'),1,3,2,700.75,'a'),
(nextval('id_metodo_envio_sec'),1,6,2,119.99,'t'),
(nextval('id_metodo_envio_sec'),1,7,1,45.00,'t'),
(nextval('id_metodo_envio_sec'),1,9,3,199.99,'t'),
(nextval('id_metodo_envio_sec'),2,1,5,200.50,'m'),
(nextval('id_metodo_envio_sec'),2,8,3,500.75,'a'),
(nextval('id_metodo_envio_sec'),2,4,7,800.50,'a'),
(nextval('id_metodo_envio_sec'),2,3,7,795.85,'a'),
(nextval('id_metodo_envio_sec'),3,5,28,1050.75,'m'),
(nextval('id_metodo_envio_sec'),3,10,24,900.00,'m'),
(nextval('id_metodo_envio_sec'),3,11,18,695.20,'m'),
(nextval('id_metodo_envio_sec'),3,1,7,95.10,'m'),
(nextval('id_metodo_envio_sec'),3,8,20,375.00,'t'),
(nextval('id_metodo_envio_sec'),4,1,1,25.40,'t'),
(nextval('id_metodo_envio_sec'),4,2,6,400.75,'a'),
(nextval('id_metodo_envio_sec'),4,6,5,340.15,'a'),
(nextval('id_metodo_envio_sec'),4,7,5,395.80,'a'),
(nextval('id_metodo_envio_sec'),4,9,6,415.60,'a'),
(nextval('id_metodo_envio_sec'),5,2,1,60.99,'t'),
(nextval('id_metodo_envio_sec'),5,6,3,130.70,'t'),
(nextval('id_metodo_envio_sec'),5,7,2,110.19,'t'),
(nextval('id_metodo_envio_sec'),5,9,2,190.85,'m'),
(nextval('id_metodo_envio_sec'),6,3,2,100.00,'t'),
(nextval('id_metodo_envio_sec'),6,3,1,215.99,'a'),
(nextval('id_metodo_envio_sec'),6,4,2,115.00,'t'),
(nextval('id_metodo_envio_sec'),6,5,7,250.79,'m'),
(nextval('id_metodo_envio_sec'),6,10,6,225.99,'m'),
(nextval('id_metodo_envio_sec'),6,11,10,299.99,'m');

INSERT INTO rdj_detalles_metodos_envios (id,id_envio,id_proveedor,id_pais,nombre,mod_precio,mod_duracion) VALUES
(nextval('id_det_metodoenvio_sec'),1,1,2,'Refrigeración',50,1),
(nextval('id_det_metodoenvio_sec'),1,1,2,'Mejor empaquetamiento',35,0),
(nextval('id_det_metodoenvio_sec'),2,1,3,'Seguro de envío',40,0),
(nextval('id_det_metodoenvio_sec'),3,1,3,'Seguro de envío',60,0),
(nextval('id_det_metodoenvio_sec'),4,1,6,'Refrigeración',40,1),
(nextval('id_det_metodoenvio_sec'),5,1,7,'Rush',15,-1),
(nextval('id_det_metodoenvio_sec'),6,1,9,'Rush',100,-2),
(nextval('id_det_metodoenvio_sec'),7,2,1,'Seguro de envío',20,0),
(nextval('id_det_metodoenvio_sec'),7,2,1,'Mejor empaquetamiento',10,0),
(nextval('id_det_metodoenvio_sec'),8,2,8,'Seguro de envío',20,0),
(nextval('id_det_metodoenvio_sec'),9,2,4,'Rush',200,-2),
(nextval('id_det_metodoenvio_sec'),9,2,4,'Refrigeración',80,1),
(nextval('id_det_metodoenvio_sec'),10,2,3,'Mejor empaquetamiento',15,0),
(nextval('id_det_metodoenvio_sec'),11,3,5,'No rush',-40,2),
(nextval('id_det_metodoenvio_sec'),12,3,10,'No rush',-45,3),
(nextval('id_det_metodoenvio_sec'),13,3,11,'No rush',-40,3),
(nextval('id_det_metodoenvio_sec'),14,3,1,'Refrigeración',125,1),
(nextval('id_det_metodoenvio_sec'),14,3,1,'Mejor empaquetamiento',22.5,0),
(nextval('id_det_metodoenvio_sec'),15,3,8,'Rush',150,-1),
(nextval('id_det_metodoenvio_sec'),16,4,1,'Refrigeración',30,1),
(nextval('id_det_metodoenvio_sec'),17,4,2,'Refrigeración',40,1),
(nextval('id_det_metodoenvio_sec'),18,4,6,'Refrigeración',15,1),
(nextval('id_det_metodoenvio_sec'),19,4,7,'Mejor empaquetamiento',5,0),
(nextval('id_det_metodoenvio_sec'),20,4,9,'Mejor empaquetamiento',7.5,0),
(nextval('id_det_metodoenvio_sec'),21,5,2,'No rush',-45,2),
(nextval('id_det_metodoenvio_sec'),22,5,6,'Rush',70,-1),
(nextval('id_det_metodoenvio_sec'),22,5,6,'No rush',-60,1),
(nextval('id_det_metodoenvio_sec'),23,5,7,'Seguro de envío',25,0),
(nextval('id_det_metodoenvio_sec'),24,5,9,'Seguro de envío',25,0),
(nextval('id_det_metodoenvio_sec'),25,6,3,'Refrigeración',60,0),
(nextval('id_det_metodoenvio_sec'),26,6,3,'Mejor empaquetamiento',35,0),
(nextval('id_det_metodoenvio_sec'),27,6,4,'Mejor empaquetamiento',45,0),
(nextval('id_det_metodoenvio_sec'),28,6,5,'Rush',30,-2),
(nextval('id_det_metodoenvio_sec'),29,6,10,'Rush',30,-2),
(nextval('id_det_metodoenvio_sec'),30,6,11,'Rush',35,-2);

INSERT INTO rdj_productores_paises (id_productor, id_pais) VALUES
(1,7), (1,9), (1,2), (2,5), (2,10), (2,11), (3,3), (3,4), (3,6);

INSERT INTO rdj_criterios(id,nombre,descripcion) VALUES
(nextval('id_criterio_sec'),'Región','Ubicación geográfica del proveedor'),
(nextval('id_criterio_sec'),'Pagos','Pagos que ofrece el proveedor'),
(nextval('id_criterio_sec'),'Envíos','Envíos que ofrece el proveedor'),
(nextval('id_criterio_sec'),'Cumplimiento','Porcentaje de cumplimiento de pedidos'),
(nextval('id_criterio_sec'),'Criterio de éxito','Cantidad de porcentaje necesario para aprobar la evaluación');

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
(1,1), (2,2), (2,3), (3,4), (4,5), (4,6), (4,7), (4,8), (4,9), (5,5), (5,6),
(5,7), (5,8), (5,9), (6,5), (6,6), (6,7), (6,8), (6,9), (7,10), (8,11), (9,10);

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

INSERT INTO rdj_familias_olfativas (id,nombre)VALUES
(nextval('id_familia_sec'),'Verde'),
(nextval('id_familia_sec'),'Cítrico'),
(nextval('id_familia_sec'),'Floral'),
(nextval('id_familia_sec'),'Frutal'),
(nextval('id_familia_sec'),'Aromático'),
(nextval('id_familia_sec'),'Helechos'),
(nextval('id_familia_sec'),'Chipre'),
(nextval('id_familia_sec'),'Amaderada'),
(nextval('id_familia_sec'),'Oriental'),
(nextval('id_familia_sec'),'Aldehídico');

INSERT INTO rdj_perfumes_familias (id_perfume,id_familia)VALUES
(1,1), (1,2), (2,3), (3,2), (4,2), (5,2),
(6,4), (7,3), (8,2), (8,5), (9,3), (9,6);

INSERT INTO rdj_ingredientes_esencias (cas_ing_esencia,nombre,naturaleza,id_proveedor,nombre_quim,pto_inf,solubilidad,proc_creac,parte_proc,vida_util,num_einecs,rendimiento)VALUES
(,'Ambranum','n',1,NULL,84,NULL,'Asociación equilibrada de ingredientes (extractos naturales y moléculas sintéticas), se mezclan para crear una Especialidad inspiradora.',NULL,NULL,NULL,NULL),
(68650-46-4,'Buchu Eo Aged','s',1,NULL,67,NULL,'Obtenido por destilación de la biomasa con agua, o vapor. El aceite esencial está físicamente separado de la fase acuosa por decantación.',NULL,NULL,NULL,NULL),
(71735-79-0,'Cedroxyde','s',1,'4,8,12-Trimethyl-13-oxabicyclo[10.1.0]trideca-4,8-diene',100,NULL,NULL,NULL,NULL,2759677,NULL),
(52424-60-9,'Ozaflor','s',2,NULL,100,95,NULL,NULL,180,NULL,NULL),
(4736-45-2,'Iriswood','n',2,NULL,100,95,NULL,NULL,180,28068919,NULL),
(8050-15-5,'Floralyn','n',2,NULL,100,95,NULL,NULL,365,2324762,NULL),
(28219-61-6,'Sandal Fleur','n',3,'2-Ethyl-4-(2,2,3-Trimethyl-3-Cyclopenten-1-yl)2-Buten-1-ol',100,70,NULL,NULL,730,2489088,NULL),
(127-42-4,'Methyl Ionone','s',3,'4-penten-3-one,5-(2, 6,6-Trimethyl-2-Cyclohexen-1-yl)',100,70,NULL,NULL,730,2048421,NULL),
(586-62-9,'Pine-Oil-70','s',3,'Mixture of terpene alcohols and Terpenes',77,NULL,NULL,NULL,NULL,2026806,NULL),
(89-88-3,'Vetiverol','n',4,'(5R,6R)-6,10-dimethyl-3-propan-2-ylidenespiro[4.5]dec-9-en-8-ol',100,70,NULL,NULL,NULL,2685789,NULL),
(106-24-1,'Geraniol','n',4,'(2E)-3,7-dimethylocta-2,6-dien-1-ol',108,95,NULL,NULL,NULL,2033771,NULL),
(120-11-6,'Benzyl Isoeugenol','s',4,'2-methoxy-1-phenylmethoxy-4-prop-1-enylbenzene',200,80,NULL,NULL,NULL,2043706,NULL),
(66-25-1,'C-6 Aldehyde','n',5,NULL,29,90,NULL,NULL,NULL,2006245,NULL),
(41890-92-0,'Osyrol','s',5,'7-Dimethyl-7-Methoxyoctan-2-ol',110,NULL,NULL,NULL,NULL,2555747,NULL),
(21112-37-8,'Vetimoss','s',5,'2-tert-butyl-1,4-dimethoxybenzene',93,NULL,NULL,NULL,NULL,2442165,NULL),
(106-72-9,'Melonal','s',6,'2,6-dimethylhept-5-enal',60,75,NULL,NULL,365,2034272 ,NULL),
(52424-60-9,'Citral','n',6,NULL,100,95,NULL,NULL,180,NULL,NULL);