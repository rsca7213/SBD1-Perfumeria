
/*
    CREATES, ALTERS, SEQUENCES e INDEXS de la base de datos del proyecto de los perfumes
    Grupo #2
    Seccion 002
    Ricardo Salvatorelli (C.I.: 26.967.602)
    Jose Manuel Ramirez (C.I.: 26.902.002)
    David Zacarias (C.I.: 27.588.099)
*/

/********************************* CREATES ******************************/

CREATE TABLE rdj_asociaciones (
    id NUMERIC(2,0) NOT NULL,
    nombre VARCHAR(300) NOT NULL UNIQUE,
    region CHAR(2) NOT NULL,
    CONSTRAINT asociaciones_pk PRIMARY KEY (id),
    CONSTRAINT region_asociacion_check CHECK(region in ('al','an','eu','ap'))
);

CREATE TABLE rdj_paises (
    id NUMERIC(3,0) NOT NULL,
    nombre VARCHAR(300) NOT NULL UNIQUE,
    nacionalidad VARCHAR(100) NOT NULL UNIQUE,
    id_asociacion NUMERIC(2,0),
    CONSTRAINT paises_pk PRIMARY KEY (id)
);

CREATE TABLE rdj_proveedores (
    id NUMERIC(2,0) NOT NULL,
    nombre VARCHAR(300) NOT NULL,
    correo VARCHAR(300) NOT NULL UNIQUE,
    telefono VARCHAR(20) NOT NULL UNIQUE,
    id_pais NUMERIC(3,0) NOT NULL,
    url_web VARCHAR(300) UNIQUE,
    id_asociacion NUMERIC(2,0),
    CONSTRAINT proveedores_pk PRIMARY KEY (id)
);

CREATE TABLE rdj_productores (
    id NUMERIC(2,0) NOT NULL,
    nombre VARCHAR(300) NOT NULL,
    correo VARCHAR(300) NOT NULL UNIQUE,
    telefono VARCHAR(20) NOT NULL UNIQUE,
    url_web VARCHAR(300) UNIQUE,
    id_asociacion NUMERIC(2,0),
    CONSTRAINT productores_pk PRIMARY KEY (id)
);

CREATE TABLE rdj_miembros_ifra (
    id NUMERIC(4,0) NOT NULL,
    fecha_inicio DATE NOT NULL,
    tipo CHAR(1) NOT NULL,
    fecha_fin DATE,
    id_proveedor NUMERIC(2,0),
    id_productor NUMERIC(2,0),
    CONSTRAINT miembros_ifra_pk PRIMARY KEY (id),
    CONSTRAINT tipo_miembro_check CHECK (tipo in ('p','s','a'))
);

CREATE TABLE rdj_metodos_pagos (
    id NUMERIC(4,0) NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    tipo CHAR(1) NOT NULL,
    num_cuotas NUMERIC(2,0),
    porcentaje NUMERIC(5,2),
    meses NUMERIC(2,0),
    CONSTRAINT metodos_pagos_pk PRIMARY KEY (id, id_proveedor),
    CONSTRAINT tipo_metodo_pago_check CHECK (tipo in ('c','p'))
);

CREATE TABLE rdj_metodos_envios (
    id NUMERIC(4,0) NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    id_pais NUMERIC(3,0) NOT NULL,
    duracion NUMERIC(3,0) NOT NULL,
    precio NUMERIC(20,2) NOT NULL,
    tipo CHAR(1) NOT NULL,
    CONSTRAINT metodos_envios_pk PRIMARY KEY (id, id_proveedor, id_pais),
    CONSTRAINT tipo_metodo_envio_check CHECK (tipo in ('t','m','a'))
);

CREATE TABLE rdj_detalles_metodos_envios (
    id NUMERIC(8,0) NOT NULL, 
    id_envio NUMERIC(4,0) NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    id_pais NUMERIC(3,0) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    mod_precio NUMERIC(20,2) NOT NULL,
    mod_duracion NUMERIC(3,0) NOT NULL,
    CONSTRAINT detalles_metodos_envios_pk PRIMARY KEY (id, id_envio, id_proveedor, id_pais)
);

CREATE TABLE rdj_productores_paises (
    id_productor NUMERIC(2,0) NOT NULL,
    id_pais NUMERIC(3,0) NOT NULL,
    CONSTRAINT productores_paises_pk PRIMARY KEY (id_productor, id_pais)
);

CREATE TABLE rdj_escalas (
    fecha_inicio TIMESTAMP NOT NULL,
    id_productor NUMERIC(2,0) NOT NULL,
    rango_inicio NUMERIC(3,0) NOT NULL,
    rango_fin NUMERIC(3,0) NOT NULL,
    fecha_fin TIMESTAMP,
    CONSTRAINT escalas_pk PRIMARY KEY (fecha_inicio, id_productor)
);

CREATE TABLE rdj_criterios (
    id NUMERIC(2,0) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion VARCHAR(1000) NOT NULL,
    CONSTRAINT criterios_pk PRIMARY KEY (id)
);

CREATE TABLE rdj_hist_formulas (
    fecha_inicio TIMESTAMP NOT NULL,
    id_criterio NUMERIC(2,0) NOT NULL,
    id_productor NUMERIC(2,0) NOT NULL,
    peso NUMERIC(5,2) NOT NULL,
    tipo CHAR(1) NOT NULL,
    fecha_fin TIMESTAMP,
    CONSTRAINT hist_formulas_pk PRIMARY KEY (fecha_inicio, id_criterio, id_productor),
    CONSTRAINT tipo_formula_check CHECK (tipo in ('i','a'))
);

CREATE TABLE rdj_resultados (
    fecha TIMESTAMP NOT NULL,
    id_productor NUMERIC(2,0) NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    resultado NUMERIC(5,2) NOT NULL,
    tipo CHAR(1) NOT NULL,
    CONSTRAINT resultados_pk PRIMARY KEY(fecha, id_productor, id_proveedor),
    CONSTRAINT tipo_resultado_check CHECK (tipo in ('i','a'))
);

