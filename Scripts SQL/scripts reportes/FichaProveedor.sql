/*Datos de contacto e informacion IFRA del proveedor*/

SELECT p.id as idprov,p.nombre AS nombre, p.correo AS correo, p.telefono AS telefono, p.url_web AS url_web, COALESCE(a.nombre,'N/A') AS asociacion, c.nombre AS pais, to_char(m.fecha_inicio,'dd/mm/yyyy') AS fecha_inicio,COALESCE(to_char(m.fecha_fin,'dd/mm/yyyy'),'Vigente') AS fecha_fin,
CASE WHEN a.region ='eu' THEN 'Europa' WHEN a.region='al' THEN 'América Latina' WHEN a.region='an' THEN 'América del Norte' WHEN a.region='ap' THEN 'Asia Pacífico' ELSE 'N/A' END AS region  
FROM rdj_miembros_ifra as m ,rdj_paises as c,rdj_proveedores AS p left outer join rdj_asociaciones AS a ON a.id=p.id_asociacion WHERE p.id=$P{MainParameter} AND p.id_pais=c.id AND m.id_proveedor=p.id
AND
fecha_inicio= (SELECT MAX(fecha_inicio) FROM rdj_miembros_ifra WHERE id_proveedor=$P{MainParameter});

/*Metodos de pago del proveedor*/
SELECT id, CASE WHEN tipo ='c' THEN 'Completo' ELSE 'Parcial' END AS tipo, COALESCE(to_char(num_cuotas,'999'),'  N/A') AS cuotas, COALESCE(porcentaje || '%', 'N/A') AS porcentaje, COALESCE('Mes ' || meses,'N/A') AS meses
FROM rdj_metodos_pagos
WHERE id_proveedor= $P{ParametroPagos}

/*Metodos de envio del proveedor*/
SELECT e.id AS n_metodo, CASE WHEN e.tipo='m' THEN 'Maritimo - ' WHEN e.tipo = 't' THEN 'Terreste - ' WHEN e.tipo = 'a' THEN 'Aereo - ' END
|| c.nombre AS nombre, e.duracion || ' dias' AS duracion_envio, e.precio || ' $' AS precio_envio
FROM rdj_metodos_envios AS e, rdj_paises AS c, rdj_proveedores AS p
WHERE p.id=$P{ParEnv} AND c.id= e.id_pais AND p.id=e.id_proveedor ORDER BY e.id

/*Detalles de metodos de envio del proveedor*/
SELECT d.nombre AS det_nombre, d.mod_precio || ' $' AS det_precio, d.mod_duracion || ' dias' AS det_duracion
FROM rdj_metodos_envios AS e, rdj_detalles_metodos_envios AS d, rdj_proveedores AS p
WHERE p.id=$P{PIdProv} AND e.id=$P{PIdEnvio} AND p.id=e.id_proveedor AND e.id=d.id_envio

/*Clientes con contratos activos*/
SELECT prod.nombre AS productor, 'Inicial' AS tipo_contrato, to_char(cont.fecha_apertura,'dd/mm/yyyy') AS fecha_inicio , to_char(cont.fecha_apertura::DATE + INTERVAL '1 year','dd/mm/yyyy') AS fecha_final 
FROM rdj_proveedores AS prov, rdj_productores AS prod, rdj_contratos AS cont 
WHERE prov.id=$P{ParametroClientes} AND prov.id=cont.id_proveedor AND cont.cancelacion=false AND prod.id = cont.id_productor AND ((NOW() - cont.fecha_apertura) < '1 YEAR')
UNION
SELECT prod.nombre AS productor, 'Renovación' AS tipo_contrato, to_char(reno.fecha_renovacion,'dd/mm/yyyy') AS fecha_inicio , to_char(reno.fecha_renovacion::DATE + INTERVAL '1 year','dd/mm/yyyy') AS fecha_final
FROM rdj_proveedores AS prov, rdj_productores AS prod, rdj_renovaciones AS reno 
WHERE prov.id=$P{ParametroClientes} AND prov.id=reno.id_proveedor AND prod.id = reno.id_productor AND ((NOW() - reno.fecha_renovacion) < '1 YEAR') ORDER BY productor ASC

/*Catalogo de productos del proveedor*/
SELECT ing.nombre, to_char(ing.cas_ing_esencia,'9999900-00-0') AS ncas, presIng.volumen || ' ml' AS presentacion, presIng.precio || ' $' AS precio, CASE WHEN ing.naturaleza = 's' THEN 'Esencia Sintética' WHEN ing.naturaleza = 'n' THEN 'Esencia Natural' END AS tipo FROM rdj_ingredientes_esencias AS ing, rdj_presents_ings_esencias AS presIng
WHERE ing.id_proveedor=$P{ParametroPresentacion} AND presIng.cas_ing_esencia=ing.cas_ing_esencia 
UNION SELECT otro.nombre, to_char(otro.cas_otro_ing,'9999900-00-0') AS ncas, presOtro.volumen || ' ml' AS presentacion, presOtro.precio || ' $' AS precio,'Componente' AS tipo FROM rdj_otros_ingredientes AS otro, rdj_present_otros_ings AS presOtro
WHERE otro.id_proveedor=$P{ParametroPresentacion} AND presOtro.cas_otro_ing=otro.cas_otro_ing ORDER BY nombre ASC;