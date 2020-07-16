<template>
    <span>
        <!-- Seleccion de tipo de evaluacion -->
        <span v-if="tipoEv === ''">
            <div class="row d-flex justify-content-center">
                <span class="h5 mr-3 mt-2"> Tipo de Evaluación: </span>
                <button class="btn btn-small btn-primary" @click="evInicial"> Inicial </button>
                <button class="btn btn-small btn-primary mx-4" @click="evAnual"> Anual </button>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <h5> Selecciona un tipo de evaluación... </h5>
            </div>
        </span>

        <!-- Seleccion de proveedores para evaluaciones inciales --> 
        <span v-if="tipoEv === 'inicial'">
            <div class="row d-flex justify-content-center">
                <span class="h5"> <b> Evaluación Inicial </b> </span>
            </div>
            <hr>
            <span class="h5"> <b> Seleccionar proveedores a evaluar: </b> </span>
            <hr>
            <div class="row mx-4 p-2 border border-info" v-for="prov in provsInic" :key="prov.idp">
                <span class="col-6"> <b> Proveedor: </b> {{ prov["prov"] }}</span>
                <span class="col-6"> <b> Fecha de inicio membresia IFRA: </b> {{ prov["memb"] }} </span>
                <span class="col-6"> <b> Tipo de membresia IFRA: </b> {{ prov["tipo"] }}</span>
                <span class="col-6"> 
                    <b> Asociación: </b> {{ prov["asoc"] }}
                    <span v-if="prov['asoc'] === null"> No aplica </span>
                </span>
                <span class="col-12"> <b> Paises de envío: </b> 
                    <span v-for="(pais, index) in prov['paises']" :key="pais"> 
                        <span> {{ pais }} </span>
                        <span v-if="!(prov['paises'].length - 1 === index)">,</span>
                    </span>
                </span>
                <span class="col-12 text-center"> 
                    <input class="mr-1" type="checkbox" @click="seleccionarProvInicial(prov['idp'])">
                    <label> <b> Incluir en evaluación </b> </label>
                </span>
            </div>
            <div class="row d-flex justify-content-center mt-4" v-if="provsInic != []">
                <button class="btn btn-primary" @click="dataInicial" :class="botonIniciarEvInicial" :disabled="botonIniciarEvInicialDis">
                    <img src="/img/iconos/check_white.svg" alt="continuar" width="24" class="mb-1">
                    <span class="ml-2"> Continuar </span>
                </button>
            </div>
        </span>

        <!-- Proceso de evaluacion inicial -->
        <span v-if="tipoEv === 'inicialStart'">
            <div v-for="(prov,index) in provsInicData" :key="prov.idp">
                <span v-if="provActual === index"> 
                    <div class="row d-flex justify-content-center">
                        <span class="h5 col-12 text-center"> <b> Proveedor: </b> {{ prov["prov"] }} </span>
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
                                <th scope="col" style="background-color: #E4E4E4" class="border border-info"> {{ prov["pais"] }} </th>
                            </thead>
                        </table>
                    </div>
                    <div class="flex-row d-flex justify-content-center mx-4 align-items-center">
                        <span class="mr-2" style="font-size: 16px"> <b> Puntaje: </b> </span> 
                        <input type="number" min="0" max="5" class="form-control w-25" :id="prov.idp + 'ubic'">
                        <span class="ml-2" style="font-size: 16px"> 
                            <b> (de {{escala.ri}} a {{escala.rf}}) </b> 
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
                                <tr v-for="pago in prov['pagos']" :key="pago.id">
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
                        <input type="number" min="0" max="5" class="form-control w-25" :id="prov.idp + 'pago'">
                        <span class="ml-2" style="font-size: 16px"> 
                            <b> (de {{escala.ri}} a {{escala.rf}}) </b> 
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
                                <tr v-for="(envio,index) in prov['envios']" :key="envio.id_envio">
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
                        <input type="number" min="0" max="5" class="form-control w-25" :id="prov.idp + 'pago'">
                        <span class="ml-2" style="font-size: 16px"> 
                            <b> (de {{escala.ri}} a {{escala.rf}}) </b> 
                        </span>
                    </div>
                    <hr class="py-0 my-2">
                    <div class="row d-flex justify-content-center mt-2">
                        <button class="btn btn-outline-primary mx-4" @click="provAnterior"
                        v-if="index != 0"> Proveedor Anterior </button>
                        <button class="btn btn-outline-primary mx-4" @click="provSiguiente" 
                        v-if="index != provsInicData.length - 1"> Siguiente Proveedor </button>
                        <button class="btn btn-primary mx-4" v-if="index === provsInicData.length - 1"> Terminar Evaluación </button>
                    </div>
                </span>
            </div>
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
                        <tbody v-if="provsInicData[provActual]['envios'][indexDetEnvio]['detalles'] != []">
                            <tr v-for="det in provsInicData[provActual]['envios'][indexDetEnvio]['detalles']" :key="det.id"> 
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
    </span>
