/*
    INSERTS de la base de datos del proyecto de perfumes
    Grupo #2
    Seccion 002
    Ricardo Salvatorelli (C.I.: 26.967.602)
    Jose Manuel Ramirez (C.I.: 26.902.002)
    David Zacarias (C.I.: 27.588.099)
*/


/* INSERT de las asociaciones utilizadas en la tabla rdj_criterios*/

INSERT INTO rdj_criterios(id,nombre,descripcion) VALUES
(nextval(id_criterio_sec),'Región','Ubicación geográfica del proveedor'),
(nextval(id_criterio_sec),'Pagos','Pagos que ofrece el proveedor'),
(nextval(id_criterio_sec),'Envíos','Envíos que ofrece el proveedor'),
(nextval(id_criterio_sec),'Cumplimiento','Porcentaje de cumplimiento de pedidos'),
(nextval(id_criterio_sec),'Criterio de éxito','Cantidad de porcentaje necesario para aprobar la evaluación');