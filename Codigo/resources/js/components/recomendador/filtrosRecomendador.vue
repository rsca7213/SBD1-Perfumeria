<template>
    <div>
        <span v-if="pregunta === 1">
            <div class="row d-flex justify-content-center h3">
                <b class="text-primary mr-2"> Pregunta #1: </b> Género
            </div>
            <div class="progress my-2 border border-light" style="height: 20px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 12.5%">
                    1 de 8
                </div>
            </div>
            <div class="row d-flex justify-content-center h4">
                <b class="mr-2"> ¿Para qué género es tu perfume ideal? </b>
            </div>
            <hr>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="genero" value="m" v-model="respuestas[0]"> 
                    Masculino 
                    <img src="/img/iconos/recomendador/hombre.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="genero" value="f" v-model="respuestas[0]"> 
                    Femenino 
                    <img src="/img/iconos/recomendador/mujer.svg" width="26" class="ml-1">
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="genero" value="u" v-model="respuestas[0]"> 
                    Unisex 
                    <img src="/img/iconos/recomendador/unisex.svg" width="26" class="ml-1">
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg" @click="validarEnvio">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="sigPregunta">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="pregunta === 2">
            <div class="row d-flex justify-content-center h3">
                <b class="text-primary mr-2"> Pregunta #2: </b> Edad
            </div>
            <div class="progress my-2 border border-light" style="height: 20px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 25%">
                    2 de 8
                </div>
            </div>
            <div class="row d-flex justify-content-center h4">
                <b class="mr-2"> ¿Qué rango de edad debe tener tu perfume ideal? </b>
            </div>
            <hr>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="edad" value="in" v-model="respuestas[1]"> 
                    Infantil
                    <span class="text-info h4"> <b> (0 a 9) </b> </span>
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="edad" value="jo" v-model="respuestas[1]"> 
                    Joven
                    <span class="text-info h4"> <b> (10 a 20) </b> </span>
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="edad" value="ad" v-model="respuestas[1]"> 
                    Adulto
                    <span class="text-info h4"> <b> (21+) </b> </span>
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="edad" value="at" v-model="respuestas[1]"> 
                    Atemporal
                    <span class="text-info h4"> <b> (Cualquiera) </b> </span>
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg" @click="validarEnvio">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="sigPregunta">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="pregunta === 3">
            <div class="row d-flex justify-content-center h3">
                <b class="text-primary mr-2"> Pregunta #3: </b> Intensidad
            </div>
            <div class="progress my-2 border border-light" style="height: 20px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 37.5%">
                    3 de 8
                </div>
            </div>
            <div class="row d-flex justify-content-center h4">
                <b class="mr-2"> ¿Cual es la intensidad de olor que deseas en tu perfume ideal? </b>
            </div>
            <hr>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="intensidad" value="lig" v-model="respuestas[2]"> 
                    Ligera / Fresca
                    <span class="text-info h4"> <b> (Eau de Splash y Eau de Cologne) </b> </span>
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="intensidad" value="inter" v-model="respuestas[2]"> 
                    Intermedia
                    <span class="text-info h4"> <b> (Eau de Toilette) </b> </span>
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="intensidad" value="intensa" v-model="respuestas[2]"> 
                    Intensa / Profunda
                    <span class="text-info h4"> <b> (Eau de Perfume y Perfume) </b> </span>
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg" @click="validarEnvio">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="sigPregunta">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="pregunta === 4">
            <div class="row d-flex justify-content-center h3">
                <b class="text-primary mr-2"> Pregunta #4: </b> Carácter
            </div>
            <div class="progress my-2 border border-light" style="height: 20px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 50%">
                    4 de 8
                </div>
            </div>
            <div class="row d-flex justify-content-center h4">
                <b class="mr-2"> ¿Cúal es el carácter que deseas que genere tu perfume ideal? </b>
            </div>
            <div class="row d-flex justify-content-center h6">
                <b class="mr-2"> Nota: Puedes seleccionar varios caracteres. </b>
            </div>
            <div class="row d-flex justify-content-center h5">
                <b class="mr-2 text-danger" v-text="textoErr"> </b>
            </div>
            <hr>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="caracter" value="Informal" v-model="respuestas[3]"> 
                    Informal
                    <img src="/img/iconos/recomendador/informal.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="caracter" value="Natural" v-model="respuestas[3]"> 
                    Natural
                    <img src="/img/iconos/recomendador/natural.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="caracter" value="Clasico" v-model="respuestas[3]"> 
                    Clásico
                    <img src="/img/iconos/recomendador/clasico.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="caracter" value="Seductor" v-model="respuestas[3]"> 
                    Seductor
                    <img src="/img/iconos/recomendador/seductor.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="caracter" value="Moderno" v-model="respuestas[3]"> 
                    Moderno
                    <img src="/img/iconos/recomendador/moderno.svg" width="24" class="ml-1">
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg" @click="validarEnvio">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="sigPregunta">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="pregunta === 5">
             <div class="row d-flex justify-content-center h3">
                <b class="text-primary mr-2"> Pregunta #5: </b> Familias Olfativas
            </div>
            <div class="progress my-2 border border-light" style="height: 20px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 62.5%">
                    5 de 8
                </div>
            </div>
            <div class="row d-flex justify-content-center h4">
                <b class="mr-2"> ¿Qué familias de olores debería tener tu perfume ideal? </b>
            </div>
             <div class="row d-flex justify-content-center h6">
                <b class="mr-2"> Nota: Puedes seleccionar varias familias olfativas. </b>
            </div>
            <div class="row d-flex justify-content-center h5">
                <b class="mr-2 text-danger" v-text="textoErr"> </b>
            </div>
            <hr>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" v-model="respuestas[4]" value="Verde"> 
                    <b> Verde: </b>
                    Hierba, pino y tallos de plantas.                                     
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" v-model="respuestas[4]" value="Citrica"> 
                    <b> Cítrica: </b>
                    Naranjas, bergamotas, limones, mandarinas y pomelos. 
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" v-model="respuestas[4]" value="Floral"> 
                    <b> Floral: </b>
                    Rosa, jazmín, violetas, narcisos y lirios.
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" v-model="respuestas[4]" value="Frutal"> 
                    <b> Frutal: </b>
                    Mango, higo, melocotón, pera, manzana y frambuesa. 
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" v-model="respuestas[4]" value="Aromatica"> 
                    <b> Aromática: </b>
                    Lavanda, geranio, albahaca, comino, romero y salvia. 
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" v-model="respuestas[4]" value="Helechos"> 
                    <b> Helechos: </b>
                    Lavandas, maderas, comino, bergamota y musgo de roble.
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" v-model="respuestas[4]" value="Chipre"> 
                    <b> Chipre: </b>
                    Musgo de roble, láudano, pachuli y bergamota. 
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" v-model="respuestas[4]" value="Amaderada"> 
                    <b> Amaderada: </b>
                    Sándalo, pachuli, cedro, lavanda y pino.
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" v-model="respuestas[4]" value="Oriental"> 
                    <b> Oriental: </b>
                    Vainilla, láudano y esencias variadas muy fuertes.
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" v-model="respuestas[4]" value="Aldehidica"> 
                    <b> Aldehidica: </b>
                    Fragancias variadas que provienen de aldehidos.
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg" @click="validarEnvio">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="sigPregunta">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="pregunta === 6">
            <div class="row d-flex justify-content-center h3">
                <b class="text-primary mr-2"> Pregunta #6: </b> Aromas importantes
            </div>
            <div class="progress my-2 border border-light" style="height: 20px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 75%">
                    6 de 8
                </div>
            </div>
            <div class="row d-flex justify-content-center h4">
                <b class="mr-2"> ¿Cúales son los aromas que deben prevalecer en tu perfume ideal? </b>
            </div>
            <div class="row d-flex justify-content-center h6">
                <b class="mr-2"> Nota: Puedes seleccionar varios aromas. </b>
            </div>
            <div class="row d-flex justify-content-center h5">
                <b class="mr-2 text-danger" v-text="textoErr"> </b>
            </div>
            <hr>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" v-model="respuestas[5]" value="Floral"> 
                    Florales
                    <img src="/img/iconos/recomendador/aromas/florales.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" v-model="respuestas[5]" value="Frutal"> 
                    Frutales
                    <img src="/img/iconos/recomendador/aromas/frutales.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" v-model="respuestas[5]" value="Verde"> 
                    Verdes
                    <img src="/img/iconos/recomendador/aromas/verdes.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" v-model="respuestas[5]" value="Herbal"> 
                    Herbales
                    <img src="/img/iconos/recomendador/aromas/herbales.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" v-model="respuestas[5]" value="Citrico"> 
                    Citricos
                    <img src="/img/iconos/recomendador/aromas/citricos.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" v-model="respuestas[5]" value="Herbal aromatico"> 
                    Herbales Aromáticos
                    <img src="/img/iconos/recomendador/aromas/aromaticos.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" v-model="respuestas[5]" value="Amaderado"> 
                    Amaderados
                    <img src="/img/iconos/recomendador/aromas/amaderados.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" v-model="respuestas[5]" value="Otros"> 
                    Otros
                    <img src="/img/iconos/recomendador/aromas/otros.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg" @click="validarEnvio">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="sigPregunta">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="pregunta === 7">
           <div class="row d-flex justify-content-center h3">
                <b class="text-primary mr-2"> Pregunta #7: </b> Preferencias de Uso
            </div>
            <div class="progress my-2 border border-light" style="height: 20px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 87.5%">
                    7 de 8
                </div>
            </div>
            <div class="row d-flex justify-content-center h4">
                <b class="mr-2"> ¿Cúando deseas usar tu perfume ideal? </b>
            </div>
            <hr> 
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="uso" value="di" v-model="respuestas[6]"> 
                    Uso diario
                    <img src="/img/iconos/recomendador/diario.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="uso" value="tr" v-model="respuestas[6]"> 
                    Trabajo
                    <img src="/img/iconos/recomendador/trabajo.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="uso" value="es" v-model="respuestas[6]"> 
                    Ocasiones especiales
                    <img src="/img/iconos/recomendador/especial.svg" width="24" class="ml-1">
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg" @click="validarEnvio">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="sigPregunta">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="pregunta === 8">
            <div class="row d-flex justify-content-center h3">
                <b class="text-primary mr-2"> Pregunta #8: </b> Aspectos de Personalidad
            </div>
            <div class="progress my-2 border border-light" style="height: 20px">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%">
                    8 de 8
                </div>
            </div>
            <div class="row d-flex justify-content-center h4">
                <b class="mr-2"> ¿Cúales son los aspectos de tu personalidad que deseas que el perfume emita? </b>
            </div>
            <div class="row d-flex justify-content-center h6">
                <b class="mr-2"> Nota: Puedes seleccionar varios aspectos de personalidad. </b>
            </div>
            <div class="row d-flex justify-content-center h5">
                <b class="mr-2 text-danger" v-text="textoErr"> </b>
            </div>
            <hr> 
            <div class="row">
                <div class="col-5 offset-1">
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Libertad"> 
                            Libertad                            
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Independencia"> 
                            Independencia                            
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Creatividad"> 
                            Creatividad                         
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Diversion"> 
                            Diversión                         
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Fantasia"> 
                            Fantasía
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Lucidez"> 
                            Lucidez
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Familiaridad"> 
                            Familiaridad
                        </label>
                    </div>
                </div>
                <div class="col-4 offset-2">
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Versatibilidad"> 
                            Versatibilidad                            
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Delicadeza"> 
                            Delicadeza                            
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Sensualidad"> 
                            Sensualidad                         
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Optimismo"> 
                            Optimismo
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Alegria"> 
                            Alegría
                        </label>
                    </div>
                     <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Confianza"> 
                            Confianza
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" v-model="respuestas[7]" value="Energia"> 
                            Energía
                        </label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg" @click="validarEnvio">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
            </div>
        </span>
    </div>
