
/*
    CREATES, ALTERS Y SEQUENCES de la base de datos del proyecto de perfumes
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
    fecha DATE NOT NULL,
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
    fecha_apertura DATE NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    id_productor NUMERIC(2,0) NOT NULL,
    exclusivo BOOLEAN NOT NULL,
    cancelacion BOOLEAN,
    razon_cierre VARCHAR(1000),
    CONSTRAINT contratos_pk PRIMARY KEY (fecha_apertura, id_proveedor, id_productor)
);

CREATE TABLE rdj_renovaciones (
    id NUMERIC(5,0) NOT NULL,
    fecha_apertura DATE NOT NULL,
    id_proveedor NUMERIC(2,0) NOT NULL,
    id_productor NUMERIC(2,0) NOT NULL,
    fecha_renovacion DATE NOT NULL,
    CONSTRAINT renovaciones_pk PRIMARY KEY (id, fecha_apertura, id_proveedor, id_productor)
);

CREATE TABLE rdj_detalles_contratos (
    id NUMERIC(8,0) NOT NULL,
    fecha_apertura DATE NOT NULL,
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
    fecha_cont DATE NOT NULL,
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
CREATE SEQUENCE id_asoc_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99;

/* tabla rdj_paises */
CREATE SEQUENCE id_pais_sec INCREMENT BY 1 START WITH 1 MAXVALUE 999;

/* tabla rdj_proveedores */
CREATE SEQUENCE id_proveedores_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99;

/* tabla rdj_productores */
CREATE SEQUENCE id_productores_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99;

/* tabla rdj_miembros_ifra */
CREATE SEQUENCE id_miembro_ifra_sec INCREMENT BY 1 START WITH 1 MAXVALUE 9999;

/* tabla rdj_metodos_pagos */
CREATE SEQUENCE id_metodo_pago_sec INCREMENT BY 1 START WITH 1 MAXVALUE 9999;

/* tabla rdj_metodos_envios */
CREATE SEQUENCE id_metodo_envio_sec INCREMENT BY 1 START WITH 1 MAXVALUE 9999;

/* tabla rdj_detalles_metodos_envios */
CREATE SEQUENCE id_det_metodoenvio_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999999;

/* tabla rdj_criterios */
CREATE SEQUENCE id_criterio_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99;

/* tabla rdj_perfumistas */
CREATE SEQUENCE id_perfumista_sec INCREMENT BY 1 START WITH 1 MAXVALUE 9999;

/* tabla rdj_perfumes */
CREATE SEQUENCE id_perfume_sec INCREMENT BY 1 START WITH 1 MAXVALUE 9999;

/* tabla rdj_intensidades */
CREATE SEQUENCE id_intensidad_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999;

/* tabla rdj_presentaciones_perfumes */
CREATE SEQUENCE id_present_perfume_sec INCREMENT BY 1 START WITH 1 MAXVALUE 999999;

/* tabla rdj_familias_olfativas */
CREATE SEQUENCE id_familia_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99;

/* tabla rdj_palabras_claves */
CREATE SEQUENCE id_palabra_sec INCREMENT BY 1 START WITH 1 MAXVALUE 9999;

/* tabla rdj_presents_otros_ings */
CREATE SEQUENCE id_present_otro_ing_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999;

/* tabla rdj_presents_ings_esencias */
CREATE SEQUENCE id_present_ing_esen_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999;

/* tabla rdj_renovacines */
CREATE SEQUENCE id_renovacion_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999;

/* tabla rdj_detalles_contratos */
CREATE SEQUENCE id_det_contrato_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999999;

/* tabla rdj_metodos_contratos */
CREATE SEQUENCE id_metodo_contrato_sec INCREMENT BY 1 START WITH 1 MAXVALUE 999999;

/* tabla rdj_pedidos */
CREATE SEQUENCE num_pedido_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999999;

/* tabla rdj_detalles_pedidos */
CREATE SEQUENCE id_det_pedido_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999999;

/* tabla rdj_pagos */
CREATE SEQUENCE num_pago_sec INCREMENT BY 1 START WITH 1 MAXVALUE 99999999;