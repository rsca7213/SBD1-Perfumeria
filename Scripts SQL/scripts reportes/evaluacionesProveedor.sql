SELECT r.id_productor, prod.nombre, r.fecha, r.tipo, r.resultado || ' %' AS resultado, f.peso || ' %' AS exito, 
CASE WHEN r.resultado >= f.peso THEN 'Aprobado' ELSE 'Reprobado' END FROM rdj_resultados r, rdj_hist_formulas f, 
rdj_productores prod WHERE prod.id = r.id_productor AND f.id_productor = r.id_productor 
AND f.id_criterio=5 AND f.fecha_inicio <= r.fecha AND COALESCE(f.fecha_fin,NOW()) >= r.fecha 
AND r.tipo=f.tipo AND r.id_proveedor = ? AND r.fecha BETWEEN ? AND ? ORDER BY prod.nombre,r.fecha ASC;

SELECT DISTINCT t.id_productor, t.nombre FROM
(SELECT r.id_productor, prod.nombre, r.fecha, r.tipo, r.resultado || ' %' AS resultado, f.peso || ' %' AS exito, 
CASE WHEN r.resultado >= f.peso THEN 'Aprobado' ELSE 'Reprobado' END FROM rdj_resultados r, rdj_hist_formulas f, 
rdj_productores prod WHERE prod.id = r.id_productor AND f.id_productor = r.id_productor 
AND f.id_criterio=5 AND f.fecha_inicio <= r.fecha AND COALESCE(f.fecha_fin,NOW()) >= r.fecha 
AND r.tipo=f.tipo AND r.id_proveedor = 1) /* AND r.fecha BETWEEN ? AND ? ORDER BY prod.nombre,r.fecha ASC)*/ t ORDER BY nombre;
