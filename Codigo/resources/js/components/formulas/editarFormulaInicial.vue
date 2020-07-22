<template>
    <span>
        <h5> Asignación de porcentajes a cada criterio: </h5>
        <hr>
        <form :action="link" method="POST" class="row justify-content-center">
            <input type="hidden" name="_token" :value="this.csrf">
            <input type="hidden" name="_method" value="PATCH">
            <div class="form-group px-4 mx-4 justify-content-center col-7">
                <label for="ubicacion" class="mt-2 px-4"> Ubicación </label>
                <input type="number" class="form-control" step="0.01" id="ubicacion" required :class="ubicInputErr" 
                v-model.number="ubicInp" placeholder="Porcentaje..." name="ubicacion">
                <small class="form-text text-danger"> <b v-text="smallUbic"> </b> </small>
            </div>
            <div class="form-group px-4 mx-4 justify-content-center col-7">
                <label for="pagos" class="mt-2 px-4"> Metodos de pago </label>
                <input type="number" class="form-control" step="0.01" id="pagos" required :class="pagosInputErr" 
                v-model.number="pagosInp" placeholder="Porcentaje..." name="pagos">
                <small class="form-text text-danger"> <b v-text="smallPago"> </b> </small>
            </div>
            <div class="form-group px-4 mx-4 justify-content-center col-7">
                <label for="envios" class="mt-2 px-4"> Metodos de envío </label>
                <input type="number" class="form-control" step="0.01" id="envios" required :class="enviosInputErr"
                 v-model.number="enviosInp" placeholder="Porcentaje..." name="envios"> 
                <small class="form-text text-danger"> <b v-text="smallEnv"> </b> </small>
            </div>
            <div class="form-group px-4 mx-4 justify-content-center col-7">
                <label for="exito" class="mt-2 px-4"> Criterio de éxito </label>
                <input type="number" class="form-control" step="0.01" id="exito" required :class="exitoInputErr"
                 v-model.number="exitoInp" placeholder="Porcentaje..." name="exito"> 
                <small class="form-text text-danger"> <b v-text="smallEx"> </b> </small>
            </div>
            <div class="form-group text-center col-12">
                <button type="submit" class="btn btn-primary" :class="submitErr" :disabled="submitErrDis">
                    <img src="/img/iconos/cambiar_white.svg" alt="crear" width="24" class="mb-1">
                    <span class="ml-2"> Crear Nueva Formula </span>
                </button>
                <br>
                <small class="text-danger"> <b v-text="smallBtn"> </b> </small>
            </div>
        </form>
    </span>
</template>

<script>
    export default {
        props: ["id_prod", "csrf", "ubic", "pagos", "envios", "exito"],

        data() {
            return {
                ubicInp: null,
                enviosInp: null,
                pagosInp: null,
                exitoInp: null,
                link: "/productor/" + this.id_prod + "/formulas/cambiar/inicial"
            };
        },

        computed: {
            ubicInputErr() {
                if((this.ubicInp > 100 || this.ubicInp < 0) && (this.ubicInp != null)) {
                    this.smallUbic = "No debe ser mayor a 100 o menor a 0.";
                    return "is-invalid";
                }
                else {
                    this.smallUbic = "";
                    return "";
                }
                
            },

            pagosInputErr() {
                if((this.pagosInp > 100 || this.pagosInp < 0) && (this.pagosInp != null)) {
                    this.smallPago = "No debe ser mayor a 100 o menor a 0.";
                    return "is-invalid";
                }
                else {
                    this.smallPago = "";
                    return "";
                }
            },

            enviosInputErr() {
                if((this.enviosInp > 100 || this.enviosInp < 0) && (this.enviosInp != null)) {
                    this.smallEnv = "No debe ser mayor a 100 o menor a 0.";
                    return "is-invalid";
                }
                else {
                    this.smallEnv = "";
                    return "";
                }
            },

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
                if(this.ubicInputErr === "is-invalid" || this.pagosInputErr === "is-invalid" || 
                this.enviosInputErr === "is-invalid" || this.exitoInputErr === "is-invalid")
                {
                    this.smallBtn = "";
                    this.submitErrDis="disabled";
                    return "btn-danger";
                }
                else if(this.enviosInp + this.pagosInp + this.ubicInp > 100 && 
                this.enviosInp != null && this.pagosInp != null && this.ubicInp != null
                && this.enviosInp != "" && this.pagosInp != "" && this.ubicInp != "") {
                    this.smallBtn = "Los criterios no deben sumar mas de 100%";
                    this.submitErrDis="disabled";
                    return "btn-danger";
                }
                else if(this.enviosInp + this.pagosInp + this.ubicInp != 100 
                && this.enviosInp != null && this.pagosInp != null && this.ubicInp != null
                && this.enviosInp != "" && this.pagosInp != "" && this.ubicInp != "") {
                    this.smallBtn = "Los criterios deben sumar 100%";
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
