<template>
    <div>
        <span v-if="filtro === 1">
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
                <label> <input type="radio" name="genero" value="m"> 
                    Masculino 
                    <img src="/img/iconos/recomendador/hombre.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="genero" value="f"> 
                    Femenino 
                    <img src="/img/iconos/recomendador/mujer.svg" width="26" class="ml-1">
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="genero" value="u"> 
                    Unisex 
                    <img src="/img/iconos/recomendador/unisex.svg" width="26" class="ml-1">
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="filtro++">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="filtro === 2">
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
                <label> <input type="radio" name="edad" value="in"> 
                    Infantil
                    <span class="text-info h4"> <b> (0 a 9) </b> </span>
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="edad" value="jo"> 
                    Joven
                    <span class="text-info h4"> <b> (10 a 20) </b> </span>
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="edad" value="ad"> 
                    Adulto
                    <span class="text-info h4"> <b> (21+) </b> </span>
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="edad" value="at"> 
                    Atemporal
                    <span class="text-info h4"> <b> (Cualquiera) </b> </span>
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="filtro++">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="filtro === 3">
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
                <label> <input type="radio" name="intensidad" value="lig"> 
                    Ligera / Fresca
                    <span class="text-info h4"> <b> (Eau de Splash y Eau de Cologne) </b> </span>
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="intensidad" value="inter"> 
                    Intermedia
                    <span class="text-info h4"> <b> (Eau de Toilette) </b> </span>
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="intensidad" value="intensa"> 
                    Intensa / Profunda
                    <span class="text-info h4"> <b> (Eau de Perfume y Perfume) </b> </span>
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="filtro++">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="filtro === 4">
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
            <hr>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="caracter" value="in"> 
                    Informal
                    <img src="/img/iconos/recomendador/informal.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="caracter" value="in"> 
                    Natural
                    <img src="/img/iconos/recomendador/natural.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="caracter" value="in"> 
                    Clásico
                    <img src="/img/iconos/recomendador/clasico.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="caracter" value="in"> 
                    Seductor
                    <img src="/img/iconos/recomendador/seductor.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="caracter" value="in"> 
                    Moderno
                    <img src="/img/iconos/recomendador/moderno.svg" width="24" class="ml-1">
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="filtro++">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="filtro === 5">
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
            <hr>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" value="verde"> 
                    <b> Verde: </b>
                    Hierba, pino y tallos de plantas.                                     
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" value="citrica"> 
                    <b> Cítrica: </b>
                    Naranjas, bergamotas, limones, mandarinas y pomelos. 
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" value="floral"> 
                    <b> Floral: </b>
                    Rosa, jazmín, violetas, narcisos y lirios.
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" value="frutal"> 
                    <b> Frutal: </b>
                    Mango, higo, melocotón, pera, manzana y frambuesa. 
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" value="aromatica"> 
                    <b> Aromática: </b>
                    Lavanda, geranio, albahaca, comino, romero y salvia. 
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" value="helechos"> 
                    <b> Helechos: </b>
                    Lavandas, maderas, comino, bergamota y musgo de roble.
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" value="chipre"> 
                    <b> Chipre: </b>
                    Musgo de roble, láudano, pachuli y bergamota. 
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" value="amaderada"> 
                    <b> Amaderada: </b>
                    Sándalo, pachuli, cedro, lavanda y pino.
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" value="oriental"> 
                    <b> Oriental: </b>
                    Vainilla, láudano y esencias variadas muy fuertes.
                </label>
            </div>
            <div class="checkbox col-12 text-left h4 ml-4">
                <label> <input type="checkbox" name="familias" value="aldehidica"> 
                    <b> Aldehidica: </b>
                    Fragancias variadas que provienen de aldehidos.
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="filtro++">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="filtro === 6">
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
            <hr>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" value="floral"> 
                    Florales
                    <img src="/img/iconos/recomendador/aromas/florales.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" value="frutal"> 
                    Frutales
                    <img src="/img/iconos/recomendador/aromas/frutales.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" value="verde"> 
                    Verdes
                    <img src="/img/iconos/recomendador/aromas/verdes.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" value="herbal"> 
                    Herbales
                    <img src="/img/iconos/recomendador/aromas/herbales.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" value="citrico"> 
                    Citricos
                    <img src="/img/iconos/recomendador/aromas/citricos.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" value="herbal_ar"> 
                    Herbales Aromáticos
                    <img src="/img/iconos/recomendador/aromas/aromaticos.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" value="amaderado"> 
                    Amaderados
                    <img src="/img/iconos/recomendador/aromas/amaderados.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <div class="checkbox col-12 text-center h4">
                <label> <input type="checkbox" name="aroma" value="otros"> 
                    Otros
                    <img src="/img/iconos/recomendador/aromas/otros.svg" width="24" class="mb-1 mr-2">
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="filtro++">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="filtro === 7">
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
                <label> <input type="radio" name="uso" value="di"> 
                    Uso diario
                    <img src="/img/iconos/recomendador/diario.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="uso" value="tr"> 
                    Trabajo
                    <img src="/img/iconos/recomendador/trabajo.svg" width="24" class="ml-1">
                </label>
            </div>
            <div class="radio col-12 text-center h4">
                <label> <input type="radio" name="uso" value="es"> 
                    Ocasiones especiales
                    <img src="/img/iconos/recomendador/especial.svg" width="24" class="ml-1">
                </label>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg">
                    <img src="/img/iconos/check_white.svg" width="24" class="mb-1">
                    <span class="ml-2"> Generar resultados </span>
                </button>
                <button class="btn btn-primary mx-4 btn-lg" @click="filtro++">
                    <img src="/img/iconos/recomendador/siguiente.svg" width="24" class="mb-1">
                    <span class="ml-2"> Siguiente pregunta </span>
                </button>
            </div>
        </span>
        <span v-if="filtro === 8">
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
            <hr> 
            <div class="row">
                <div class="col-5 offset-1">
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Libertad"> 
                            Libertad                            
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Independencia"> 
                            Independencia                            
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Creatividad"> 
                            Creatividad                         
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Diversion"> 
                            Diversión                         
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Fantasia"> 
                            Fantasía
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Lucidez"> 
                            Lucidez
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Familiaridad"> 
                            Familiaridad
                        </label>
                    </div>
                </div>
                <div class="col-4 offset-2">
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Versatibilidad"> 
                            Versatibilidad                            
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Delicadeza"> 
                            Delicadeza                            
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Sensualidad"> 
                            Sensualidad                         
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Optimismo"> 
                            Optimismo
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Alegria"> 
                            Alegría
                        </label>
                    </div>
                     <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Confianza"> 
                            Confianza
                        </label>
                    </div>
                    <div class="checkbox row text-left h4 ml-4">
                        <label> <input type="checkbox" name="personalidad" value="Energía"> 
                            Energía
                        </label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success mx-4 btn-lg">
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
            filtro: 1
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