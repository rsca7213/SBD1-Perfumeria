<template>
    <span>
        
        <!-- Seleccion de tipo de evaluacion -->
        <span v-if="tipoEv === ''">
            <div class="row d-flex justify-content-center">
                <span class="h5 mr-3 mt-2"> Tipo de Evaluación: </span>
                <button class="btn btn-small btn-primary" @click="evInicial" :disabled="btnEvInicDis"> Inicial </button>
                <button class="btn btn-small btn-primary mx-4" @click="evAnual" :disabled="btnEvAnualDis"> Anual </button>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <h5> Selecciona un tipo de evaluación... </h5>
            </div>

            <!-- Muestra disponibilidad de formulas y escala -->
            <span v-if="formInicDisp === true" class="flex-row d-flex align-items-center ml-4 my-2">
                <img src="/img/iconos/check_green.svg" width="24" class="mb-1">
                <span class="h6 ml-2"> <b> Formula inicial disponible. </b> </span>
            </span>
            <span v-else class="flex-row d-flex align-items-center ml-4 my-2">
                <img src="/img/iconos/cancel_red.svg" width="24" class="mb-1">
                <span class="h6 ml-2"> <b> Formula inicial no disponible. </b> </span>
            </span>
            <span v-if="formAnualDisp === true" class="flex-row d-flex align-items-center ml-4 my-2">
                <img src="/img/iconos/check_green.svg" width="24" class="mb-1">
                <span class="h6 ml-2"> <b> Formula anual disponible. </b> </span>
            </span>
            <span v-else class="flex-row d-flex align-items-center ml-4 my-2">
                <img src="/img/iconos/cancel_red.svg" width="24" class="mb-1">
                <span class="h6 ml-2"> <b> Formula anual no disponible. </b> </span>
            </span>
            <span v-if="escalaDisp === true" class="flex-row d-flex align-items-center ml-4 my-2">
                <img src="/img/iconos/check_green.svg" width="24" class="mb-1">
                <span class="h6 ml-2"> <b> Escala de evaluación disponible. </b> </span>
            </span>
            <span v-else class="flex-row d-flex align-items-center ml-4 my-2">
                <img src="/img/iconos/cancel_red.svg" width="24" class="mb-1">
                <span class="h6 ml-2"> <b> Escala de evaluación no disponible. </b> </span>
            </span>
        </span>

        <!-- Seleccion de proveedor a evaluar en una evaluacion inicial -->
        <span v-if="tipoEv === 'inicial'"> 
            <div class="row d-flex justify-content-center">
                <span class="h5"> <b> Evaluación Inicial </b> </span>
            </div>
            <hr>
            <span class="h5"> <b> Seleccionar proveedor a evaluar: </b> </span>
            <hr>
            <div class="row mx-4 p-2 border border-info" v-for="prov in provsInic" :key="prov.idp">
                <span class="col-12 h5"> <b> Proveedor: </b> {{ prov["prov"] }}</span>
                <span class="col-6"> &#x2022; <b> Fecha de inicio membresia IFRA: </b> {{ prov["memb"] }} </span>
                <span class="col-6"> &#x2022; <b> Tipo de membresia IFRA: </b> {{ prov["tipo"] }}</span>
                <span class="col-6"> 
                    &#x2022; <b> Asociación: </b> {{ prov["asoc"] }}
                    <span v-if="prov['asoc'] === null"> No aplica </span>
                </span>
                <span class="col-6"> &#x2022; <b> Paises de envío: </b> 
                    <span v-for="(pais, index) in prov['paises']" :key="pais"> 
                        <span> {{ pais }} </span>
                        <span v-if="!(prov['paises'].length - 1 === index)">,</span>
                    </span>
                </span>
                <div class="col-12 row d-flex justify-content-center mt-2">
                    <button class="btn btn-primary" @click="dataInicial(prov['idp'])"> 
                        <img src="/img/iconos/check_white.svg" alt="evaluar" width="24" class="mb-1">
                        <span class="ml-2"> Evaluar </span>
                    </button>
                </div>
            </div>
        </span>

        <!-- Proceso de evaluacion inicial -->
        <span v-if="tipoEv === 'inicialStart'"> 
            <form action="inicial" method="POST">
                <div class="row d-flex justify-content-center">
                    <span class="h5 col-12 text-center"> <b> Proveedor: </b> {{ provInicData["prov"] }} </span>
                    <div class="col-12 h6"> &#x2022; <b> Productos disponibles en el catálogo: </b> </div>
                    <div class="col-12">
                        <table class="table table-striped border border-info">
                            <thead class="bg-primary text-white"> 
                                <th scope="col" class="p-1 m-0 pl-2 "> # Cas </th>
                                <th scope="col" class="p-1 m-0"> Nombre </th>
                                <th scope="col" class="p-1 m-0"> Tipo </th>
                                <th scope="col" class="p-1 m-0 text-center"> Presentaciones </th>
                            </thead>
                            <tbody>
                                <tr v-for="(esen,index) in provInicData['esencias']" :key="esen.cas">
                                    <td class=" p-1 m-0 pl-2"> {{ esen["cas"] }} </td>
                                    <td class="p-1 m-0"> {{ esen["ing"] }} </td>
                                    <td class="p-1 m-0"> Esencia {{ esen["tipo"] }} </td>
                                    <td class="text-center p-1 m-0"> 
                                        <img src="/img/iconos/list.svg" alt="ver" width="24" class="iconobtn" 
                                        @click="rellenarModalDetProducto('e',index)" data-toggle="modal"
                                        data-target="#modalInicDetProducto">
                                    </td>
                                </tr>
                                <tr v-for="(otro,index) in provInicData['otros']" :key="otro.cas">
                                    <td class=" p-1 m-0 pl-2"> {{ otro["cas"] }} </td>
                                    <td class="p-1 m-0"> {{ otro["ing"] }} </td>
                                    <td class="p-1 m-0"> Componente </td>
                                    <td class="text-center p-1 m-0"> 
                                        <img src="/img/iconos/list.svg" alt="ver" width="24" class="iconobtn"
                                        @click="rellenarModalDetProducto('o',index)" data-toggle="modal"
                                        data-target="#modalInicDetProducto">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr class="py-0 my-2">
                <div class="flex-row d-flex justify-content-center mx-4">
                    <span class="h5 col-12 text-center"> 
                        <b> Criterio de Evaluación: </b> {{ formInic[0]["nombre"] }} <b> 
                        ({{ formInic[0]["peso"] }} %) </b> 
                    </span>
                </div>
                <hr class="py-0 my-0 mb-2">
                <div class="row d-flex justify-content-center mx-4">
                    <table class="table table-striped mx-4 col-8">
                        <thead class="text-center">
                            <th scope="col" class="bg-primary text-white border border-info"> Ubicación / Sede Principal </th>
                            <th scope="col" style="background-color: #E4E4E4" class="border border-info"> {{ provInicData["pais"] }} </th>
                        </thead>
                    </table>
                </div>
                <div class="flex-row d-flex justify-content-center mx-4 align-items-center">
                    <span class="mr-2" style="font-size: 16px"> <b> Puntaje: </b> </span> 
                    <input type="number" name="inicUbic" :min="escala.ri" :max="escala.rf" class="form-control w-25" 
                    id="inicUbic" v-model.number="provInicRes[0]" :class="inicUbicErr">
                    <span class="ml-2" style="font-size: 16px"> 
                        <b> (de {{escala.ri}} a {{escala.rf}}) </b> 
                    </span>
                </div>
                <div class="flex-row d-flex justify-content-center">
                    <span v-if="inicUbicErr === 'is-invalid'"> 
                        <small class="text-danger"> <b> El puntaje está fuera de la escala. </b> </small> 
                    </span>
                </div>
                <hr class="py-0 my-2">
                <div class="flex-row d-flex justify-content-center mx-4">
                    <span class="h5 col-12 text-center"> 
                        <b> Criterio de Evaluación: </b> {{ formInic[1]["nombre"] }} <b> 
                        ({{ formInic[1]["peso"] }} %) </b> 
                    </span>
                </div>
                <hr class="py-0 my-2">
                <div class="row d-flex justify-content-center mx-4">
                    <table class="table table-striped border border-info mx-4">
                        <thead class="bg-primary text-white">
                            <th scope="col"> Tipo de pago </th>
                            <th scope="col" class="text-center"> Número de cuotas </th>
                            <th scope="col" class="text-center"> Porcentaje por cuota </th>
                            <th scope="col" class="text-center"> Pago cada </th>
                        </thead>
                        <tbody>
                            <tr v-for="pago in provInicData['pagos']" :key="pago.id">
                                <td> {{ pago["tipo"] }} </td> 
                                <td v-if="pago['numc'] != null" class="text-center"> 
                                    <span v-if="pago['numc'] > 1"> {{ pago["numc"] }} cuotas </span>
                                    <span v-else> 1 cuota </span>
                                </td>
                                <td v-else class="text-center"> N/A </td>
                                <td v-if="pago['porc'] != null" class="text-center"> {{ pago['porc'] }} % </td>
                                <td v-else class="text-center"> N/A </td>
                                <td v-if="pago['meses'] != null" class="text-center"> 
                                    <span v-if="pago['meses'] > 1"> {{ pago["meses"] }} meses </span>
                                    <span v-else> 1 mes </span>
                                </td>
                                <td v-else class="text-center"> N/A </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex-row d-flex justify-content-center mx-4 align-items-center">
                    <span class="mr-2" style="font-size: 16px"> <b> Puntaje: </b> </span> 
                    <input type="number" name="inicPago" :min="escala.ri" :max="escala.rf" class="form-control w-25" 
                    id="inicPago" v-model.number="provInicRes[1]" :class="inicPagoErr">
                    <span class="ml-2" style="font-size: 16px"> 
                        <b> (de {{escala.ri}} a {{escala.rf}}) </b> 
                    </span>
                </div>
                <div class="flex-row d-flex justify-content-center">
                    <span v-if="inicPagoErr === 'is-invalid'"> 
                        <small class="text-danger"> <b> El puntaje está fuera de la escala. </b> </small> 
                    </span>
                </div>
                <hr class="py-0 my-2">
                <div class="flex-row d-flex justify-content-center mx-4">
                    <span class="h5 col-12 text-center"> 
                        <b> Criterio de Evaluación: </b> {{ formInic[2]["nombre"] }} <b> 
                        ({{ formInic[2]["peso"] }} %) </b> 
                    </span> 
                </div>
                <hr class="py-0 my-2">
                <div class="row d-flex justify-content-center mx-4">
                    <table class="table table-striped border border-info mx-4">
                        <thead class="bg-primary text-white">
                            <th scope="col"> País de envío </th>
                            <th scope="col"> Tipo de envío </th>
                            <th scope="col" class="text-center"> Duración </th>
                            <th scope="col" class="text-right"> Precio </th>
                            <th scope="col" class="text-center"> Extra </th>
                        </thead>
                        <tbody>
                            <tr v-for="(envio,index) in provInicData['envios']" :key="envio.id_envio">
                                <td> {{ envio["pais"] }} </td>
                                <td> {{ envio["tipo"] }} </td>
                                <td class="text-right" v-if="envio['duracion'] > 1"> {{ envio["duracion"]}} días </td>
                                <td class="text-right" v-else> 1 día </td>
                                <td class="text-right"> {{ envio["precio"] }} $ </td>
                                <td class="text-center"> 
                                    <img src="/img/iconos/list.svg" alt="expandir" width="24" 
                                    class="iconobtn" @click="rellenarModalDetEnvio(index)" data-toggle="modal"
                                    data-target="#modalInicDetEnvio">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex-row d-flex justify-content-center mx-4 align-items-center">
                    <span class="mr-2" style="font-size: 16px"> <b> Puntaje: </b> </span> 
                    <input type="number" name="inicEnvio" :min="escala.ri" :max="escala.rf" class="form-control w-25" 
                    id="inicEnvio" v-model.number="provInicRes[2]" :class="inicEnvioErr">
                    <span class="ml-2" style="font-size: 16px"> 
                        <b> (de {{escala.ri}} a {{escala.rf}}) </b> 
                    </span>
                </div>
                <div class="flex-row d-flex justify-content-center">
                    <span v-if="inicEnvioErr === 'is-invalid'"> 
                        <small class="text-danger"> <b> El puntaje está fuera de la escala. </b> </small> 
                    </span>
                </div>
                <hr class="py-0 my-2">
                <input type="hidden" name="_token" :value="this.csrf">
                <input type="hidden" name="idp" :value="provInicData['idp']">
                <div class="row d-flex justify-content-center mt-2">
                    <button type="submit" class="btn btn-primary" :class="inicFinalizarErr" :disabled="inicFinalizarDis"> 
                        <img src="/img/iconos/check_white.svg" alt="" width="24" class="mb-1">
                        <span class="ml-2"> Finalizar Evaluación </span>
                    </button>
                </div>
            </form>
        </span>

        <!-- Modal para mostrar los detalles de un metodo de envio -->
        <div class="modal fade" id="modalInicDetEnvio" tabindex="-1" role="dialog" 
        aria-hidden="true" v-if="tipoEv === 'inicialStart'">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content" style="background-color: #F5F5F5">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel"> <b> Extra de Envío </b> </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body h5 text-center">
                    <table class="table table-striped border border-info">
                        <thead class="bg-primary text-white">
                            <th scope="col"> Nombre </th>
                            <th scope="col" class="text-right"> Duración </th>
                            <th scope="col" class="text-right"> Precio </th>
                        </thead>
                        <tbody v-if="provInicData['envios'][indexDetEnvio]['detalles'] != []">
                            <tr v-for="det in provInicData['envios'][indexDetEnvio]['detalles']" :key="det.id"> 
                                <td> {{ det["det"] }} </td>
                                <td class="text-right" v-if="det['duracion'] != 1 && det['duracion'] != -1"> {{ det["duracion"]}} días </td>
                                <td class="text-right" v-else> {{ det['duracion'] }} día </td>
                                <td class="text-right"> {{ det["precio"] }} $ </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
        </div>

        <!-- Modal para mostrar los detalles de un producto -->
        <div class="modal fade" id="modalInicDetProducto" tabindex="-1" role="dialog" 
        aria-hidden="true" v-if="tipoEv === 'inicialStart'">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content" style="background-color: #F5F5F5">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel"> <b> Presentaciones de Producto </b> </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body h5 text-center">
                    <span v-if="provInicData['esencias'] != [] && indexProducto[0] === 'e'">
                        <h5> <b> {{ provInicData['esencias'][indexProducto[1]]["ing"] }} </b> </h5>
                    </span>
                    <span v-if="provInicData['otros'] != [] && indexProducto[0] === 'o'">
                        <h5> <b> {{ provInicData['otros'][indexProducto[1]]["ing"] }} </b> </h5>
                    </span>
                    <table class="table table-striped border border-info">
                        <thead class="bg-primary text-white">
                            <th scope="col" class="text-right"> Volumen </th>
                            <th scope="col" class="text-right"> Precio </th>
                        </thead>
                        <tbody v-if="provInicData['esencias'] != [] && indexProducto[0] === 'e'">
                            <tr v-for="pres in provInicData['esencias'][indexProducto[1]]['pres']" :key="pres.id"> 
                                <td class="text-right"> {{ pres["vol"] }} ml </td>
                                <td class="text-right"> {{ pres["precio"] }} $ </td>
                            </tr>
                        </tbody>
                        <tbody v-if="provInicData['otros'] != [] && indexProducto[0] === 'o'">
                            <tr v-for="pres in provInicData['otros'][indexProducto[1]]['pres']" :key="pres.id"> 
                                <td class="text-right"> {{ pres["vol"] }} ml </td>
                                <td class="text-right"> {{ pres["precio"] }} $ </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
        </div>

    </span>
