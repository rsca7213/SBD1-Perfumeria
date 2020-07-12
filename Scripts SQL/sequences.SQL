/*
    SEQUENCES de la base de datos del proyecto de perfumes
    Grupo #2
    Seccion 002
    Ricardo Salvatorelli (C.I.: 26.967.602)
    Jose Manuel Ramirez (C.I.: 26.902.002)
    David Zacarias (C.I.: 27.588.099)
*/

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