</template>

<script>
export default {

    data() {
        return {
            tipoEv: "", /*encargada de determinar el tipo de evaluacion a realizar
                            "" = sin seleccion
                            inicial = seleccion de proveedores para ev inicial
                            inicialStart = proceso de evaluacion inicial
                            anual = seleccion de proveedores para ev anual
                            anualStart = proceso de evaluacion anual
                        */
            provsInic: [], /*data que devuelve laravel para seleccionar los proveedores
                             de la evaluacion inicial */
            provsInicSelec: [], /*arreglo que contiene las ids de los proveedores seleccionados
                                 para una evaluacion inicial, se envian a laravel */
            provsInicRes: [], /*arreglo que contiene los resultados que se van asignando
                               a los proveedores en la evaluacion inicial */
            provsInicData: [],

            formInic: [], /* Formula de evaluacion inicial del productor */

            escala: [], /* Escala de evaluacion del productor */

            provActual: 0, /* Variable auxiliar que determina el proveedor que se esta
                              evaluando actualmente */

            indexDetEnvio: 0 /* Variable auxiliar que determina el metodo de envio que se
                                quiere detallar */

        }
    },

    methods: {

        /* Funcion AXIOS que busca la data necesaria para realizar la seleccion
           de proveedores para una evaluacion inicial y almacena que el tipo de
           ev a realizar es de tipo inicial*/
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
            this.tipoEv = "Anual";
        },

        /* Cuando se selecciona un proveedor en la seleccion de proveedores
           para evaluaciones iniciales se llama a este metodo para registrar
           la id del proveedor seleccionada */
        seleccionarProvInicial(idp) {
            for (let i = 0; i <= this.provsInicSelec.length; i++) {
                if(this.provsInicSelec[i] === idp) {
                    this.provsInicSelec.splice(i,1);
                    return;
                }
            }
            this.provsInicSelec.push(idp);
        },

        /* Funcion AXIOS que le pide a laravel toda la data necesaria para
           llevar a cabo una evaluacion inicial, envia las ids de los proveedores
           de los cuales necesita data */
        dataInicial() {
            console.log("%cAxios: Get! Buscando data para evs iniciales", "color: lightblue");
            axios.post("data/inicial",{
                provs: this.provsInicSelec
            })
            .then(response => {
                console.log("%cAxios: Success!", "color: lightgreen");
                this.provsInicData = response.data[0];
                this.formInic = response.data[1];
                this.escala = response.data[2];
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

        /* Cambia de proveedor al siguiente */
        provSiguiente() {
            this.provActual++;  
        },

        /* Cambia de proveedor al anterior */
        provAnterior() {
            this.provActual--;
        }
    },

    computed: {
        /* Validacion front end que no permite que se continue con la
           evaluacion inicial hasta que se seleccione un proveedor al menos */
        botonIniciarEvInicial() {
            if(this.provsInicSelec.length === 0) {
                this.botonIniciarEvInicialDis = "disabled";
                return "disabled";
            }
            else {
                this.botonIniciarEvInicialDis = null;
                return "";
            }
        }
    }
}
</script>

<style scoped>

</style>