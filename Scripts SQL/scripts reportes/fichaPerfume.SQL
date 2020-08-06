SELECT pe.nombre AS nombre, CASE WHEN pe.genero = 'f' THEN 'Femenino' WHEN pe.genero = 'm' THEN 'Masculino' ELSE 'Unisex' END AS genero,
CASE WHEN pe.edad = 'in' THEN 'Infantil' WHEN pe.edad = 'jo' THEN 'Joven' WHEN pe.edad = 'ad' THEN 'Adulto' ELSE 'Atemporal' END AS edad,
CASE WHEN pe.monolitico = 'f' THEN 'Monolítico' ELSE 'Por fases' END AS tipo, pr.nombre AS productor
FROM rdj_perfumes pe, rdj_productores pr
WHERE pe.id = ? AND pe.id_productor = pr.id;

SELECT pm.pri_nombre || CASE WHEN pm.seg_nombre IS NOT NULL THEN concat(' ',pm.seg_nombre) ELSE '' END || concat(' ',pm.pri_apellido) ||
CASE WHEN pm.seg_apellido IS NOT NULL THEN concat(' ',pm.seg_apellido, ' ') ELSE '' END || concat('(',pa.nombre,')') AS perfumista
FROM rdj_perfumistas pm, rdj_paises pa, rdj_perfumes_perfumistas pp 
WHERE pa.id = pm.id_pais AND pp.id_perfume = ? AND pp.id_perfumista = pm.id ORDER BY pm.pri_nombre; 

SELECT CASE WHEN i.tipo = 'EdS' THEN 'Eau de Splash' WHEN i.tipo = 'EdC' THEN 'Eau de Cologne' WHEN i.tipo = 'EdT' THEN 'Eau de Toilette'
WHEN i.tipo = 'EdP' THEN 'Eau de Perfume' ELSE 'Perfume' END || CASE WHEN i.porcentaje IS NOT NULL THEN concat(' (',i.porcentaje,' %) ') 
ELSE ' ' END || '- ' || pr.volumen || ' ml' AS pres FROM rdj_intensidades i, rdj_presentaciones_perfumes pr WHERE i.id_perfume = 2
AND i.id = pr.id_intensidad AND pr.id_perfume = 2 ORDER BY i.tipo,pr.volumen;

SELECT fa.nombre AS familia FROM rdj_familias_olfativas fa, rdj_perfumes_familias pf WHERE pf.id_familia = fa.id AND
pf.id_perfume = ? ORDER BY fa.nombre;

($V{REPORT_COUNT}%2==0)

SELECT DISTINCT pa.palabra AS caracter FROM rdj_familias_olfativas fa, 
rdj_palabras_claves pa, rdj_perfumes pe, rdj_perfumes_familias pf,
rdj_familias_palabras fp WHERE fp.id_familia=fa.id AND pe.id=? AND
fp.id_palabra=pa.id AND pf.id_perfume=pe.id AND pf.id_familia=fa.id 
AND pa.palabra IN ('Informal','Natural','Clasico','Seductor','Moderno') ORDER BY pa.palabra;

SELECT DISTINCT pa.palabra AS pers FROM rdj_familias_olfativas fa, 
rdj_palabras_claves pa, rdj_perfumes pe, rdj_perfumes_familias pf,
rdj_familias_palabras fp WHERE fp.id_familia=fa.id AND pe.id=? AND
fp.id_palabra=pa.id AND pf.id_perfume=pe.id AND pf.id_familia=fa.id 
AND pa.palabra IN ('Libertad','Independencia','Creatividad','Diversion','Fantasia',
'Versatibilidad','Delicadeza','Sensualidad','Optimismo','Alegria','Lucidez',
'Familiaridad','Confianza','Energia') ORDER BY pa.palabra;

SELECT DISTINCT to_char(es.num_cas,'9999900-00-0') AS cas, 'Esencia de ' || lower(es.nombre) AS nombre, 
CASE WHEN es.tipo = 'n' THEN 'Esencia natural' ELSE 'Esencia sintetica' END AS tipo
FROM rdj_esencias_perfumes es, rdj_notas n, rdj_monoliticos m WHERE (n.id_perfume = 1
AND es.num_cas = n.cas_esencia) OR (m.id_perfume = 1 AND es.num_cas = m.cas_esencia)
UNION
SELECT to_char(oi.cas_otro_ing,'9999900-00-0') AS cas, oi.nombre AS nombre, 'Componente' AS tipo
FROM rdj_otros_ingredientes oi, rdj_otros_componentes oc WHERE oc.cas_otro_ing = oi.cas_otro_ing
AND oc.id_perfume = 1 ORDER BY tipo DESC, nombre;

<![CDATA[($P{id_perfume}.equals(1))? "nicole.png": ($P{id_perfume}==2)? "homem.png":($P{id_perfume}==3)? "eternal.png": 
($P{id_perfume}==4)? "flores.png":($P{id_perfume}==5)? "jazmin.png":($P{id_perfume}==6)? "champagne.png":
($P{id_perfume}==7)? "allure.png":($P{id_perfume}==8)? "rouge.png":($P{id_perfume}==9)?: "antaeus.png"]]>

SELECT es.nombre AS nota, CASE WHEN n.tipo = 's' THEN 'Salida' WHEN n.tipo = 'c' THEN 'Corazon' ELSE 'Fondo' END AS tipo
FROM rdj_notas n, rdj_esencias_perfumes es WHERE n.id_perfume = 1 AND n.cas_esencia = es.num_cas
UNION
SELECT es.nombre AS nota, 'Monolítica' AS tipo
FROM rdj_monoliticos m, rdj_esencias_perfumes es WHERE m.id_perfume = 1 AND m.cas_esencia = es.num_cas
ORDER BY tipo,nota;