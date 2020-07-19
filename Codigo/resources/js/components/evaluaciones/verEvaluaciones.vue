<template>
    <span>
        <div class="row d-flex justify-content-center">
            <span class="h5 mr-3"> Filtrar: </span>
            <input type="radio" id="todas" name="filtro" class="mt-1" value="t" checked v-model="filtro">
            <label class="h5 ml-2 mr-4" for="todas"> Todas </label>
            <input type="radio" id="iniciales" name="filtro" class="mt-1" value="i" v-model="filtro">
            <label class="h5 ml-2 mr-4" for="iniciales"> Iniciales </label>
            <input type="radio" id="anuales" name="filtro" class="mt-1" value="a" v-model="filtro">
            <label class="h5 ml-2 mr-4" for="anuales"> Anuales </label>
        </div>
        <hr>
        <table class="table table-striped border border-info">
            <thead class="bg-primary text-white"> 
                <th scope="col" class="text-center"> Fecha </th>
                <th scope="col"> Proveedor </th>
                <th scope="col"> Tipo</th>
                <th scope="col" class="text-right"> Resultado </th>
                <th class="text-right"> Criterio de Éxito </th>
                <th class="text-center"> Aprobación </th>
            </thead>
            <tbody v-if="filtro === 't'">
                <tr v-for="(res,index) in resultados" :key="index">
                    <td class="text-center"> {{ res.fecha }} </td>
                    <td> {{ res.prov }} </td>
                    <td> {{ res.tipo }} </td>
                    <td class="text-right"> {{ res.res }} % </td>
                    <td class="text-right"> {{ res.exito }} % </td>
                    <td class="text-center">
                        <span v-if="parseInt(res.res) >= parseInt(res.exito)">
                            <img src="/img/iconos/check_green.svg" width="24">
                            <span class="ml-1"> Aprobado </span>    
                        </span>
                        <span v-else>
                            <img src="/img/iconos/cancel_red.svg" width="24">
                            <span class="ml-1"> Reprobado </span>    
                        </span>    
                    </td>
                </tr>
                <tr class="text-center" v-if="resultados.length === 0"> 
                    <td colspan="7"> Aun no tiene resultados de evaluaciones. </td>
                </tr>
            </tbody>
            <tbody v-if="filtro === 'i'">
                <tr v-for="(res,index) in resultadosIniciales" :key="index">
                    <td class="text-center"> {{ res.fecha }} </td>
                    <td> {{ res.prov }} </td>
                    <td> {{ res.tipo }} </td>
                    <td class="text-right"> {{ res.res }} % </td>
                    <td class="text-right"> {{ res.exito }} % </td>
                    <td class="text-center">
                        <span v-if="parseInt(res.res) >= parseInt(res.exito)">
                            <img src="/img/iconos/check_green.svg" width="24">
                            <span class="ml-1"> Aprobado </span>    
                        </span>
                        <span v-else>
                            <img src="/img/iconos/cancel_red.svg" width="24">
                            <span class="ml-1"> Reprobado </span>    
                        </span>    
                    </td>
                </tr>
                <tr class="text-center" v-if="resultadosIniciales.length === 0"> 
                    <td colspan="7"> Aun no tiene resultados de evaluaciones iniciales. </td>
                </tr>
            </tbody>
            <tbody v-if="filtro === 'a'">
                <tr v-for="(res,index) in resultadosAnuales" :key="index">
                    <td class="text-center"> {{ res.fecha }} </td>
                    <td> {{ res.prov }} </td>
                    <td> {{ res.tipo }} </td>
                    <td class="text-right"> {{ res.res }} % </td>
                    <td class="text-right"> {{ res.exito }} % </td>
                    <td class="text-center">
                        <span v-if="parseInt(res.res) >= parseInt(res.exito)">
                            <img src="/img/iconos/check_green.svg" width="24">
                            <span class="ml-1"> Aprobado </span>    
                        </span>
                        <span v-else>
                            <img src="/img/iconos/cancel_red.svg" width="24">
                            <span class="ml-1"> Reprobado </span>    
                        </span>    
                    </td>
                </tr>
                <tr class="text-center" v-if="resultadosAnuales.length === 0"> 
                    <td colspan="7"> Aun no tiene resultados de evaluaciones anuales. </td>
                </tr>
            </tbody>
        </table>
    </span>
</template>

<script>
export default {
    data() {
        return {
            filtro: "",
            resultados: [],
            resultadosIniciales: [],
            resultadosAnuales: []
        }
    },

    created() {
        console.log("%cAxios: Buscando resultados.", "color: lightblue");
        axios.get("evaluaciones/resultados")
        .then(response => {
            console.log("%cAxios: Exito en busqueda de resultados!", "color: lightgreen");
            this.resultados = response.data[0];

            this.resultados.forEach(element => {
                if(element.tipo === 'Inicial') 
                    this.resultadosIniciales.push(element);
                else
                    this.resultadosAnuales.push(element);
            });

            this.filtro = "t";
        })
        .catch(errors => {  
            console.log("%cAxios: Error buscando resultados!", "color: #FFCCCB");
        });
    },

    methods: {

    },

    computed: {

    }
}
</script>

<style>

</style>