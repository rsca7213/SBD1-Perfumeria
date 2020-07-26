<template>
  <div class="d-flex flex-column align-items-center col-12">
    <div class="d-flex justify-content-around col-9 my-2 pl-3">
      <label style="font-weight:bold;" for="envios">Método de envío</label>
      <select
        placeholder="Metodo de envio"
        v-model="envioAusar"
        class="combo"
        name="envios"
        id="envios"
        style="outline:none;"
        @change="extrasSeleccionados=[]"
      >
        <option selected="selected" value="0" class="combo">Selecciona un Metodo de Envio</option>
        <option
          v-for="(envio,index) in envios"
          :key="index"
          :value="envio.idenvio"
          class="combo"
        >{{nombreEnvio(envio.tipoenvio,envio.paisenvio,envio.precioenvio,envio.duracionenvio)}}</option>
      </select>
    </div>
    <div class="d-flex flex-column col-9 my-2 align-items-center">
      <div class="d-flex justify-content-around col-12 my-2">
        <label class="mr-5" style="font-weight:bold;" for="detalleEnvio">Extras de envío</label>
        <button
          class="btn btn-primary"
          :disabled="envioAusar==0"
          style="width:340px;"
          data-toggle="modal"
          data-target="#extraEnvio"
          @click="extrasEnvios()"
        >
          <img class="pr-2" src="/img/iconos/add_white.svg" alt="crear" width="24" /> Agregar Extra de Envío
        </button>
      </div>

      <!--<table class="align-self-center col-2" v-if="extrasSeleccionados.length!=0">
        <tr v-for="(extraSeleccionado,index) in extrasSeleccionados" :key="index">
          <th class="text-right">Nombre</th>
          <th class="text-right">Precio</th>
          <th class="text-right">Duracion</th>
        </tr>
      </table>-->
      <div class="col-10 p-0">
        <table
          class="table table-striped border border-info align-self-start col-12 pl-5 mt-2"
          v-if="extrasSeleccionados.length!=0"
        >
          <thead class="bg-primary text-white">
            <tr class="text-center">
              <th class="text-left" scope="col">Nombre</th>
              <th class="text-right" scope="col">Precio</th>
              <th class="text-right" scope="col">Duración</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(extraSeleccionado,index) in extrasSeleccionados" :key="index">
              <th class="text-left">Nombre</th>
              <th class="text-right">Precio</th>
              <th class="text-right">Duracion</th>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="modal fade" id="extraEnvio" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content" style="background-color: #F5F5F5">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Extras De Envío</h5>
              <button
                @click="extrasSeleccionados=[]"
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body h5 text-center">
              <span
                class="font-weight-bold"
                v-if="envioAusar != null && envioAusar != 0"
              >{{nombreEnvioDetalle()}}</span>
              <br />
              <br />
              <table class="table table-striped border border-info">
                <thead class="bg-primary text-white">
                  <tr class="text-center">
                    <th class="text-left" scope="col">Nombre</th>
                    <th class="text-right" scope="col">Precio</th>
                    <th class="text-right" scope="col">Duración</th>
                    <th class="text-center" scope="col">Seleccionar</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(extra,index) in extrasAusar" :key="index">
                    <td class="text-left">{{extra.nombre}}</td>
                    <td class="text-right">{{extra.precio + " $"}}</td>
                    <td class="text-right">{{duracion(extra.duracion)}}</td>
                    <td class="text-center">
                      <input
                        :checked="estaSeleccionado(extra.id)"
                        @change="agregarQuitarExtra(extra.id)"
                        type="checkbox"
                        id
                        value
                      />
                      <br />
                    </td>
                  </tr>
                </tbody>
              </table>
              <button
                class="btn btn-primary"
                style="width:110px;"
                data-dismiss="modal"
                aria-label="Close"
              >Aceptar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-around col-9 my-2 pl-3">
      <label style="font-weight:bold;" for="pagos">Método de pago</label>
      <select class="combo" name="pagos" id="pagos" style="outline:none;">
        <option class="combo" v-for="(pago,index) in pagos" :key="index">{{detallePago(pago)}}</option>
      </select>
    </div>
    <div class="align-items-center h5 text-center mt-3">
      <b class="text-center">Productos Contratados</b>
    </div>
    <table class="table table-striped border border-info col-12">
      <thead class="bg-primary text-white">
        <th scope="col" class="text-left"># Cas</th>
        <th scope="col" class="text-left">Ingrediente</th>
        <th scope="col" class="text-left">Tipo de Producto</th>
        <th scope="col" class="text-right">Precio Unitario</th>
        <th class="text-center">Cantidad</th>
        <th class="text-right">Descuento</th>
        <th class="text-right" style="width:120px;">Subtotal</th>
      </thead>
      <tbody>
        <tr v-for="(producto,index) in productos" :key="index">
          <td class="text-left">{{producto.ncas}}</td>
          <td class="text-left">{{ producto.nombreprod + " (" + producto.presentacion+ " ml)" }}</td>
          <td class="text-left">{{ producto.tipo}}</td>
          <td class="text-right">{{producto.precioing + " $"}}</td>
          <td class="text-center">
            <input
              style="width: 80px;"
              type="number"
              min="0"
              max="9999"
              id
              name
              class="text-right"
              v-model.number="cantidad[index]"
            />
          </td>
          <td
            v-if="producto.descuento_producto != null"
            class="text-right"
          >{{producto.descuento_producto + " %"}}</td>
          <td v-else class="text-right">{{"0 %"}}</td>
          <td
            style="width:120px;"
            v-if="cantidad[index]!=null"
            class="text-right"
          >{{(producto.precioing*cantidad[index] - (producto.descuento_producto/100) *cantidad[index]* producto.precioing ) + " $"}}</td>
          <td style="width:120px;" class="text-right" v-else>0 $</td>
        </tr>
      </tbody>
    </table>

    <div class="row d-flex justify-content-center col-6">
      <a class="btn btn-primary mx-4 btn-lg col-8" href="#">
        <img src="/img/iconos/evaluation.svg" alt="inicial" width="24" class />
        <span class="ml-2">Realizar Pedido</span>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  props: ["csrf"],
  data() {
    return {
      envios: [] /* Todos los envios que posee el contrato respectivo */,
      pagos: [] /* Todos los pagos que posee el contrato respectivo */,
      extras: [] /* Todos los extras de envios que posee el contrato respectivo */,
      productos: [] /* Todos los productos que posee el contrato respectivo */,
      cantidad: [] /* Cantidad de cada producto*/,
      extrasAusar: [] /* Extras de envio a usar en el pedido*/,
      envioAusar: 0 /* Id del envio a usar*/,
      extrasSeleccionados: [],
    };
  },

  created() {
    console.log(
      "%cAxios: Buscando metodos de envio y metodos de pago.",
      "color: lightblue"
    );
    axios
      .get(window.location.pathname + "/enviosPagos")
      .then((response) => {
        this.envios = response.data[0];
        this.pagos = response.data[1];
        this.extras = response.data[2];
        this.productos = response.data[3];
      })
      .catch((errors) => {
        console.log(
          "%cAxios: Error buscando metodos de envio y metodos de pago",
          "color: #FFCCCB"
        );
      });
  },

  methods: {
    /* Funcion para obtener el nombre del envio*/
    nombreEnvio(tipoEnvio, paisEnvio, precioEnvio, duracionEnvio) {
      return (
        this.tipoEnvio(tipoEnvio) +
        " - " +
        paisEnvio +
        " (" +
        precioEnvio +
        "$ - " +
        this.duracion(duracionEnvio) +
        ")"
      );
    },
    /* Funcion para colocar el nombre del envio en el modal extra envio*/
    nombreEnvioDetalle() {
      for (let index = 0; index < this.envios.length; index++) {
        if (this.envios[index].idenvio == this.envioAusar) {
          return this.nombreEnvio(
            this.envios[index].tipoenvio,
            this.envios[index].paisenvio,
            this.envios[index].precioenvio,
            this.envios[index].duracionenvio
          );
        }
      }
    },
    tipoEnvio(tipo) {
      if (tipo == "t") {
        return "Terrestre";
      } else if (tipo == "m") {
        return "Marítimo";
      } else {
        return "Aéreo";
      }
    },
    detallePago(pago) {
      var cadames = "";
      if (pago.tipopago == "p") {
        if (pago.meses == 1) {
          cadames = " mes";
        } else {
          cadames = " meses";
        }
        return (
          pago.cuotas +
          " cuotas de " +
          pago.porcentaje +
          "% cada " +
          pago.meses +
          cadames
        );
      } else {
        return "Pago completo";
      }
    },
    duracion(dias) {
      if (dias == 1) {
        return "1 día";
      } else {
        return dias + " dias";
      }
    },
    extrasEnvios() {
      //this.extrasSeleccionados = [];
      this.extrasAusar = [];
      this.extras.forEach((extra) => {
        if (extra.idenvio === this.envioAusar) {
          this.extrasAusar.push(extra);
        }
      });
    },
    agregarQuitarExtra(idExtra) {
      if (this.extrasSeleccionados.length == 0) {
        this.extrasSeleccionados.push(idExtra);
      } else {
        for (let index = 0; index < this.extrasSeleccionados.length; index++) {
          if (this.extrasSeleccionados[index] == idExtra) {
            this.extrasSeleccionados.splice(index, 1);
            return;
          }
        }
        this.extrasSeleccionados.push(idExtra);
      }
    },
    /*Verifica si el extra esta seleccionado para marcarlo como check en el model de extras*/
    estaSeleccionado(idExtra) {
      for (let index = 0; index < this.extrasSeleccionados.length; index++) {
        if (this.extrasSeleccionados[index] == idExtra) {
          return true;
        }
      }
      return false;
    },
  },

  computed: {},
};
</script>

<style>
.combo {
  color: #707070;
  font-weight: bold;
  width: 340px;
  padding-top: 5px;
  padding-bottom: 5px;
  padding-left: 5px;
}
</style>