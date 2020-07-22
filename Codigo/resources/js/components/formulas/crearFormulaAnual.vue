<template>
    <span>
        <h5> Asignación de porcentajes a cada criterio: </h5>
        <hr>
        <form :action="link" method="POST" class="row justify-content-center">
            <input type="hidden" name="_token" :value="this.csrf">
            <input type="hidden" name="_method" value="POST">
            <div class="form-group px-4 mx-4 justify-content-center col-7">
                <label for="cumplim" class="mt-2 px-4"> Cumplimiento de pedidos </label>
                <input type="number" class="form-control" step="0.01" id="cumplim" required :class="cumplimInputErr"
                 v-model.number="cumplimInp" placeholder="Porcentaje..." name="cumplim" readonly> 
                <small class="form-text text-danger"> <b v-text="smallCum"> </b> </small>
            </div>
            <div class="form-group px-4 mx-4 justify-content-center col-7">
                <label for="exito" class="mt-2 px-4"> Criterio de éxito </label>
                <input type="number" class="form-control" step="0.01" id="exito" required :class="exitoInputErr"
                 v-model.number="exitoInp" placeholder="Porcentaje..." name="exito"> 
                <small class="form-text text-danger"> <b v-text="smallEx"> </b> </small>
            </div>
            <div class="form-group text-center col-12">
                <button type="submit" class="btn btn-primary" :class="submitErr" :disabled="submitErrDis">
                    <img src="/img/iconos/add_white.svg" alt="crear" width="24" class="mb-1">
                    <span class="ml-2"> Crear Formula </span>
                </button>
                <br>
                <small class="text-danger"> <b v-text="smallBtn"> </b> </small>
            </div>
        </form>
    </span>
</template>

<script>
    export default {
        props: ["id_prod", "csrf"],

        data() {
            return {
                exitoInp: null,
                cumplimInp: 100,
                link: "/productor/" + this.id_prod + "/formulas/crear/anual"
            };
        },

        computed: {

            exitoInputErr() {
                if((this.exitoInp > 100 || this.exitoInp < 0) && (this.exitoInp != null)) {
                    this.smallEx = "No debe ser mayor a 100 o menor a 0.";
                    return "is-invalid";
                }
                else {
                    this.smallEx = "";
                    return "";
                }
            },

            submitErr() {
                if(this.exitoInputErr === "is-invalid")
                {
                    this.smallBtn = "";
                    this.submitErrDis="disabled";
                    return "btn-danger";
                }
                else {
                    this.submitErrDis = null;
                    this.smallBtn = "";
                    return "";
                }
            }
        }
    }
</script>
