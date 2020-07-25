INSERT INTO rdj_contratos (fecha_apertura,id_proveedor,id_productor,exclusivo,cancelacion,razon_cierre) VALUES
('2020-06-15 00:00:00',1,1,'true',null,null),
('2019-09-15 00:00:00',1,2,'true',null,null),
('2019-06-07 00:00:00',2,1,'true',null,null),
('2020-02-02 00:00:00',2,2,'false',null,null),
('2019-07-21 00:00:00',3,3,'false',null,null),
('2019-03-10 00:00:00',4,1,'false',null,null),
('2016-02-15 00:00:00',4,2,'false',null,null),
('2020-07-14 00:00:00',5,1,'true',null,null),
('2020-05-10 00:00:00',5,3,'true',null,null),
('2019-02-09 00:00:00',6,1,'true',null,null),
('2020-06-01 00:00:00',6,2,'true',null,null);


INSERT INTO rdj_renovaciones (id,fecha_apertura,id_proveedor,id_productor,fecha_renovacion) VALUES
(nextval('rdj_renovacion_sec'),'2019-06-07 00:00:00',2,1,'2020-06-07 00:00:01'),
(nextval('rdj_renovacion_sec'),'2019-03-10 00:00:00',4,1,'2020-03-10 00:00:01'),
(nextval('rdj_renovacion_sec'),'2019-02-09 00:00:00',6,1,'2020-02-09 00:00:01');
    