CREATE TABLE rdj_sustancias_prohibidas (
    num_cas NUMERIC(10,0) NOT NULL,
    nombre VARCHAR(300) NOT NULL,
    CONSTRAINT sustancias_prohibidas_pk PRIMARY KEY (num_cas) 
);

CREATE TABLE rdj_perfumistas (
    id NUMERIC(4,0) NOT NULL,
    pri_nombre VARCHAR(50) NOT NULL,
    pri_apellido VARCHAR(50) NOT NULL,
    seg_apellido VARCHAR(50) NOT NULL,
    id_pais NUMERIC(3,0) NOT NULL,
    seg_nombre VARCHAR(50),
    CONSTRAINT perfumistas_pk PRIMARY KEY (id)
);

CREATE TABLE rdj_perfumes (
    id NUMERIC(4,0) NOT NULL,
    nombre VARCHAR(300) NOT NULL,
    genero CHAR(1) NOT NULL,
    edad CHAR(2) NOT NULL,
    monolitico BOOLEAN NOT NULL,
    id_productor NUMERIC(2,0) NOT NULL,
    CONSTRAINT perfumes_pk PRIMARY KEY (id),
    CONSTRAINT genero_perfume_check CHECK (genero in ('m','f','u')),
    CONSTRAINT edad_perfume_check CHECK (edad in ('in','jo','ad','at'))
);

CREATE TABLE rdj_perfumes_perfumistas (
    id_perfume NUMERIC(4,0) NOT NULL,
    id_perfumista NUMERIC(4,0) NOT NULL,
    CONSTRAINT perfumes_perfumistas_pk PRIMARY KEY (id_perfume, id_perfumista)
);

CREATE TABLE rdj_intensidades (
    id NUMERIC(5,0) NOT NULL,
    id_perfume NUMERIC(4,0) NOT NULL,
    tipo CHAR(3) NOT NULL,
    porcentaje NUMERIC(5,2),
    descripcion VARCHAR(1000),
    CONSTRAINT intensidades_pk PRIMARY KEY (id, id_perfume),
    CONSTRAINT tipo_intensidad_check CHECK (tipo in ('P','EdP','EdC','EdT','EdS'))
);

CREATE TABLE rdj_presentaciones_perfumes (
    id NUMERIC(6,0) NOT NULL,
    id_perfume NUMERIC(4,0) NOT NULL,
    id_intensidad NUMERIC(5,0) NOT NULL,
    volumen NUMERIC(6,0) NOT NULL,
    CONSTRAINT presentaciones_perfumes_pk PRIMARY KEY (id, id_perfume, id_intensidad)
);

CREATE TABLE rdj_esencias_perfumes (
    num_cas NUMERIC(10,0) NOT NULL,
    nombre VARCHAR(300) NOT NULL,
    tipo CHAR(1) NOT NULL,
    descripcion VARCHAR(1000) NOT NULL,
    CONSTRAINT esencias_perfumes_pk PRIMARY KEY (num_cas),
    CONSTRAINT tipo_esencia_perfume_check CHECK (tipo in ('n','s'))
);

CREATE TABLE rdj_monoliticos (
    id_perfume NUMERIC(4,0) NOT NULL,
    cas_esencia NUMERIC(10,0) NOT NULL,
    CONSTRAINT monoliticos_pk PRIMARY KEY (id_perfume, cas_esencia)
);

CREATE TABLE rdj_notas (
    id_perfume NUMERIC(4,0) NOT NULL,
    cas_esencia NUMERIC(10,0) NOT NULL,
    tipo CHAR(1) NOT NULL,
    CONSTRAINT notas_pk PRIMARY KEY (id_perfume, cas_esencia),
    CONSTRAINT tipo_nota_check CHECK (tipo in ('s','c','f'))
);

CREATE TABLE rdj_familias_olfativas (
    id NUMERIC(2,0) NOT NULL,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    CONSTRAINT familias_olfativas_pk PRIMARY KEY (id)
);

CREATE TABLE rdj_esencias_perfumes_familias (
    cas_esencia NUMERIC(10,0) NOT NULL,
    id_familia NUMERIC(2,0) NOT NULL,
    CONSTRAINT esencias_perfumes_familias_pk PRIMARY KEY (cas_esencia, id_familia)
);

CREATE TABLE rdj_perfumes_familias (
    id_perfume NUMERIC(4,0) NOT NULL,
    id_familia NUMERIC(2,0) NOT NULL,
    CONSTRAINT perfumes_familias_pk PRIMARY KEY (id_perfume, id_familia)
);

CREATE TABLE rdj_palabras_claves (
    id NUMERIC(4,0) NOT NULL,
    palabra VARCHAR(100) NOT NULL UNIQUE,
    CONSTRAINT palabras_claves_pk PRIMARY KEY (id)
);

CREATE TABLE rdj_familias_palabras (
    id_palabra NUMERIC(4,0) NOT NULL,
    id_familia NUMERIC(2,0) NOT NULL,
    CONSTRAINT familias_palabras_pk PRIMARY KEY (id_palabra, id_familia)
);

CREATE TABLE rdj_otros_ingredientes (
    cas_otro_ing NUMERIC(10,0) NOT NULL,
    nombre VARCHAR(300) NOT NULL,
    descripcion VARCHAR(1000) NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    CONSTRAINT otros_ingredientes_pk PRIMARY KEY (cas_otro_ing)
);

CREATE TABLE rdj_present_otros_ings (
    id NUMERIC(5,0) NOT NULL,
    cas_otro_ing NUMERIC(10,0) NOT NULL,
    volumen NUMERIC(6,0) NOT NULL,
    precio NUMERIC(20,2) NOT NULL,
    CONSTRAINT present_otros_ing_pk PRIMARY KEY (id, cas_otro_ing)
);

