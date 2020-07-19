INSERT INTO rdj_contratos (fecha_apertura,id_proveedor,id_productor,exclusivo,cancelacion,razon_cierre) VALUES
('2020-06-15',1,1,'true',null,null), ('2018-09-17',4,1,'true',null,null), ('2020-01-11',5,1,'false','true','F en el chat prro'),
('2017-07-21',3,2,'false',null,null), ('2019-12-24',6,2,'false',null,null);

INSERT INTO rdj_renovaciones (id,fecha_apertura,id_proveedor,id_productor,fecha_renovacion) VALUES
(nextval('rdj_renovacion_sec'),'2018-09-17',4,1,'2019-09-17'),
(nextval('rdj_renovacion_sec'),'2018-09-17',4,1,'2020-09-17'),
(nextval('rdj_renovacion_sec'),'2017-07-21',3,2,'2018-07-21'),
(nextval('rdj_renovacion_sec'),'2017-07-21',3,2,'2019-07-21');      