
/* para mostrar los metodos de envio */
SELECT p.nombre AS proveedor, pa.nombre AS pais, to_char(m.duracion,'999') || ' dias' AS duracion, m.precio || ' $' AS precio, 
CASE WHEN m.tipo = 't' THEN 'terrestre' WHEN m.tipo = 'm' THEN 'maritimo' ELSE 'aereo' END AS "tipo de envio"
FROM rdj_proveedores p, rdj_paises pa, rdj_metodos_envios m WHERE p.id=m.id_proveedor AND pa.id=m.id_pais
ORDER BY p.nombre,pa.nombre;

/* para mostrar los metodos de pago */
SELECT p.nombre AS proveedor, CASE WHEN m.tipo = 'c' THEN 'completo' ELSE 'parcial' END AS "tipo de pago",
COALESCE(to_char(m.num_cuotas,'9'),'N/A') AS "numero de cuotas", COALESCE(to_char(m.porcentaje,'999'),'N/A'),
COALESCE(to_char(m.meses,'99'),'N/A') AS "pago cada x meses"  FROM rdj_metodos_pagos m, rdj_proveedores p 
WHERE p.id=m.id_proveedor ORDER BY p.nombre,m.tipo;

/* para ver proveedor con su asociacion, su pais y su membresia */
SELECT prov.nombre AS proveedor, to_char(m.fecha_inicio,'dd-mm-yyyy') AS "fecha inicio", 
COALESCE(to_char(m.fecha_fin,'dd-mm-yyyy'),'vigente') AS "fecha fin" , COALESCE(a.nombre,'N/A') AS asociacion, 
COALESCE(pa.nombre,'N/A') AS "pais asociacion" FROM rdj_miembros_ifra m, rdj_proveedores prov 
LEFT OUTER JOIN rdj_asociaciones a ON a.id=prov.id_asociacion LEFT OUTER JOIN rdj_paises pa 
ON pa.id_asociacion=a.id WHERE m.id_proveedor=prov.id ORDER BY m.fecha_inicio;

/* para ver detalles de metodos de envios */
SELECT pr.nombre AS proveedor, pa.nombre AS pais, m.id AS id_envio, det.nombre AS modificador, 
CASE WHEN m.tipo = 't' THEN 'terrestre' WHEN m.tipo = 'm' THEN 'maritimo' ELSE 'aereo' END AS "tipo de envio",
det.mod_precio || ' $' AS precio, det.mod_duracion || ' dias' AS duracion
FROM rdj_proveedores pr, rdj_paises pa, rdj_detalles_metodos_envios det, rdj_metodos_envios m
WHERE pr.id=det.id_proveedor AND pa.id=det.id_pais AND m.id=det.id_envio
ORDER BY pr.nombre,pa.nombre,m.tipo;

/* para ver la tabla esencias_perfumes y familias que tiene */
SELECT es.num_cas AS cas, es.nombre AS esencia, CASE WHEN es.tipo = 'n' THEN 'natural' ELSE 'sintetico' END AS tipo,
fa.nombre AS familia FROM rdj_esencias_perfumes es, rdj_familias_olfativas fa, rdj_esencias_perfumes_familias ef 
WHERE ef.id_familia=fa.id AND ef.cas_esencia=es.num_cas ORDER BY es.nombre;

/* para ver todas las notas por fases de los perfumes */
SELECT pe.nombre AS perfume, es.nombre AS esencia,
CASE WHEN n.tipo = 's' THEN 'salida' WHEN n.tipo = 'c' THEN 'corazon' WHEN n.tipo = 'f' THEN 'fondo' END AS tipo
FROM rdj_esencias_perfumes es, rdj_perfumes pe, rdj_notas n
WHERE pe.id=n.id_perfume AND es.num_cas=n.cas_esencia ORDER BY pe.nombre,n.tipo;

/* para ver todas las notas monoliticas de los perfumes */
SELECT pe.nombre AS perfume, es.nombre AS esencia
FROM rdj_esencias_perfumes es, rdj_perfumes pe, rdj_monoliticos m
WHERE pe.id=m.id_perfume AND es.num_cas=m.cas_esencia ORDER BY pe.nombre;

/* para ver todas las familias con sus palabras claves */
SELECT fa.nombre AS familia, pa.palabra AS palabra
FROM rdj_familias_olfativas fa, rdj_palabras_claves pa, rdj_familias_palabras fp
WHERE fp.id_familia=fa.id AND fp.id_palabra=pa.id ORDER BY fa.id;