CREATE TABLE rdj_otros_componentes (
    id_perfume NUMERIC(4,0) NOT NULL,
    cas_otro_ing NUMERIC(10,0) NOT NULL,
    CONSTRAINT otros_componentes_pk PRIMARY KEY (id_perfume, cas_otro_ing)
);

CREATE TABLE rdj_ingredientes_esencias (
    cas_ing_esencia NUMERIC(10,0) NOT NULL,
    nombre VARCHAR(300) NOT NULL,
    naturaleza CHAR(1) NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    nombre_quim VARCHAR(300),
    pto_inf NUMERIC(3,0),
    solubilidad NUMERIC(5,2),
    proc_creac VARCHAR(1000),
    parte_proc VARCHAR(300),
    vida_util NUMERIC(3,0),
    num_einecs NUMERIC(12,0) UNIQUE,
    rendimiento NUMERIC(2,0),
    CONSTRAINT ingredientes_esencias_pk PRIMARY KEY (cas_ing_esencia),
    CONSTRAINT nat_ing_esencia_check CHECK (naturaleza in ('n','s'))
);

CREATE TABLE rdj_ings_esencias_paises (
    id_pais NUMERIC(3,0) NOT NULL,
    cas_ing_esencia NUMERIC(10,0) NOT NULL,
    CONSTRAINT ings_esencias_paises_pk PRIMARY KEY (id_pais, cas_ing_esencia)
);

CREATE TABLE rdj_presents_ings_esencias (
    id NUMERIC(5,0) NOT NULL,
    cas_ing_esencia NUMERIC(10,0) NOT NULL,
    volumen NUMERIC(6,0) NOT NULL,
    precio NUMERIC(20,2) NOT NULL,
    CONSTRAINT presents_ings_esencias_pk PRIMARY KEY (id, cas_ing_esencia)
);

CREATE TABLE rdj_ings_esencias_otros_ings (
    cas_otro_ing NUMERIC(10,0) NOT NULL,
    cas_ing_esencia NUMERIC(10,0) NOT NULL,
    CONSTRAINT ings_esencias_otros_ings_pk PRIMARY KEY (cas_ing_esencia, cas_otro_ing)
);

CREATE TABLE rdj_ings_esencias_familias (
    id_familia NUMERIC(2,0) NOT NULL,
    cas_ing_esencia NUMERIC(10,0) NOT NULL,
    CONSTRAINT ings_esencias_familias_pk PRIMARY KEY (id_familia, cas_ing_esencia)
);

CREATE TABLE rdj_contratos (
    fecha_apertura TIMESTAMP NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    id_productor NUMERIC(2,0) NOT NULL,
    exclusivo BOOLEAN NOT NULL,
    cancelacion BOOLEAN,
    razon_cierre VARCHAR(1000),
    CONSTRAINT contratos_pk PRIMARY KEY (fecha_apertura, id_proveedor, id_productor)
);

CREATE TABLE rdj_renovaciones (
    id NUMERIC(5,0) NOT NULL,
    fecha_apertura TIMESTAMP NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    id_productor NUMERIC(2,0) NOT NULL,
    fecha_renovacion TIMESTAMP NOT NULL,
    CONSTRAINT renovaciones_pk PRIMARY KEY (id, fecha_apertura, id_proveedor, id_productor)
);

CREATE TABLE rdj_detalles_contratos (
    id NUMERIC(8,0) NOT NULL,
    fecha_apertura TIMESTAMP NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    id_productor NUMERIC(2,0) NOT NULL,
    precio NUMERIC(20,2),
    descuento NUMERIC(5,2),
    cas_otro_ing NUMERIC(10,0),
    cas_ing_esencia NUMERIC(10,0),
    CONSTRAINT detalles_contratos_pk PRIMARY KEY (id, fecha_apertura, id_proveedor, id_productor)
);

CREATE TABLE rdj_metodos_contratos (
    id NUMERIC(6,0) NOT NULL,
    fecha_cont TIMESTAMP NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    id_productor NUMERIC(2,0) NOT NULL,
    id_pago NUMERIC(4,0),
    id_prov_pago NUMERIC(2,0),
    id_envio NUMERIC(4,0),
    id_prov_envio NUMERIC(2,0),
    id_pais_envio NUMERIC(3,0),
    CONSTRAINT metodos_contratos_pk PRIMARY KEY (id, fecha_cont, id_proveedor, id_productor)
);

CREATE TABLE rdj_pedidos (
    num_pedido NUMERIC(8,0) NOT NULL,
    fecha_pedido DATE NOT NULL,
    estatus CHAR(5) NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    id_productor NUMERIC(2,0) NOT NULL,
    fecha_envio DATE,
    factura NUMERIC(8,0),
    monto NUMERIC(20,2),
    id_envio NUMERIC(8,0),
    fecha_ap_envio DATE,
    id_prod_envio NUMERIC(2,0),
    id_prov_envio NUMERIC(2,0),
    id_pago NUMERIC(8,0),
    fecha_ap_pago DATE,
    id_prod_pago NUMERIC(2,0),
    id_prov_pago NUMERIC(2,0),
    CONSTRAINT pedidos_pk PRIMARY KEY (num_pedido),
    CONSTRAINT estatus_pedido_check CHECK (estatus in ('p','e','cprod','cprov'))
);

CREATE TABLE rdj_detalles_pedidos (
    id NUMERIC(8,0) NOT NULL,
    num_pedido NUMERIC(8,0) NOT NULL,
    cantidad NUMERIC(4,0) NOT NULL,
    id_pres_esencia NUMERIC(5,0),
    cas_esencia NUMERIC(10,0),
    id_pres_otro NUMERIC(5,0),
    cas_otro NUMERIC(10,0),
    CONSTRAINT detalles_pedidos_pk PRIMARY KEY (id, num_pedido)
);

