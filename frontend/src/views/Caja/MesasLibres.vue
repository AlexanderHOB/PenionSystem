<template>

  <loading-fish v-if="loadingFish" />

  <v-container v-else fluid class="pb-5">
        <h3 class="title">Mesas Libres</h3>
        <v-row>
          <v-col
            v-for="mesa in mesas"
            :key="mesa.id"
            cols="3"
          >
            <v-card>
              <div class="d-flex flex-no-wrap justify-space-between">
                <div>
                  <v-card-title
                    class="title"
                  >
                    Mesa {{ mesa.numero }}
                  </v-card-title>

                  <v-card-text>
                    <p class="Mesa-capacidad mb-1">Capacidad</p>
                    {{ mesa.capacidad }}
                  </v-card-text>
                </div>

                <div class="Mesa-img-box pa-2">
                  <img src="../../assets/img/mesas/mesaLibre.svg" alt="mesa" class="Mesa-img">
                </div>
              </div>
            </v-card>
          </v-col>
        </v-row>

        <section
          ref="details"
          class="Details elevation-10"
        >
          <v-btn
            fab
            class="Details-arrow"
            small
            @click="toggleDetails"
          >
            <v-icon>keyboard_arrow_up</v-icon>
          </v-btn>
          <div class="Details-wrapper pa-3">
            <h2 class="title pl-2">ORDEN</h2>
            <v-row class="mx-0 pt-2">
              <v-col cols="12" class="Details-details pa-0">
                <v-row class="mx-0 text-center">
                  <v-col cols="1" class="pa-2">Nro. Orden</v-col>
                  <v-col cols="1" class="pa-2 Details-detail">171717</v-col>
                  <v-col cols="1" class="pa-2">Mesa</v-col>
                  <v-col cols="1" class="pa-2 Details-detail">62</v-col>
                  <v-col cols="1" class="pa-2">Mozo</v-col>
                  <v-col cols="1" class="pa-2 Details-detail">luismozo</v-col>
                  <v-col cols="1" class="pa-2">
                    <v-btn text color="blue" x-small>
                      PAX
                    </v-btn>
                  </v-col>
                  <v-col cols="1" class="pa-2 Details-detail">0</v-col>
                  <v-col cols="1" class="pa-2">T/C</v-col>
                  <v-col cols="1" class="pa-2 Details-detail">3.20</v-col>
                  <v-col cols="1" class="pa-2">T/C</v-col>
                  <v-col cols="1" class="pa-2 Details-detail">1.00</v-col>
                </v-row>
              </v-col>
              <v-col cols="12" class="Details-pedidos text-center pa-0 mt-4">
                <v-row class="mx-0">
                  <v-col cols="12" class="title d-flex px-0 pl-2">
                    <h3>Pedidos</h3>
                  </v-col>
                </v-row>
                <v-row class="mx-0">
                  <v-col cols="2" class="Details-pedidos-header">Selección</v-col>
                  <v-col cols="6" class="Details-pedidos-header">Descripción</v-col>
                  <v-col cols="2" class="Details-pedidos-header">Cantidad</v-col>
                  <v-col cols="2" class="Details-pedidos-header">Precio</v-col>
                </v-row>
                <v-row class="mx-0">
                  <v-col class="d-flex justify-center align-center" cols="2">
                    <v-checkbox
                      v-model="checkbox"
                      dense
                      hide-details
                      class="mt-0 pt-0"
                    ></v-checkbox>
                  </v-col>
                  <v-col cols="6">
                    <div class="subtitle-2">Ronda Marina (C/S, A/M Causa Lec/S) Doncella</div>
                    <small class="Details-pedidos-desc caption">Descripción de pedido descripción de pedido descripcion</small>
                  </v-col>
                  <v-col class=" d-flex align-center justify-center" cols="2">1</v-col>
                  <v-col class=" d-flex align-center justify-center" cols="2"> 28.00</v-col>
                </v-row>
                <v-row class="mx-0 py-3">
                  <v-col class="d-flex justify-center align-center" cols="2">
                    <v-checkbox
                      v-model="checkbox"
                      dense
                      hide-details
                      class="mt-0 pt-0"
                    ></v-checkbox>
                  </v-col>
                  <v-col cols="6">
                    Seleccionar Todo
                  </v-col>
                  <v-col cols="4">
                    <v-btn text color="blue" small>
                      Reducir
                    </v-btn>
                    /
                    <v-btn text color="blue" small>
                      Eliminar (2)
                    </v-btn>
                  </v-col>
                </v-row>
              </v-col>
              <v-col cols="12">
                <v-row class="mx-0">
                  <v-col cols="12" class="title d-flex px-0 pl-2">
                    <h3>Pagos</h3>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="4">
                    <v-row class="mx-0">
                      <v-col class="" cols="5">Consumo</v-col>
                      <v-col class="" cols="2">S/.</v-col>
                      <v-col class="" cols="5">49.00</v-col>
                    </v-row>
                    <v-row class="mx-0">
                      <v-col class="" cols="5">Descuento</v-col>
                      <v-col class="" cols="2">S/.</v-col>
                      <v-col class="" cols="5">00.00</v-col>
                    </v-row>
                    <v-row class="mx-0">
                      <v-col cols="6">
                        <v-btn text color="blue" small>
                          SplitMesa
                        </v-btn>
                    </v-col>
                      <v-col cols="6">
                        <v-btn text color="blue" small>
                          Pre-Cuenta
                        </v-btn>
                      </v-col>
                    </v-row>
                  </v-col>
                  <v-col cols="4">
                    <v-row class="text-center ma-0">
                      <v-col class="d-flex align-center justify-center" cols="5">TOTAL A PAGAR</v-col>
                      <v-col class="pa-0" cols="7">
                          <v-row class="ma-0">
                            <v-col class="total" cols="4">S/.</v-col>
                            <v-col class="total" cols="8">49.00</v-col>
                          </v-row>
                          <v-row class="ma-0">
                            <v-col class="total" cols="4">$</v-col>
                            <v-col class="total" cols="8">15.31</v-col>
                          </v-row>
                          <v-row class="ma-0">
                            <v-col class="total" cols="4">$</v-col>
                            <v-col class="total" cols="8">23.33</v-col>
                          </v-row>
                      </v-col>
                    </v-row>
                  </v-col>
                  <v-col cols="4">
                    <v-row class="text-center ma-0">
                      <v-col cols="12">FORMAS DE PAGO</v-col>
                    </v-row>
                    <v-row class="text-center ma-0">
                      <v-col cols="4">
                        <v-btn text color="blue" small>
                          Soles
                        </v-btn>
                      </v-col>
                      <v-col cols="4">
                        <v-btn text color="blue" small>
                          Dolar
                        </v-btn>
                      </v-col>
                      <v-col cols="4">
                        <v-btn text color="blue" small>
                          Crédito
                        </v-btn>
                      </v-col>
                    </v-row>
                    <v-row class="text-center ma-0">
                      <v-col cols="4">
                        <v-btn text color="blue" small>
                          Tarjeto
                        </v-btn>
                      </v-col>
                      <v-col cols="4">
                        <v-btn text color="blue" small>
                          Combo
                        </v-btn>
                      </v-col>
                      <v-col cols="4">
                        <v-btn text color="blue" small>
                          Registrar
                        </v-btn>
                      </v-col>
                    </v-row>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
          </div>
        </section>

        <template v-if="pageTotal">
          <div class="text-center mt-5">
            <v-pagination
              v-model="page"
              :length="pageTotal"
              circle
              @input="paginate"
              @next="paginate"
              @previous="paginate"
            ></v-pagination>
          </div>
        </template>

        <AlertNotifications />

        <v-overlay
          :value="overlay"
          z-index="99"
        />

        <v-btn
          v-show="overlay"
          class="Overlay-close"
          @click="toggleDetails"
        >
          Close
        </v-btn>

  </v-container>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'