</template>

<script>
export default {

    props: ["csrf"],

    data() {
        return {

            /* Variables que determinan si los botones para realizar evaluacion
               estahn habilitados o no dependiendo de la disponilibidad de las
               formulas y escala */
            btnEvInicDis: null,

            btnEvAnualDis: null,

            escalaDisp: true,

            formInicDisp: true,

            formAnualDisp: true,

            tipoEv: "wait", /*encargada de determinar el tipo de evaluacion a realizar
                            wait = pagina cargando
                            "" (nada) = seleccion de tipo de evaluacion
                            inicial = seleccion de proveedores para ev inicial
                            inicialStart = proceso de evaluacion inicial
                            anual = seleccion de proveedores para ev anual
                            anualStart = proceso de evaluacion anual
                        */

            provsInic: [], /*data que devuelve laravel para seleccionar los proveedores
                             de la evaluacion inicial */

            provInicRes: [0,0,0], /*arreglo que contiene los resultados que se van asignando
                               a los proveedores en la evaluacion inicial */

            provInicData: [], /*arreglo que contiene la data que devuelve laravel
                                 para la evaluacion inicial del proveedor */

            formInic: [], /* Formula de evaluacion inicial del productor */

            formAnual: [], /* Formula de evaluacion anual del productor */

            escala: [], /* Escala de evaluacion del productor */

            indexDetEnvio: 0, /* Variable auxiliar que determina la posicion del arreglo de 
                                metodos de envio que se quiere detallar */
            indexProducto: ['e',0], /* Variable auxiliar que determina la posicion del arreglo
                                 de producto y que tipo de producto es para rellenar el modal */
        }
    },


    /* Al cargar la pagina se procede a buscar las formulas de evaluacion y la escala
       para poder determinar si realizar evaluaciones es posible y tambien tener esa
       data para realizar las evaluaciones. La funcion tambien se encarga de habilitar o
       deshabilitar las evaluaciones dependiendo de la disponibilidad de formulas y escala */
    created() {
        console.log("%cAxios: Buscando formulas y escala.", "color: lightblue");
        axios.get("formulas")
        .then(response => {
            console.log("%cAxios: Exito en busqueda de formulas y escala!", "color: lightgreen");
            this.formInic = response.data[0];
            this.formAnual = response.data[1];
            this.escala = response.data[2];

            //Validacion front-end
            if(this.escala.length === 0) {
                this.btnEvInicDis = "disabled";
                this.btnEvAnualDis = "disabled";
                this.escalaDisp = false;
            }
            else {
                this.provInicRes[0] = this.escala["ri"];
                this.provInicRes[1] = this.escala["ri"];
                this.provInicRes[2] = this.escala["ri"];
            }
            if(this.formInic.length === 0) {
                this.btnEvInicDis = "disabled";
                this.formInicDisp = false;
            }
            if(this.formAnual.length === 0) {
                this.btnEvAnualDis = "disabled";
                this.formAnualDisp = false;
            }

            this.tipoEv = "";

        })
        .catch(errors => {
            console.log("%cAxios: Error buscando formulas y escala!", "color: #FFCCCB");
        });
    },

    methods: {

        /* Funcion AXIOS que busca la data necesaria para realizar la seleccion
           de proveedor para una evaluacion inicial y almacena que el tipo de
           ev a realizar es de tipo inicial */
        evInicial() {
            console.log("%cAxios: Get! Buscando proveedores para ev inicial", "color: lightblue");
            axios.get("inicial")
            .then(response => {
                console.log("%cAxios: Success!", "color: lightgreen");
                this.provsInic = response.data[0];
                this.tipoEv = "inicial";
            })
            .catch(errors => {
                console.log("%cAxios: Error!", "color: #FFCCCB");
            });
        },

        /* Funcion AXIOS que busca la data necesaria para realizar la seleccion
        de proveedores para una evaluacion anual y almacena que el tipo de
        ev a realizar es de tipo anual*/
        evAnual() {
            // POR HACER
            this.tipoEv = "Anual";
        },

         /* Funcion AXIOS que le pide a laravel toda la data necesaria para
           llevar a cabo una evaluacion inicial, envia la id del proveedor
           del cual necesita data */
        dataInicial(idp) {
            console.log("%cAxios: Get! Buscando data para ev inicial", "color: lightblue");
            axios.get("data/inicial/" + idp)
            .then(response => {
                console.log("%cAxios: Success!", "color: lightgreen");
                this.provInicData = response.data[0];
                this.tipoEv = "inicialStart";
            })
            .catch(errors => {
                console.log("%cAxios: Error!", "color: #FFCCCB");
                console.log(errors);
            });
        },

        /* Determina que metodo de envio detallar en el modal */
        rellenarModalDetEnvio(num) {
            this.indexDetEnvio = num;
        },

        /* Determina que producto detallar en el modal */
        rellenarModalDetProducto(tipo,num) {
            this.indexProducto = [tipo,num];
        }


    },

    computed: {
        // Validación front end de los inputs de puntajes
        inicUbicErr() {
            if (this.provInicRes[0] < parseInt(this.escala["ri"]) || this.provInicRes[0] > parseInt(this.escala["rf"])) {
                return "is-invalid";
            }
            else return "";
        },

        inicPagoErr() {
            if (this.provInicRes[1] < parseInt(this.escala["ri"]) || this.provInicRes[1] > parseInt(this.escala["rf"])) {
                return "is-invalid";
            }
            else return "";
        },

        inicEnvioErr() {
            if (this.provInicRes[2] < parseInt(this.escala["ri"]) || this.provInicRes[2] > parseInt(this.escala["rf"])) {
                return "is-invalid";
            }
            else return "";
        },

        inicFinalizarErr() {
            if( this.inicUbicErr != "" || this.inicPagoErr != "" || this.inicEnvioErr != "") {
                return "btn-danger";
            }
            else return "";
        },

        inicFinalizarDis() {
            if( this.inicUbicErr != "" || this.inicPagoErr != "" || this.inicEnvioErr != "") {
                return "disabled";
            }
            else return null;
        },

    }
}
</script>

<style scoped>

</style>