CREATE TABLE rdj_pagos (
    num_pago NUMERIC(8,0) NOT NULL,
    num_pedido NUMERIC(8,0) NOT NULL,
    fecha_pago DATE NOT NULL,
    monto NUMERIC(20,2) NOT NULL,
    CONSTRAINT pagos_pk PRIMARY KEY (num_pago, num_pedido)
);


/***************************** ALTERS ****************************/

/*ALTERS de la tabla rdj_paises*/

ALTER TABLE rdj_paises ADD CONSTRAINT pais_asociacion_fk 
FOREIGN KEY (id_asociacion) 
REFERENCES rdj_asociaciones (id);

/*ALTERS de la tabla rdj_proveedores*/

ALTER TABLE rdj_proveedores ADD CONSTRAINT proveedor_asociacion_fk 
FOREIGN KEY (id_asociacion) 
REFERENCES rdj_asociaciones (id);

ALTER TABLE rdj_proveedores ADD CONSTRAINT proveedor_pais_fk
FOREIGN KEY (id_pais)
REFERENCES rdj_paises (id);

/*ALTERS de la tabla rdj_productores*/

ALTER TABLE rdj_productores ADD CONSTRAINT productor_asociacion_fk 
FOREIGN KEY (id_asociacion) 
REFERENCES rdj_asociaciones (id);

/*ALTERS de la tabla rdj_miembros_ifra*/

ALTER TABLE rdj_miembros_ifra ADD CONSTRAINT miembro_ifra_proveedor_fk 
FOREIGN KEY (id_proveedor) 
REFERENCES rdj_proveedores (id);

ALTER TABLE rdj_miembros_ifra ADD CONSTRAINT miembro_ifra_id_productor_fk 
FOREIGN KEY (id_productor) 
REFERENCES rdj_productores (id);

/*ALTERS de la tabla rdj_metodos_pagos*/

ALTER TABLE rdj_metodos_pagos ADD CONSTRAINT metodo_pago_proveedor_fk 
FOREIGN KEY (id_proveedor) 
REFERENCES rdj_proveedores (id);

/*ALTERS de la tabla rdj_metodos_envios*/

ALTER TABLE rdj_metodos_envios ADD CONSTRAINT metodo_envio_proveedor_fk 
FOREIGN KEY (id_proveedor) 
REFERENCES rdj_proveedores (id);

ALTER TABLE rdj_metodos_envios ADD CONSTRAINT metodo_envio_pais_fk 
FOREIGN KEY (id_pais) 
REFERENCES rdj_paises (id);

/*ALTERS de la tabla rdj_detalles_metodos_envios*/

ALTER TABLE rdj_detalles_metodos_envios ADD CONSTRAINT detalle_metodo_envio_envio_fk 
FOREIGN KEY (id_envio,id_proveedor,id_pais) 
REFERENCES rdj_metodos_envios (id,id_proveedor,id_pais);

/*ALTERS de la tabla rdj_productores_paises*/

ALTER TABLE rdj_productores_paises ADD CONSTRAINT productor_pais_pais_fk 
FOREIGN KEY (id_pais) 
REFERENCES rdj_paises (id);

ALTER TABLE rdj_productores_paises ADD CONSTRAINT productor_pais_productor_fk 
FOREIGN KEY (id_productor) 
REFERENCES rdj_productores (id);

/*ALTERS de la tabla rdj_escalas*/

ALTER TABLE rdj_escalas ADD CONSTRAINT escala_productor_fk 
FOREIGN KEY (id_productor) 
REFERENCES rdj_productores (id);

/*ALTERS de la tabla rdj_hist_formulas*/

ALTER TABLE rdj_hist_formulas ADD CONSTRAINT hist_formula_criterio_fk 
FOREIGN KEY (id_criterio) 
REFERENCES rdj_criterios (id);

ALTER TABLE rdj_hist_formulas ADD CONSTRAINT hist_formula_productor_fk 
FOREIGN KEY (id_productor) 
REFERENCES rdj_productores (id);

/*ALTERS de la tabla rdj_resultados*/

ALTER TABLE rdj_resultados ADD CONSTRAINT resultado_productor_fk 
FOREIGN KEY (id_productor) 
REFERENCES rdj_productores (id);

ALTER TABLE rdj_resultados ADD CONSTRAINT resultado_proveedor_fk 
FOREIGN KEY (id_proveedor) 
REFERENCES rdj_proveedores (id);

/*ALTERS de la tabla rdj_perfumistas*/

ALTER TABLE rdj_perfumistas ADD CONSTRAINT perfumistas_pais_fk 
FOREIGN KEY (id_pais) 
REFERENCES rdj_paises (id);

/*ALTERS de la tabla rdj_perfumes*/

ALTER TABLE rdj_perfumes ADD CONSTRAINT perfume_productor_fk 
FOREIGN KEY (id_productor) 
REFERENCES rdj_productores (id);

/*ALTERS de la tabla rdj_perfumes_perfumistas*/

ALTER TABLE rdj_perfumes_perfumistas ADD CONSTRAINT perfume_perfumista_perfume_fk 
FOREIGN KEY (id_perfume) 
REFERENCES rdj_perfumes (id);

ALTER TABLE rdj_perfumes_perfumistas ADD CONSTRAINT perf_perfumista_perfumista_fk 
FOREIGN KEY (id_perfumista) 
REFERENCES rdj_perfumistas (id);

/*ALTERS de la tabla rdj_intensidades*/

ALTER TABLE rdj_intensidades ADD CONSTRAINT intensidad_perfume_fk 
FOREIGN KEY (id_perfume) 
REFERENCES rdj_perfumes (id);

/*ALTERS de la tabla rdj_presentaciones_perfumes*/

ALTER TABLE rdj_presentaciones_perfumes ADD CONSTRAINT present_perfume_perfume_fk 
FOREIGN KEY (id_perfume,id_intensidad) 
REFERENCES rdj_intensidades (id_perfume,id);

