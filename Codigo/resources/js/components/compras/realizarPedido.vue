<template>
  <div class="d-flex flex-column align-items-center col-12">
    <div class="d-flex justify-content-around col-10 my-2">
      <label style="font-weight:bold;" for="envios">Método de envío</label>
      <select class="combo" name="envios" id="envios" style="outline:none;">
        <option v-for="(envio,index) in envios" :key="index" class="combo">
          {{tipoEnvio(envio.tipoenvio) + " - " + envio.paisenvio + " (" + envio.precioenvio +
          "$ - "+duracion(envio.duracionenvio)+")"}}
        </option>
      </select>
    </div>
    <div class="d-flex justify-content-around col-10 my-2">
      <label class style="font-weight:bold;" for="detalleEnvio">Extras de envío</label>
      <button
        class="btn btn-primary"
        style="width:340px;"
        data-toggle="modal"
        data-target="#extraEnvio"
      >
        <img class="pr-2" src="/img/iconos/add_white.svg" alt="crear" width="24" /> Agregar Extra de Envío
      </button>
      <div class="modal fade" id="extraEnvio" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content" style="background-color: #F5F5F5">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Extras De Envío</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body h5 text-center">
              AQUI DEBE IR EL NOMBRE DEL METODO
              <br />
              <br />
              <table class="table table-striped border border-info">
                <thead class="bg-primary text-white">
                  <tr class="text-center">
                    <th scope="col">Nombre</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Días de Retraso</th>
                    <th scope="col">Seleccionar</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>hola</td>
                    <td>hola</td>
                    <td>hola</td>
                    <td>
                      <input type="checkbox" id name value />
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
    <div class="d-flex justify-content-around col-10 my-2">
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
        <th scope="col" class="text-left">Nº Cas</th>
        <th scope="col" class="text-left">Ingrediente</th>
        <th scope="col" class="text-left">Tipo de Producto</th>
        <th scope="col" class="text-left">Precio Unitario</th>
        <th class="text-left">Cantidad</th>
        <th class="text-left">Subtotal</th>
      </thead>
      <tbody>
        <tr v-for="(producto,index) in productos" :key="index">
          <td class="text-left">{{producto.ncas}}</td>
          <td class="text-left">{{ producto.nombreprod + " (" + producto.presentacion+ " ml)" }}</td>
          <td class="text-left">{{ producto.tipo}}</td>
          <td class="text-right">{{producto.precioing + " $"}}</td>
          <td class="text-left">
            <input style="width: 80px;" type="number" id name value />
          </td>
          <td class="text-center">Precio*Cantidad</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: ["csrf"],
  data() {
    return {
      envios: [],
      pagos: [],
      extras: [],
      productos: [],
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
        console.log(response.data[0]);
        console.log(response.data[1]);
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