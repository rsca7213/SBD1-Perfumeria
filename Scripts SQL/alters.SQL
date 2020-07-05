/*
    ALTERS de la base de datos del proyecto de perfumes donde se agregan las constraint FK
    Grupo #2
    Seccion 002
    Ricardo Salvatorelli (C.I.: 26.967.602)
    Jose Manuel Ramirez (C.I.: 26.902.002)
    David Zacarias (C.I.: 27.588.099)
*/

/*ALTERS de la tabla rdj_paises*/

ALTER TABLE rdj_paises ADD CONSTRAINT pais_asociacion_fk FOREIGN KEY (id_asociacion) REFERENCES
rdj_asociaciones (id);

/*ALTERS de la tabla rdj_proveedores*/

ALTER TABLE rdj_proveedores ADD CONSTRAINT proveedor_asociacion_fk FOREIGN KEY (id_asociacion) REFERENCES
rdj_asociaciones (id);

/*ALTERS de la tabla rdj_productores*/

ALTER TABLE rdj_productores ADD CONSTRAINT productor_asociacion_fk FOREIGN KEY (id_asociacion) REFERENCES
rdj_asociaciones (id);

/*ALTERS de la tabla rdj_miembros_ifra*/

ALTER TABLE rdj_miembros_ifra ADD CONSTRAINT miembro_ifra_proveedor_fk FOREIGN KEY (id_proveedor) REFERENCES
rdj_proveedores (id);

ALTER TABLE rdj_miembros_ifra ADD CONSTRAINT miembro_ifra_id_productor_fk FOREIGN KEY (id_productor) REFERENCES
rdj_productores (id);

/*ALTERS de la tabla rdj_metodos_pagos*/

ALTER TABLE rdj_metodos_pagos ADD CONSTRAINT metodo_pago_proveedor_fk FOREIGN KEY (id_proveedor) REFERENCES
rdj_proveedores (id);

/*ALTERS de la tabla rdj_metodos_envios*/

ALTER TABLE rdj_metodos_envios ADD CONSTRAINT metodo_envio_proveedor_fk FOREIGN KEY (id_proveedor) REFERENCES
rdj_proveedores (id);

ALTER TABLE rdj_metodos_envios ADD CONSTRAINT metodo_envio_pais_fk FOREIGN KEY (id_pais) REFERENCES
rdj_paises (id);

/*ALTERS de la tabla rdj_detalles_metodos_envios*/

ALTER TABLE rdj_detalles_metodos_envios ADD CONSTRAINT detalle_metodo_envio_envio_fk FOREIGN KEY (id_envio,id_proveedor,id_pais) REFERENCES
rdj_metodos_envios (id,id_proveedor,id_pais);

/*ALTERS de la tabla rdj_productores_paises*/

ALTER TABLE rdj_productores_paises ADD CONSTRAINT productor_pais_pais_fk FOREIGN KEY (id_pais) REFERENCES
rdj_paises (id);

ALTER TABLE rdj_productores_paises ADD CONSTRAINT productor_pais_productor_fk FOREIGN KEY (id_productor) REFERENCES
rdj_productores (id);

/*ALTERS de la tabla rdj_escalas*/

ALTER TABLE rdj_escalas ADD CONSTRAINT escala_productor_fk FOREIGN KEY (id_productor) REFERENCES
rdj_productores (id);

/*ALTERS de la tabla rdj_hist_formulas*/

ALTER TABLE rdj_hist_formulas ADD CONSTRAINT hist_formula_criterio_fk FOREIGN KEY (id_criterio) REFERENCES
rdj_criterios (id);

ALTER TABLE rdj_hist_formulas ADD CONSTRAINT hist_formula_productor_fk FOREIGN KEY (id_productor) REFERENCES
rdj_productores (id);

/*ALTERS de la tabla rdj_resultados*/

ALTER TABLE rdj_resultados ADD CONSTRAINT resultado_productor_fk FOREIGN KEY (id_productor) REFERENCES
rdj_productores (id);