/*ALTERS de la tabla rdj_monoliticos*/

ALTER TABLE rdj_monoliticos ADD CONSTRAINT monolitico_perfume_fk 
FOREIGN KEY (id_perfume) 
REFERENCES rdj_perfumes (id);

ALTER TABLE rdj_monoliticos ADD CONSTRAINT monolitico_esencia_perfume_fk 
FOREIGN KEY (cas_esencia) 
REFERENCES rdj_esencias_perfumes (num_cas);

/*ALTERS de la tabla rdj_notas*/

ALTER TABLE rdj_notas ADD CONSTRAINT nota_perfume_fk 
FOREIGN KEY (id_perfume) 
REFERENCES rdj_perfumes (id);

ALTER TABLE rdj_notas ADD CONSTRAINT nota_esencia_perfume_fk 
FOREIGN KEY (cas_esencia) 
REFERENCES rdj_esencias_perfumes (num_cas);

/*ALTERS de la tabla rdj_esencias_perfumes_familias*/

ALTER TABLE rdj_esencias_perfumes_familias ADD CONSTRAINT esencia_perf_fam_esencia_fk 
FOREIGN KEY (cas_esencia) 
REFERENCES rdj_esencias_perfumes (num_cas);

ALTER TABLE rdj_esencias_perfumes_familias ADD CONSTRAINT esencia_perf_fam_familia_fk 
FOREIGN KEY (id_familia) 
REFERENCES rdj_familias_olfativas (id);

/*ALTERS de la tabla rdj_perfumes_familias*/

ALTER TABLE rdj_perfumes_familias ADD CONSTRAINT perfume_familia_familia_fk 
FOREIGN KEY (id_familia) 
REFERENCES rdj_familias_olfativas (id);

ALTER TABLE rdj_perfumes_familias ADD CONSTRAINT perfume_familia_perfume_fk 
FOREIGN KEY (id_perfume) 
REFERENCES rdj_perfumes (id);

/*ALTERS de la tabla rdj_familias_palabras*/

ALTER TABLE rdj_familias_palabras ADD CONSTRAINT familia_palabra_palabra_fk 
FOREIGN KEY (id_palabra) 
REFERENCES rdj_palabras_claves (id);

ALTER TABLE rdj_familias_palabras ADD CONSTRAINT familia_palabra_familia_fk 
FOREIGN KEY (id_familia) 
REFERENCES rdj_familias_olfativas (id);

/*ALTERS de la tabla rdj_otros_ingredientes*/

ALTER TABLE rdj_otros_ingredientes ADD CONSTRAINT otro_ingrediente_proveedor_fk 
FOREIGN KEY (id_proveedor) 
REFERENCES rdj_proveedores (id);

/*ALTERS de la tabla rdj_present_otros_ings*/

ALTER TABLE rdj_present_otros_ings ADD CONSTRAINT present_otro_ing_otro_ing_fk 
FOREIGN KEY (cas_otro_ing) 
REFERENCES rdj_otros_ingredientes (cas_otro_ing);

/*ALTERS de la tabla rdj_otros_componentes*/

ALTER TABLE rdj_otros_componentes ADD CONSTRAINT otro_componente_otro_ing_fk 
FOREIGN KEY (cas_otro_ing) 
REFERENCES rdj_otros_ingredientes (cas_otro_ing);

ALTER TABLE rdj_otros_componentes ADD CONSTRAINT otro_componente_perfume_fk 
FOREIGN KEY (id_perfume) 
REFERENCES rdj_perfumes (id);

/*ALTERS de la tabla rdj_ingredientes_esencias*/

ALTER TABLE rdj_ingredientes_esencias ADD CONSTRAINT ing_esencia_proveedor_fk 
FOREIGN KEY (id_proveedor) 
REFERENCES rdj_proveedores (id);

/*ALTERS de la tabla rdj_ings_esencias_paises*/

ALTER TABLE rdj_ings_esencias_paises ADD CONSTRAINT ing_esencia_pais_pais_fk 
FOREIGN KEY (id_pais) 
REFERENCES rdj_paises (id);

ALTER TABLE rdj_ings_esencias_paises ADD CONSTRAINT ing_esenc_pais_ing_esencia_fk
FOREIGN KEY (cas_ing_esencia) 
REFERENCES rdj_ingredientes_esencias (cas_ing_esencia);

/*ALTERS de la tabla rdj_presents_ings_esencias*/

ALTER TABLE rdj_presents_ings_esencias ADD CONSTRAINT present_ing_esenc_ing_esenc_fk 
FOREIGN KEY (cas_ing_esencia) 
REFERENCES rdj_ingredientes_esencias (cas_ing_esencia);

/*ALTERS de la tabla rdj_ings_esencias_otros_ings*/

ALTER TABLE rdj_ings_esencias_otros_ings ADD CONSTRAINT ing_esen_otro_ing_ing_esen_fk 
FOREIGN KEY (cas_ing_esencia) 
REFERENCES rdj_ingredientes_esencias (cas_ing_esencia);

ALTER TABLE rdj_ings_esencias_otros_ings ADD CONSTRAINT ing_esen_otro_ing_otro_ing_fk 
FOREIGN KEY (cas_otro_ing) 
REFERENCES rdj_otros_ingredientes (cas_otro_ing);

/*ALTERS de la tabla rdj_ings_esencias_familias*/

ALTER TABLE rdj_ings_esencias_familias ADD CONSTRAINT ing_esenc_famili_ing_esenc_fk 
FOREIGN KEY (cas_ing_esencia) 
REFERENCES rdj_ingredientes_esencias (cas_ing_esencia);

ALTER TABLE rdj_ings_esencias_familias ADD CONSTRAINT ing_esenc_familia_familia_fk 
FOREIGN KEY (id_familia) 
REFERENCES rdj_familias_olfativas (id);

