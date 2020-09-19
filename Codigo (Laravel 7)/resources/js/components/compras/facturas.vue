<template>
  <div class="container col-12">
    <br />
    <span v-if="facturas.length!=0">
      <table class="table table-striped border border-info">
        <thead class="bg-primary text-white">
          <tr class="text-center">
            <th scope="col">#Factura</th>
            <th scope="col">#Pedido</th>
            <th scope="col">Proveedor</th>
            <th scope="col">Monto Total</th>
            <th scope="col">Estatus</th>
            <th scope="col">Pagos</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="(factura,index) in facturas">
            <tr class="text-center">
              <td>{{factura.num_factura}}</td>
              <td>{{factura.num_pedido}}</td>
              <td>{{factura.prov}}</td>
              <td>{{factura.monto + " $"}}</td>
              <template v-if="factura.por_pagar==0">
                <td>
                  <span class="ml-2">Pagado</span>
                </td>
              </template>
              <template v-else>
                <td>
                  <span class="ml-2">Por pagar</span>
                </td>
              </template>
              <td>
                <img
                  src="/img/iconos/list.svg"
                  alt="ver"
                  width="24"
                  class="iconobtn"
                  data-toggle="modal"
                  data-target="#pagosPorHacer"
                  @click="buscarPagosFactura(factura.num_pedido)"
                />
                <!-- Modal para mostrar los detalles de un ingrediente -->

                <div
                  class="modal fade"
                  id="pagosPorHacer"
                  tabindex="-1"
                  role="dialog"
                  aria-hidden="true"
                >
                  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content" style="background-color: #F5F5F5">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Generar Pedido</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body h5 text-center">
                        <table class="table table-striped border border-info">
                          <thead class="bg-primary text-white">
                            <tr class="text-center">
                              <th scope="col">Cuota</th>
                              <th scope="col">Porcentaje por cuota</th>
                              <th scope="col">Monto a pagar</th>
                              <th scope="col">Disponible en</th>
                              <th scope="col">Meses</th>
                              <th scope="col">Nº de Pago</th>
                              <th scope="col">Estatus</th>
                              <th scope="col">Pagar</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(det,index) in detallesPagos" :key="index">
                              <td>{{index+1}}</td>
                              <td>{{porcentaje + " %"}}</td>
                              <td>{{factura.monto + " $"}}</td>
                              <td>{{cuotas_desde[index+1][0]}}</td>
                              <td>{{meses }}</td>

                              <td v-if>N/A</td>
                              <td v-else></td>
                              <td>{{cuotas_desde[index+1][2]}}</td>
                              <td v-if="cuotas_desde[index+1][1]">
                                <button class="btn btn-primary" disabled href>Pagar</button>
                              </td>
                              <td v-else>
                                <button class="btn btn-primary" href></button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </span>
    <span v-else>
      <h5>No tienes facturas en este momento.</h5>
    </span>
  </div>
</template>

<script>
export default {
  props: ["csrf"],
  data() {
    return {
      facturas: [],
      pagos: [],
      pagados: [],
      id_productor: 0,
      cuotas_desde: [],
      i: 0,
      detallesPagos: [],
      fecha_inicial: "",
      id_pago: 0,
      cuotas: [],
      porcentaje: 0,
      meses: 0,
      longitud: 0,
      detallesPagos2: [],
    };
  },
  created() {
    console.log("%cAxios: Buscando data factura.", "color: lightblue");
    axios
      .get(window.location.pathname + "/facturas")
      .then((response) => {
        console.log("estoy en resposne");
        this.id_productor = response.data[0];
        this.facturas = response.data[1];
        this.pagos = response.data[2];
        this.pagados = response.data[3];
        this.cuotas_desde = response.data[4];
      })
      .catch((errors) => {
        console.log(
          "%cAxios: Error buscando metodos de envio y metodos de pago",
          "color: #FFCCCB"
        );
      });
  },
  methods: {
    cuotasPorPago(parametro) {
      for (let index = 1; index < parametro; index++) {}
    },
    buscarPagosFactura(n_pedido) {
      //this.detallesPagos = [];
      const params = {
        fecha_inicial: "",
        id_pago: 0,
        tipo: "",
        cuotas: [],
        porcentaje: 0,
        meses: 0,
      };
      this.pagos.forEach((pago) => {
        if (pago.num_pedido === n_pedido) {
          params.fecha_inicial = pago.fecha_inicial;
          params.id_pago = pago.id_pago;
          params.tipo = pago.tipo;
          for (let index = 0; index < pago.cuotas; index++) {
            this.detallesPagos.push(index + 1);
          }
          this.porcentaje = pago.porcentaje;
          this.meses = pago.meses;
          //this.detallesPagos.push(params);
        }
        //break;
      });
    },
    ayuda() {
      return 3;
    },
  },
};
</script>