</template>

<script>
export default {
    data() {
        return {
            pregunta: 1, /* Variable que determina por cual pregunta va el usuario */
            respuestas: ["m","in","lig",[],[],[],"di",[]], /* Variable que va almacenando las respuestas del usuario */
            textoErr: "" /* Variable que almacena texto para mostrar al realizar validacion front end */
        }
    },

    methods: {

        enviarRespuestas() {
            console.log("%cAxios: Enviando respuestas!", "color: lightblue");
            axios.post("resultados", 
                {
                    'pregunta': this.pregunta,
                    'respuestas': this.respuestas
                }
            )
            .then((response) => {
                console.log("%cAxios: Resultados recibidos!", "color: lightgreen");
                console.log(response.data);
            })
            .catch((errors) => {
                console.log("%cAxios: Error!", "color: #FFCCCB");
                console.log(errors);
            });
        },

        sigPregunta() {
            if(this.pregunta === 4 && this.respuestas[3].length === 0) {
                this.textoErr = "Debe seleccionar al menos 1 carácter.";
            }
            else if(this.pregunta === 5 && this.respuestas[4].length === 0) {
                this.textoErr = "Debe seleccionar al menos 1 familia olfativa.";
            }
            else if(this.pregunta === 6 && this.respuestas[5].length === 0) {
                this.textoErr = "Debe seleccionar al menos 1 aroma.";
            } 
            else if(this.pregunta === 8 && this.respuestas[7].length === 0) {
                this.textoErr = "Debe seleccionar al menos 1 aspecto de personalidad.";
            }
            else {
                this.textoErr = "";
                this.pregunta++;
            }
        },

        validarEnvio() {
            if(this.pregunta === 4 && this.respuestas[3].length === 0) {
                this.textoErr = "Debe seleccionar al menos 1 carácter.";
            }
            else if(this.pregunta === 5 && this.respuestas[4].length === 0) {
                this.textoErr = "Debe seleccionar al menos 1 familia olfativa.";
            }
            else if(this.pregunta === 6 && this.respuestas[5].length === 0) {
                this.textoErr = "Debe seleccionar al menos 1 aroma.";
            } 
            else if(this.pregunta === 8 && this.respuestas[7].length === 0) {
                this.textoErr = "Debe seleccionar al menos 1 aspecto de personalidad.";
            }
            else {
                this.textoErr = "";
                this.enviarRespuestas();
            }
        }
    }
}
</script>

<style scoped>
    input[type=radio] {
        width: 18px;
        height: 18px;
    }
    input[type=checkbox] {
        width: 18px;
        height: 18px;
    }
</style>