/*ALTERS de la tabla rdj_contratos*/

ALTER TABLE rdj_contratos ADD CONSTRAINT contrato_proveedor_fk 
FOREIGN KEY (id_proveedor) 
REFERENCES rdj_proveedores (id);

ALTER TABLE rdj_contratos ADD CONSTRAINT contrato_productor_fk 
FOREIGN KEY (id_productor) 
REFERENCES rdj_productores (id);

/*ALTERS de la tabla rdj_renovaciones*/

ALTER TABLE rdj_renovaciones ADD CONSTRAINT renovacion_contrato_fk 
FOREIGN KEY (fecha_apertura,id_proveedor,id_productor) 
REFERENCES rdj_contratos (fecha_apertura,id_proveedor,id_productor);

/*ALTERS de la tabla rdj_detalles_contratos*/

ALTER TABLE rdj_detalles_contratos ADD CONSTRAINT det_contrato_contrato_fk 
FOREIGN KEY (fecha_apertura,id_proveedor,id_productor) 
REFERENCES rdj_contratos (fecha_apertura,id_proveedor,id_productor);

ALTER TABLE rdj_detalles_contratos ADD CONSTRAINT det_contrato_otro_ing_fk 
FOREIGN KEY (cas_otro_ing) 
REFERENCES rdj_otros_ingredientes (cas_otro_ing);

ALTER TABLE rdj_detalles_contratos ADD CONSTRAINT det_contrato_ing_esenc_fk 
FOREIGN KEY (cas_ing_esencia) 
REFERENCES rdj_ingredientes_esencias (cas_ing_esencia);

/*ALTERS de la tabla rdj_metodos_contratos*/

ALTER TABLE rdj_metodos_contratos ADD CONSTRAINT metodo_contrato_contrato_fk 
FOREIGN KEY (fecha_cont,id_proveedor,id_productor) 
REFERENCES rdj_contratos (fecha_apertura,id_proveedor,id_productor);

ALTER TABLE rdj_metodos_contratos ADD CONSTRAINT met_contrato_met_pago_fk 
FOREIGN KEY (id_pago,id_prov_pago) 
REFERENCES rdj_metodos_pagos (id,id_proveedor);

ALTER TABLE rdj_metodos_contratos ADD CONSTRAINT met_contrato_met_envio_fk 
FOREIGN KEY (id_envio,id_prov_envio,id_pais_envio) 
REFERENCES rdj_metodos_envios (id,id_proveedor,id_pais);

/*ALTERS de la tabla rdj_pedidos*/

ALTER TABLE rdj_pedidos ADD CONSTRAINT pedido_proveedor_fk 
FOREIGN KEY (id_proveedor) 
REFERENCES rdj_proveedores (id);

ALTER TABLE rdj_pedidos ADD CONSTRAINT pedido_productor_fk 
FOREIGN KEY (id_productor) 
REFERENCES rdj_productores (id);

ALTER TABLE rdj_pedidos ADD CONSTRAINT pedido_metco_envio_fk 
FOREIGN KEY (id_envio,fecha_ap_envio,id_prov_envio,id_prod_envio) 
REFERENCES rdj_metodos_contratos (id,fecha_cont,id_proveedor,id_productor);

ALTER TABLE rdj_pedidos ADD CONSTRAINT pedido_metco_pago_fk 
FOREIGN KEY (id_pago,fecha_ap_pago,id_prov_pago,id_prod_pago) 
REFERENCES rdj_metodos_contratos (id,fecha_cont,id_proveedor,id_productor);

/*ALTERS de la tabla rdj_detalles_pedidos*/

ALTER TABLE rdj_detalles_pedidos ADD CONSTRAINT detalle_pedido_pedido_fk 
FOREIGN KEY (num_pedido) 
REFERENCES rdj_pedidos (num_pedido);

ALTER TABLE rdj_detalles_pedidos ADD CONSTRAINT det_ped_present_ing_esen_fk 
FOREIGN KEY (id_pres_esencia,cas_esencia) 
REFERENCES rdj_presents_ings_esencias (id,cas_ing_esencia);

ALTER TABLE rdj_detalles_pedidos ADD CONSTRAINT det_ped_present_otro_ing_fk 
FOREIGN KEY (id_pres_otro,cas_otro) 
REFERENCES rdj_present_otros_ings (id,cas_otro_ing);

/*ALTERS de la tabla rdj_pagos*/
ALTER TABLE rdj_pagos ADD CONSTRAINT pago_pedido_fk 
FOREIGN KEY (num_pedido) 
REFERENCES rdj_pedidos (num_pedido);


/*************************** SECUENCIAS ***************************/

/* tabla rdj_asociaciones */
CREATE SEQUENCE rdj_asoc_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99;

/* tabla rdj_paises */
CREATE SEQUENCE rdj_pais_sec INCREMENT BY 1 START WITH 1 MAXVALUE 999;

/* tabla rdj_proveedores */
CREATE SEQUENCE rdj_proveedores_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99;

/* tabla rdj_productores */
CREATE SEQUENCE rdj_productores_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99;

/* tabla rdj_miembros_ifra */
CREATE SEQUENCE rdj_miembro_ifra_sec INCREMENT BY 1 START WITH 1 MAXVALUE 9999;

/* tabla rdj_metodos_pagos */
CREATE SEQUENCE rdj_metodo_pago_sec INCREMENT BY 1 START WITH 1 MAXVALUE 9999;

/* tabla rdj_metodos_envios */
CREATE SEQUENCE rdj_metodo_envio_sec INCREMENT BY 1 START WITH 1 MAXVALUE 9999;

/* tabla rdj_detalles_metodos_envios */
CREATE SEQUENCE rdj_det_metodoenvio_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999999;

/* tabla rdj_criterios */
CREATE SEQUENCE rdj_criterio_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99;

/* tabla rdj_perfumistas */
CREATE SEQUENCE rdj_perfumista_sec INCREMENT BY 1 START WITH 1 MAXVALUE 9999;