ALTER TABLE rdj_resultados ADD CONSTRAINT resultado_proveedor_fk FOREIGN KEY (id_proveedor) REFERENCES
rdj_proveedores (id);

/*ALTERS de la tabla rdj_perfumistas*/

ALTER TABLE rdj_perfumistas ADD CONSTRAINT perfumistas_id_pais_fk FOREIGN KEY (id_pais) REFERENCES
rdj_paises (id);

/*ALTERS de la tabla rdj_perfumes*/

ALTER TABLE rdj_perfumes ADD CONSTRAINT perfume_productor_fk FOREIGN KEY (id_productor) REFERENCES
rdj_productores (id);

/*ALTERS de la tabla rdj_perfumes_perfumistas*/

ALTER TABLE rdj_perfumes_perfumistas ADD CONSTRAINT perfume_perfumista_perfume_fk FOREIGN KEY (id_perfume) REFERENCES
rdj_perfumes (id);

ALTER TABLE rdj_perfumes_perfumistas ADD CONSTRAINT perf_perfumista_perfumista_fk FOREIGN KEY (id_perfumista) REFERENCES
rdj_perfumistas (id);

/*ALTERS de la tabla rdj_intensidades*/

ALTER TABLE rdj_intensidades ADD CONSTRAINT intensidad_perfume_fk FOREIGN KEY (id_perfume) REFERENCES
rdj_perfumes (id);

/*ALTERS de la tabla rdj_presentaciones_perfumes*/

ALTER TABLE rdj_presentaciones_perfumes ADD CONSTRAINT presentacion_perfum_perfume_fk FOREIGN KEY (id_perfume,id_intensidad) REFERENCES
rdj_intensidades (id_perfume,id);

/*ALTERS de la tabla rdj_monoliticos*/

ALTER TABLE rdj_monoliticos ADD CONSTRAINT monolitico_perfume_fk FOREIGN KEY (id_perfume) REFERENCES
rdj_perfumes (id);

ALTER TABLE rdj_monoliticos ADD CONSTRAINT monolitico_esencia_perfumes_fk FOREIGN KEY (cas_esencia) REFERENCES
rdj_esencias_perfumes (num_cas);

/*ALTERS de la tabla rdj_notas*/

ALTER TABLE rdj_notas ADD CONSTRAINT nota_perfume_fk FOREIGN KEY (id_perfume) REFERENCES
rdj_perfumes (id);

ALTER TABLE rdj_notas ADD CONSTRAINT nota_esencia_perfumes_fk FOREIGN KEY (cas_esencia) REFERENCES
rdj_esencias_perfumes (num_cas);

/*ALTERS de la tabla rdj_esencias_perfumes_familias*/

ALTER TABLE rdj_esencias_perfumes_familias ADD CONSTRAINT esencia_perfume_fam_esencia_fk FOREIGN KEY (cas_esencia) REFERENCES
rdj_esencias_perfumes (num_cas);

ALTER TABLE rdj_esencias_perfumes_familias ADD CONSTRAINT esencia_perfume_fam_familia_fk FOREIGN KEY (id_familia) REFERENCES
rdj_familias_olfativas (id);

/*ALTERS de la tabla rdj_perfumes_familias*/

ALTER TABLE rdj_perfumes_familias ADD CONSTRAINT perfume_familia_familia_fk FOREIGN KEY (id_familia) REFERENCES
rdj_familias_olfativas (id);

ALTER TABLE rdj_perfumes_familias ADD CONSTRAINT perfume_familia_perfume_fk FOREIGN KEY (id_perfume) REFERENCES
rdj_perfumes (id);

/*ALTERS de la tabla rdj_familias_palabras*/

ALTER TABLE rdj_familias_palabras ADD CONSTRAINT familia_palabra_palabra_fk FOREIGN KEY (id_palabra) REFERENCES
rdj_palabras_claves (id);

ALTER TABLE rdj_familias_palabras ADD CONSTRAINT familia_palabra_familia_fk FOREIGN KEY (id_familia) REFERENCES
rdj_familias_olfativas(id);

