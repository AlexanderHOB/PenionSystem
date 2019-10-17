<template>
  <v-row>
    <v-col cols="5 pa-0">
      <v-card class="Aside" color="grey lighten-3">
       <v-row class="text-center ma-0">
         <v-col class="Aside-col Aside-header pa-1" cols="3">Nro. Orden</v-col>
         <v-col class="Aside-col Aside-header pa-1" cols="2"></v-col>
         <v-col class="Aside-col Aside-header pa-1" cols="2">Mesa</v-col>
         <v-col class="Aside-col Aside-header pa-1" cols="2"></v-col>
         <v-col class="Aside-col Aside-header pa-1" cols="1">T/C</v-col>
         <v-col class="Aside-col Aside-header pa-1" cols="2">3.20</v-col>
       </v-row>
       <v-row class="text-center ma-0">
         <v-col class="Aside-col Aside-header pa-1" cols="3">Mozo</v-col>
         <v-col class="Aside-col Aside-header pa-1" cols="2"></v-col>
         <v-col class="Aside-col Aside-header pa-1" cols="2">
           <v-btn text color="blue" x-small>
             PAX
           </v-btn>
         </v-col>
         <v-col class="Aside-col Aside-header pa-1" cols="2"></v-col>
         <v-col class="Aside-col Aside-header pa-1" cols="1">T/C</v-col>
         <v-col class="Aside-col Aside-header pa-1" cols="2">1.00</v-col>
       </v-row>
       <v-row class="text-center ma-0">
         <v-col class="Aside-col Aside-description pa-0" cols="2">Selección</v-col>
         <v-col class="Aside-col Aside-description pa-0" cols="6">Descripción</v-col>
         <v-col class="Aside-col Aside-description pa-0" cols="2">Cantidad</v-col>
         <v-col class="Aside-col Aside-description pa-0" cols="2">Precio</v-col>
        </v-row>
       <v-row class="text-center ma-0 py-2">
         <v-col class="Aside-col d-flex justify-center align-center" cols="2">
           <v-checkbox
            v-model="checkbox"
            dense
            hide-details
            class="mt-0 pt-0"
          ></v-checkbox>
         </v-col>
         <v-col class="Aside-col" cols="6">
           <div class="subtitle-2">Ronda Marina (C/S, A/M Causa Lec/S) Doncella</div>
           <small class="caption">Descripción de pedido descripción de pedido descripcion</small>
         </v-col>
         <v-col class="Aside-col  d-flex align-center justify-center" cols="2">1</v-col>
         <v-col class="Aside-col  d-flex align-center justify-center" cols="2"> 28.00</v-col>
       </v-row>
       <v-row class="text-center ma-0 py-3">
         <v-col class="Aside-col d-flex justify-center align-center" cols="2">
           <v-checkbox
            v-model="checkbox"
            dense
            hide-details
            class="mt-0 pt-0"
          ></v-checkbox>
         </v-col>
         <v-col class="Aside-col" cols="6">
           Seleccionar Todo
         </v-col>
         <v-col class="Aside-col" cols="4">Reducir/Eliminar (2)</v-col>
       </v-row>
       <v-row class="text-center ma-0 pt-2">
         <v-col class="Aside-col Aside-pagos" cols="5">Consumo</v-col>
         <v-col class="Aside-col Aside-pagos" cols="2">S/.</v-col>
         <v-col class="Aside-col Aside-pagos" cols="5">49.00</v-col>
       </v-row>
       <v-row class="text-center ma-0">
         <v-col class="Aside-col Aside-pagos" cols="5">Descuento</v-col>
         <v-col class="Aside-col Aside-pagos" cols="2">S/.</v-col>
         <v-col class="Aside-col Aside-pagos" cols="5">00.00</v-col>
       </v-row>
       <v-row class="text-center ma-0">
         <v-col class="Aside-col Aside-pagos d-flex align-center justify-center" cols="5">TOTAL A PAGAR</v-col>
         <v-col class="Aside-col Aside-pagos pa-0" cols="7">
            <v-row class="ma-0">
              <v-col class="Aside-col Aside-pagos Aside-pagos-total" cols="4">S/.</v-col>
              <v-col class="Aside-col Aside-pagos Aside-pagos-total" cols="8">49.00</v-col>
            </v-row>
            <v-row class="ma-0">
              <v-col class="Aside-col Aside-pagos Aside-pagos-total" cols="4">$</v-col>
              <v-col class="Aside-col Aside-pagos Aside-pagos-total" cols="8">15.31</v-col>
            </v-row>
            <v-row class="ma-0">
              <v-col class="Aside-col Aside-pagos Aside-pagos-total" cols="4">$</v-col>
              <v-col class="Aside-col Aside-pagos Aside-pagos-total" cols="8">23.33</v-col>
            </v-row>
         </v-col>
       </v-row>
       <v-row class="text-center ma-0">
         <v-col class="Aside-col Aside-pagos" cols="5">SplitMesa</v-col>
         <v-col class="Aside-col Aside-pagos" cols="7">Pre-Cuenta</v-col>
       </v-row>
       <v-row class="text-center ma-0">
         <v-col class="Aside-col Aside-pagos" cols="12">FORMAS DE PAGO</v-col>
       </v-row>
       <v-row class="text-center ma-0">
         <v-col class="Aside-col Aside-pagos" cols="4">Soles</v-col>
         <v-col class="Aside-col Aside-pagos" cols="4">Dolar</v-col>
         <v-col class="Aside-col Aside-pagos" cols="4">Crédito</v-col>
       </v-row>
       <v-row class="text-center ma-0">
         <v-col class="Aside-col Aside-pagos" cols="4">Tarjeto</v-col>
         <v-col class="Aside-col Aside-pagos" cols="4">Combo</v-col>
         <v-col class="Aside-col Aside-pagos" cols="4">Registrar cliente</v-col>
       </v-row>
      </v-card>
    </v-col>
    <v-col cols="7" class="pa-0">
      <v-container fluid>
        <h3 class="title">Mesas Libres</h3>
        <v-row>
          <v-col
            v-for="n in 3"
            :key="n"
            cols="4"
          >
            <v-card>
              <div class="d-flex flex-no-wrap justify-space-between">
                <div>
                  <v-card-title
                    class="title"
                  >
                    Mesa 61
                  </v-card-title>

                  <v-card-text>
                    <p class="Mesa-capacidad mb-1">Capacidad</p>
                    25
                  </v-card-text>
                </div>

                <div class="Mesa-img-box pa-2">
                  <img src="../../assets/img/mesas/mesaLibre.svg" alt="mesa" class="Mesa-img">
                </div>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-col>
  </v-row>
</template>

<script>
export default {
  data () {
    return {
      checkbox: false
    }
  }
}
</script>

<style lang="scss" scoped>
.Mesa {
  &-img {
    &-box {
      width: 120px;
    }
  }
  &-capacidad {
    color: #212121;
  }
}
.Aside {
  min-height: calc(100vh - 136px);
  overflow-y: auto;
  &-col {
    background-color: #fff;
  }
  &-header,
  &-description,
  &-pagos {
    border: 1px solid #eee;
  }
  &-header {
    &:nth-child(2n - 1) {
      background-color: #fafafa;
    }
  }
  &-pagos {
    &-total {
      color: #f44336;
    }
  }
}
</style>