/* tabla rdj_perfumes */
CREATE SEQUENCE rdj_perfume_sec INCREMENT BY 1 START WITH 1 MAXVALUE 9999;

/* tabla rdj_intensidades */
CREATE SEQUENCE rdj_intensidad_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999;

/* tabla rdj_presentaciones_perfumes */
CREATE SEQUENCE rdj_present_perfume_sec INCREMENT BY 1 START WITH 1 MAXVALUE 999999;

/* tabla rdj_familias_olfativas */
CREATE SEQUENCE rdj_familia_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99;

/* tabla rdj_palabras_claves */
CREATE SEQUENCE rdj_palabra_sec INCREMENT BY 1 START WITH 1 MAXVALUE 9999;

/* tabla rdj_presents_otros_ings */
CREATE SEQUENCE rdj_present_otro_ing_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999;

/* tabla rdj_presents_ings_esencias */
CREATE SEQUENCE rdj_present_ing_esen_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999;

/* tabla rdj_renovacines */
CREATE SEQUENCE rdj_renovacion_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999;

/* tabla rdj_detalles_contratos */
CREATE SEQUENCE rdj_det_contrato_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999999;

/* tabla rdj_metodos_contratos */
CREATE SEQUENCE rdj_metodo_contrato_sec INCREMENT BY 1 START WITH 1 MAXVALUE 999999;

/* tabla rdj_pedidos */
CREATE SEQUENCE rdj_pedido_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999999;

/* tabla rdj_detalles_pedidos */
CREATE SEQUENCE rdj_det_pedido_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999999;

/* tabla rdj_pagos */
CREATE SEQUENCE rdj_pago_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999999;



/*********************************** INDICES ***********************************/

/* tabla rdj_paises */
CREATE INDEX rdj_asoc_pais_ind ON rdj_paises (id_asociacion);

/* tabla rdj_proveedores */
CREATE INDEX rdj_asoc_prov_ind ON rdj_proveedores (id_asociacion);

/* tabla rdj_productores */
CREATE INDEX rdj_asoc_prod_ind ON rdj_productores (id_asociacion);

/* tabla rdj_miembros_ifra */
CREATE INDEX rdj_prov_membresia_ind ON rdj_miembros_ifra (id_proveedor);
CREATE INDEX rdj_prod_membresia_ind ON rdj_miembros_ifra (id_productor);

/* tabla rdj_perfumistas */
CREATE INDEX rdj_pais_perfumista_ind ON rdj_perfumistas (id_pais);

/* tabla rdj_perfumes */
CREATE INDEX rdj_prod_perfume_ind ON rdj_perfumes (id_productor);

/* tabla rdj_otros_ingredientes */
CREATE INDEX rdj_prov_otroing_ind ON rdj_otros_ingredientes (id_proveedor);

/* tabla rdj_ingredientes_esencias */
CREATE INDEX rdj_prov_ingesen_ind ON rdj_ingredientes_esencias (id_proveedor);

/* tabla rdj_detalles_contratos */
CREATE INDEX rdj_cas_otro_ind ON rdj_detalles_contratos (cas_otro_ing);
CREATE INDEX rdj_cas_esen_ind ON rdj_detalles_contratos (cas_ing_esencia);

/* tabla rdj_metodos_contratos */
CREATE INDEX rdj_pago_metco_ind ON rdj_metodos_contratos (id_pago,id_prov_pago);
CREATE INDEX rdj_envio_metco_ind ON rdj_metodos_contratos (id_envio,id_prov_envio,id_pais_envio);

/* tabla rdj_pedidos */
CREATE INDEX rdj_prov_pedido_ind ON rdj_pedidos (id_proveedor);
CREATE INDEX rdj_prod_pedido_ind ON rdj_pedidos (id_productor);
CREATE INDEX rdj_envio_pedido_ind ON rdj_pedidos (id_envio,fecha_ap_envio,id_prod_envio,id_prov_envio);
CREATE INDEX rdj_pago_pedido_ind ON rdj_pedidos (id_pago,fecha_ap_pago,id_prod_pago,id_prov_pago);

/* tabla rdj_detalles_pedidos */
CREATE INDEX rdj_esencia_detped_ind ON rdj_detalles_pedidos (id_pres_esencia,cas_esencia);
CREATE INDEX rdj_otro_detped_ind ON rdj_detalles_pedidos (id_pres_otro,cas_otro);



/****************************** VISTAS *************************************/

/* Vista para ver los metodos de envios de los proveedores */
CREATE VIEW rdj_vista_metenvio (proveedor,pais,duracion,precio,tipo) AS 
SELECT p.nombre, pa.nombre, to_char(m.duracion,'999') || ' dias', m.precio || ' $', 
CASE WHEN m.tipo = 't' THEN 'terrestre' WHEN m.tipo = 'm' THEN 'maritimo' ELSE 'aereo' END
FROM rdj_proveedores p, rdj_paises pa, rdj_metodos_envios m WHERE p.id=m.id_proveedor AND pa.id=m.id_pais
ORDER BY p.nombre,pa.nombre;

/* Vista para ver los metodos de pago de los proveedores */
CREATE VIEW rdj_vista_metpago (proveedor,tipo,numero_cuotas,porcentaje,pago_cada_x_meses) AS
SELECT p.nombre, CASE WHEN m.tipo = 'c' THEN 'completo' ELSE 'parcial' END,
COALESCE(to_char(m.num_cuotas,'9'),'N/A'), COALESCE(to_char(m.porcentaje,'999'),'N/A'),
COALESCE(to_char(m.meses,'99'),'N/A') FROM rdj_metodos_pagos m, rdj_proveedores p 
WHERE p.id=m.id_proveedor ORDER BY p.nombre,m.tipo;