/*ALTERS de la tabla rdj_otros_ingredientes*/

ALTER TABLE rdj_otros_ingredientes ADD CONSTRAINT otro_ingrediente_proveedor_fk FOREIGN KEY (id_proveedor) REFERENCES
rdj_proveedores(id);

/*ALTERS de la tabla rdj_present_otros_ings*/

ALTER TABLE rdj_present_otros_ings ADD CONSTRAINT present_otro_ing_otro_ing_fk FOREIGN KEY (cas_otro_ing) REFERENCES
rdj_otros_ingredientes(cas_otro_ing);

/*ALTERS de la tabla rdj_otros_componentes*/

ALTER TABLE rdj_otros_componentes ADD CONSTRAINT otros_componentes_otro_ing_fk FOREIGN KEY (cas_otro_ing) REFERENCES
rdj_otros_ingredientes(cas_otro_ing);

ALTER TABLE rdj_otros_componentes ADD CONSTRAINT otros_componentes_perfume_fk FOREIGN KEY (id_perfume) REFERENCES
rdj_perfumes(id);

/*ALTERS de la tabla rdj_ingredientes_esencias*/

ALTER TABLE rdj_ingredientes_esencias ADD CONSTRAINT ing_esencia_proveedor_fk FOREIGN KEY (id_proveedor) REFERENCES
rdj_proveedores(id);

/*ALTERS de la tabla rdj_ings_esencias_paises*/

ALTER TABLE rdj_ings_esencias_paises ADD CONSTRAINT ing_esencia_pais_pais_fk FOREIGN KEY (id_paises) REFERENCES
rdj_paises(id);

ALTER TABLE rdj_ings_esencias_paises ADD CONSTRAINT ing_esenc_pais_ing_esencia_fk FOREIGN KEY (cas_ing_esencia) REFERENCES
rdj_ingredientes_esencias(cas_ing_esencia);

/*ALTERS de la tabla rdj_presents_ings_esencias*/

ALTER TABLE rdj_presents_ings_esencias ADD CONSTRAINT present_ing_esenc_ing_esenc_fk FOREIGN KEY (cas_ing_esencia) REFERENCES
rdj_ingredientes_esencias(cas_ing_esencia);

/*ALTERS de la tabla rdj_ings_esencias_otros_ings*/

ALTER TABLE rdj_ings_esencias_otros_ings ADD CONSTRAINT ing_esenc_otro_ing_ing_esen_fk FOREIGN KEY (cas_ing_esencia) REFERENCES
rdj_ingredientes_esencias(cas_ing_esencia);

ALTER TABLE rdj_ings_esencias_otros_ings ADD CONSTRAINT ing_esenc_otro_ing_otro_ing_fk FOREIGN KEY (cas_otro_ing) REFERENCES
rdj_otros_ingredientes(cas_otro_ing);

/*ALTERS de la tabla rdj_ings_esencias_familias*/

ALTER TABLE rdj_ings_esencias_familias ADD CONSTRAINT ing_esenc_famili_ing_esenc_fk FOREIGN KEY (cas_ing_esencia) REFERENCES
rdj_ingredientes_esencias(cas_ing_esencia);

ALTER TABLE rdj_ings_esencias_familias ADD CONSTRAINT ing_esenc_familia_familia_fk FOREIGN KEY (id_familia) REFERENCES
rdj_familias_olfativas(id);

/*ALTERS de la tabla rdj_contratos*/

ALTER TABLE rdj_contratos ADD CONSTRAINT contrato_proveedor_fk FOREIGN KEY (id_proveedor) REFERENCES
rdj_proveedores(id);

ALTER TABLE rdj_contratos ADD CONSTRAINT contrato_productor_fk FOREIGN KEY (id_productor) REFERENCES
rdj_productores(id);

/*ALTERS de la tabla rdj_renovaciones*/