import LoadingFish from '@/components/loading/LoadingFish'
import ErrorMessage from '@/components/messages/ErrorMessage'
import AlertNotifications from '@/components/messages/AlertNotifications'


export default {
  components: {
    LoadingFish,
    ErrorMessage,
    AlertNotifications
  },
  data () {
    return {
      checkbox: false,
      // General
      overlay: false,
      // loaders
      loadingFish: true,
      // Mesas
      mesas: [],
      allMesas: [],
      backup: {
        mesas: [],
        mesasIndex: true,
        pageTotal: 0
      },
      // Pagination
      pagination: 10,
      pageTotal: 0,
      page: 1,
      // Details
      detailActive: false
    }
  },
  methods: {
    // Get mesas
    async getMesas () {
      try {
        if (this.allMesasState.length === 0) {
          await this.getMesasAction()
        }
        console.log(this.allMesasState)
        return
        // const mesas = this.allMesasState[0].filter(e => e.estado === 'Disponible')
        this.allMesas = this.allMesasState
        this.page = 1
        this.paginate()
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Ocurrio un error en el servidor culpa de la lapaca',
          color: 'error'
        })
      } finally {
        this.loadingFish = false
      }
    },
    // Paginate
    paginate () {
      if(this.allMesas.length > this.pagination){
        this.mesas = this.allMesas.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page))
        this.pageTotal = Math.ceil(this.allMesas.length / this.pagination)
      }else {
         this.mesas = this.allMesas
          this.pageTotal = 0
      }
    },
    // Toggle Detail
    toggleDetails () {
        this.$refs.details.classList.toggle('Details-active')
        document.querySelector('.Details-arrow').classList.toggle('Details-arrow-active')
        this.overlay = !this.overlay
    },
    ...mapActions(['getMesasAction']),
    ...mapMutations(['snackbarMutation'])
  },
  computed: {
    ...mapState(['allMesasState'])
  },
  created () {
    this.getMesas()
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
.Details {
  position: fixed;
  width: 95%;
  height: 80%;
  z-index: 100;
  bottom: 0;
  left: 50%;
  transform: translate(-50%, 92%);
  border-top-left-radius: 36px;
  border-top-right-radius: 36px;
  background-color: #fff;
  transition: .45s;
  &-arrow {
    position: fixed;
    top: 0;
    left: 50%;
    transform: translate(-50%, -50%);
    transition: .45s;
    z-index: 1;
    &-active {
      transform: translate(-50%, -50%) rotate(180deg);
    }
  }
  &-active {
    transform: translate(-50%, 0);
  }
  &-wrapper {
    max-height: 100%;
    width: 100%;
    overflow-y: auto;
    &::-webkit-scrollbar {
      display: none;
    }
  }
  &-detail {
    border-right: 1px solid #ccc;
    &:last-child {
      border: none;
    }
  }
  &-pedidos {
    &-header {
      /* border-top: 1px solid #ccc; */
    }
    &-desc {
      color: #2196f3;
    }
  }
}
.Overlay-close {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 99;
  width: 100%;
  height: 100% !important;
  opacity: 0;
}
</style>