/* Vista para ver proveedores con su asociacion respectiva y su membresia ifra */
CREATE VIEW rdj_vista_prov (proveedor,fecha_inicio,fecha_fin,asociacion,pais) AS
SELECT prov.nombre, to_char(m.fecha_inicio,'dd-mm-yyyy'), COALESCE(to_char(m.fecha_fin,'dd-mm-yyyy'),'vigente'), 
COALESCE(a.nombre,'N/A'), pa.nombre AS pais FROM rdj_miembros_ifra m, rdj_paises pa, rdj_proveedores prov
LEFT OUTER JOIN rdj_asociaciones a ON a.id=prov.id_asociacion
WHERE m.id_proveedor=prov.id AND prov.id_pais=pa.id ORDER BY m.fecha_inicio;

/* Vista para ver detalles de metodos de envios */
CREATE VIEW rdj_vista_detenvio (proveedor,pais,id_envio,modificador,tipo,precio,duracion) AS
SELECT pr.nombre, pa.nombre, m.id, det.nombre, 
CASE WHEN m.tipo = 't' THEN 'terrestre' WHEN m.tipo = 'm' THEN 'maritimo' ELSE 'aereo' END,
det.mod_precio || ' $', det.mod_duracion || ' dias' FROM rdj_proveedores pr, rdj_paises pa, 
rdj_detalles_metodos_envios det, rdj_metodos_envios m WHERE pr.id=det.id_proveedor AND 
pa.id=det.id_pais AND m.id=det.id_envio ORDER BY pr.nombre,pa.nombre,m.tipo;

/* Vista para ver las esencias de perfumes y las familias que tiene */
CREATE VIEW rdj_vista_esenciasperfume (cas,esencia,tipo,familia) AS
SELECT es.num_cas, es.nombre, CASE WHEN es.tipo = 'n' THEN 'natural' ELSE 'sintetico' END,
fa.nombre FROM rdj_esencias_perfumes es, rdj_familias_olfativas fa, rdj_esencias_perfumes_familias ef 
WHERE ef.id_familia=fa.id AND ef.cas_esencia=es.num_cas ORDER BY es.nombre;

/* Vista para ver las notas por fases de los perfumes */
CREATE VIEW rdj_vista_notasfases (perfume,esencia,tipo) AS
SELECT pe.nombre, es.nombre, CASE WHEN n.tipo = 's' THEN 'salida' WHEN n.tipo = 'c' 
THEN 'corazon' WHEN n.tipo = 'f' THEN 'fondo' END FROM rdj_esencias_perfumes es, 
rdj_perfumes pe, rdj_notas n WHERE pe.id=n.id_perfume AND es.num_cas=n.cas_esencia ORDER BY pe.nombre,n.tipo;

/* Vista para ver las notas monoliticas de los perfumes */
CREATE VIEW rdj_vista_notasmono (perfume,esencia) AS
SELECT pe.nombre, es.nombre FROM rdj_esencias_perfumes es, 
rdj_perfumes pe, rdj_monoliticos m WHERE pe.id=m.id_perfume 
AND es.num_cas=m.cas_esencia ORDER BY pe.nombre;

/* Vista para ver las palabras claves de las familias */
CREATE VIEW rdj_vista_palclave (familia,palabra) AS
SELECT fa.nombre, pa.palabra FROM rdj_familias_olfativas fa, 
rdj_palabras_claves pa, rdj_familias_palabras fp
WHERE fp.id_familia=fa.id AND fp.id_palabra=pa.id ORDER BY fa.id;

/* Vista para ver las presentaciones de los ingredientes esencias */
CREATE VIEW rdj_vista_presents_esencias (proveedor,esencia,cas,vol,precio) AS
SELECT p.nombre, ing.nombre, ing.cas_ing_esencia, pr.volumen || ' ml', 
pr.precio || ' $' FROM rdj_ingredientes_esencias ing, rdj_presents_ings_esencias pr,
rdj_proveedores p WHERE p.id=ing.id_proveedor AND pr.cas_ing_esencia=ing.cas_ing_esencia 
ORDER BY p.id,ing.cas_ing_esencia;

/* Vista para ver las presentaciones de los otros ingredientes */
CREATE VIEW rdj_vista_presents_otros (proveedor,otroing,cas,vol,precio) AS
SELECT p.nombre, ing.nombre, ing.cas_otro_ing, pr.volumen || ' ml', pr.precio || ' $' 
FROM rdj_otros_ingredientes ing, rdj_present_otros_ings pr,
rdj_proveedores p WHERE p.id=ing.id_proveedor AND
pr.cas_otro_ing=ing.cas_otro_ing ORDER BY p.id,ing.cas_otro_ing;

/* Vista para ver las formulas */
CREATE VIEW rdj_vista_formulas (productor,criterio,fecha_inicio,peso,tipo,fecha_fin) AS
SELECT p.nombre, c.nombre, to_char(h.fecha_inicio,'dd/mm/yyyy'), to_char(h.peso,'99.99') || '%', 
CASE WHEN h.tipo='i' THEN 'inicial' WHEN h.tipo='a' THEN 'anual' END, 
COALESCE(to_char(h.fecha_fin,'dd/mm/yyyy'),'N/A') FROM rdj_productores p, rdj_hist_formulas h,
rdj_criterios c WHERE c.id=h.id_criterio AND p.id=h.id_productor ORDER BY p.id,h.tipo,c.id;

/* Vista para ver las escalas */
CREATE VIEW rdj_vista_escalas (productor,fecha_inicio,rango_i,rango_f,fecha_fin) AS
SELECT p.nombre, to_char(e.fecha_inicio,'dd/mm/yyyy'), to_char(e.rango_inicio,'999') || 'pts', to_char(e.rango_fin,'999') || 'pts', 
COALESCE(to_char(e.fecha_fin,'dd/mm/yyyy'),'N/A') FROM rdj_productores p, rdj_escalas e
WHERE p.id=e.id_productor ORDER BY p.id;