ALTER TABLE rdj_renovaciones ADD CONSTRAINT renovacion_contrato_fk FOREIGN KEY (fecha_apertura,id_proveedor,id_productor) REFERENCES
rdj_contratos(fecha_apertura,id_proveedor,id_productor);

/*ALTERS de la tabla rdj_detalles_contratos*/

ALTER TABLE rdj_detalles_contratos ADD CONSTRAINT detalle_contrato_contrato_fk FOREIGN KEY (fecha_apertura,id_proveedor,id_productor) REFERENCES
rdj_contratos(fecha_apertura,id_proveedor,id_productor);

ALTER TABLE rdj_detalles_contratos ADD CONSTRAINT detalle_contrato_otro_ing_fk FOREIGN KEY (cas_otro_ing) REFERENCES
rdj_otros_ingredientes(cas_otro_ing);

ALTER TABLE rdj_detalles_contratos ADD CONSTRAINT detalle_contrato_ing_esenc_fk FOREIGN KEY (cas_ing_esencia) REFERENCES
rdj_ingredientes_esencias(cas_ing_esencia);

/*ALTERS de la tabla rdj_metodos_contratos*/

ALTER TABLE rdj_metodos_contratos ADD CONSTRAINT metodo_contrato_contrato_fk FOREIGN KEY (fecha_cont,id_proveedor,id_productor) REFERENCES
rdj_contratos(fecha_apertura,id_proveedor,id_productor);

ALTER TABLE rdj_metodos_contratos ADD CONSTRAINT met_contrato_met_pago_fk FOREIGN KEY (id_pago,id_prov_pago) REFERENCES
rdj_metodos_pagos(id,id_proveedor);

ALTER TABLE rdj_metodos_contratos ADD CONSTRAINT met_contrato_met_envio_fk FOREIGN KEY (id_envio,id_prov_envio,id_pais_envio) REFERENCES
rdj_metodos_envios(id,id_proveedor,id_pais);

/*ALTERS de la tabla rdj_pedidos*/

ALTER TABLE rdj_pedidos ADD CONSTRAINT pedido_proveedor_fk FOREIGN KEY (id_proveedor) REFERENCES
rdj_proveedores(id);

ALTER TABLE rdj_pedidos ADD CONSTRAINT pedido_productor_fk FOREIGN KEY (id_productor) REFERENCES
rdj_productores(id);

ALTER TABLE rdj_pedidos ADD CONSTRAINT pedido_metodo_contrato_fk FOREIGN KEY (id_envio,fecha_ap_envio,id_prod_envio,id_prov_envio) REFERENCES
rdj_metodos_contratos(id_envio,fecha_cont,id_productor,id_prov_envio);

ALTER TABLE rdj_pedidos ADD CONSTRAINT pedido_metodo_contrato_fk FOREIGN KEY (id_pago,fecha_ap_pago,id_prod_pago,id_prov_pago) REFERENCES
rdj_metodos_contratos(id_pago,fecha_cont,id_productor,id_prov_pago);

/*ALTERS de la tabla rdj_detalles_pedidos*/

ALTER TABLE rdj_detalles_pedidos ADD CONSTRAINT detalle_pedido_pedido_fk FOREIGN KEY (num_pedido) REFERENCES
rdj_pedidos(num_pedido);

ALTER TABLE rdj_detalles_pedidos ADD CONSTRAINT det_ped_present_ing_esenc_fk FOREIGN KEY (id_pres_esencia,cas_esencia) REFERENCES
rdj_presents_ings_esencias(id,cas_ing_esencia);

ALTER TABLE rdj_detalles_pedidos ADD CONSTRAINT det_pedido_present_otro_ing_fk FOREIGN KEY (id_pres_otro,cas_otro) REFERENCES
rdj_present_otros_ings(id,cas_otro_ing);

/*ALTERS de la tabla rdj_pagos*/
ALTER TABLE rdj_pagos ADD CONSTRAINT pago_pedido_fk FOREIGN KEY (num_pedido) REFERENCES
rdj_pedidos(num_pedido);


























































