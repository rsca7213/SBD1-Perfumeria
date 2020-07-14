<template>
    <span>
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
                    <input class="mr-1" type="checkbox">
                    <label> <b> Incluir en evaluación </b> </label>
                </span>
            </div>
        </span>
    </span>
</template>

<script>
export default {
    props: ["id_prod"],

    data() {
        return {
            tipoEv: "",
            provs: null,
            provsInic: []
        }
    },

    mounted() {
        
    },

    methods: {

        evInicial() {
            console.log("%cAxios: Get!", "color: lightblue");
            axios.get("inicial")
            .then(response => {
                console.log("%cAxios: Success!", "color: lightgreen");
                console.log(this.provsInic = response.data[0]);
            })
            .catch(errors => {
                console.log("%cAxios: Error!", "color: #FFCCCB");
            });
            this.tipoEv = "inicial";
        },

        evAnual() {
            this.tipoEv = "Anual";
        }
    },

    computed: {

    }
}
</script>

<style>

</style>