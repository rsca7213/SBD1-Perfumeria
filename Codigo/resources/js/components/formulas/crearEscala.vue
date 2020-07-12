<template>
    <span>
        <h5> Asignación de rangos de escala: </h5>
        <hr>
        <form :action="link" method="POST" class="row justify-content-center">
            <input type="hidden" name="_token" :value="this.csrf">
            <div class="form-group px-4 mx-4 justify-content-center col-7">
                <label for="ri" class="mt-2 px-4"> Rango inicial </label>
                <input type="number" class="form-control" id="ri" required :class="riInputErr" 
                v-model.number="riInp" placeholder="Número de 0 a 3 digitos..." name="ri">
                <small class="form-text text-danger"> <b v-text="smallRi"> </b> </small>
            </div>
            <div class="form-group px-4 mx-4 justify-content-center col-7">
                <label for="rf" class="mt-2 px-4"> Rango final </label>
                <input type="number" class="form-control" id="rf" required :class="rfInputErr" 
                v-model.number="rfInp" placeholder="Número de 0 a 3 digitos..." name="rf">
                <small class="form-text text-danger"> <b v-text="smallRf"> </b> </small>
            </div>
            <div class="form-group text-center col-12">
                <button type="submit" class="btn btn-primary" :class="submitErr" :disabled="submitErrDis">
                    <img src="/img/iconos/add_white.svg" alt="crear" width="24" class="mb-1">
                    <span class="ml-2"> Crear Escala </span>
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
                riInp: null,
                rfInp: null,
                link: "/productor/" + this.id_prod + "/escala/crear"
            };
        },

        computed: {
            riInputErr() {
                if((this.riInp > 999 || this.riInp < 0) && (this.riInp != null)) {
                    this.smallRi = "No debe ser mayor a 999 o menor a 0.";
                    return "is-invalid";
                }
                else {
                    this.smallRi = "";
                    return "";
                }
                
            },

            rfInputErr() {
                if((this.rfInp > 999 || this.rfInp < 0) && (this.rfInp != null)) {
                    this.smallRf = "No debe ser mayor a 999 o menor a 0.";
                    return "is-invalid";
                }
                else {
                    this.smallRf = "";
                    return "";
                }
            },

            submitErr() {
                if(this.riInputErr === "is-invalid" || this.rfInputErr === "is-invalid")
                {
                    this.smallBtn = "";
                    this.submitErrDis="disabled";
                    return "btn-danger";
                }
                else if(this.riInp >= this.rfInp && this.riInp != null && this.rfInp != null) {
                    this.smallBtn = "El rango final debe ser mayor al rango inicial.";
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
