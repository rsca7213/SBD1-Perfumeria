/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);
Vue.component(
    "crear-formula-inicial",
    require("./components/formulas/crearFormulaInicial.vue").default
);
Vue.component(
    "editar-formula-inicial",
    require("./components/formulas/editarFormulaInicial.vue").default
);
Vue.component(
    "crear-formula-anual",
    require("./components/formulas/crearFormulaAnual.vue").default
);
Vue.component(
    "editar-formula-anual",
    require("./components/formulas/editarFormulaAnual.vue").default
);
Vue.component(
    "crear-escala",
    require("./components/formulas/crearEscala.vue").default
);
Vue.component(
    "editar-escala",
    require("./components/formulas/editarEscala.vue").default
);
Vue.component(
    "realizar-evaluacion",
    require("./components/evaluaciones/realizarEvaluacion.vue").default
);
Vue.component(
    "ver-facturas",
    require("./components/compras/facturas.vue").default
);
Vue.component(
    "ver-evaluaciones",
    require("./components/evaluaciones/verEvaluaciones.vue").default
);
Vue.component(
    "realizar-pedido",
    require("./components/compras/realizarPedido.vue").default
);
Vue.component(
    "filtros-recomendador",
    require("./components/recomendador/filtrosRecomendador.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app"
});
