<template>
  <LoadingFish v-if="loadingFish" />

  <div v-else>
    <v-layout wrap>
      <v-flex xs12>
        <header class="d-flex justify-space-between align-center my-3">
          <h3 class="title">
            Mis Mesas
          </h3>
          <v-btn
            small
            fab
            dark
            color="blue"
            @click="refreshMesas"
          >
            <v-icon>refresh</v-icon>
          </v-btn>
        </header>
      </v-flex>
      <v-flex
        v-if="messageMesas"
        xs12
      >
        <ErrorMessage
          :error-message="messageMesas"
          :refresh="refreshMesas"
        />
      </v-flex>
      <template v-else>
        <v-flex
          v-for="(mesa, i) of mesas"
          :key="mesa.id"
          xs12
          sm6
          md4
          lg3
        >
          <v-card @click="selectDetail(i)">
            <div class="d-flex flex-no-wrap justify-space-between">
              <div>
                <v-card-title
                  class="title pr-0 pl-1"
                >
                  Mesa {{ mesa.mesa_numero }}
                </v-card-title>

                <v-card-text class="pl-1">
                  <p class="Mesa-capacidad mb-1">
                    Capacidad
                  </p>
                  {{ mesa.mesa_capacidad | persona }}
                </v-card-text>
              </div>

              <div
                class="Mesa-img-box pa-2 pt-4"
                :style="{'background-color': mesa.mozo_color}"
              >
                <img
                  src="@/assets/img/mesas/mesaOcupada.svg"
                  alt="mesa"
                  class="Mesa-img"
                >
              </div>
            </div>
          </v-card>
        </v-flex>
      </template>
    </v-layout>

    <section
      ref="details"
      class="Details"
    >
      <v-btn
        fab
        class="Details-arrow"
        small
        color="red"
        dark
        @click="toggleDetails"
      >
        <v-icon>keyboard_arrow_up</v-icon>
      </v-btn>

      <v-row class="mx-0 justify-space-between Details-wrapper">
        <v-col
          class="Details-card red lighten-1"
          cols="4"
        >
          <v-row class="mx-0 Details-content white pa-2">
            <div
              v-if="loadingPedido"
              class="Loader fill-height d-flex justify-center align-center"
            >
              <v-progress-circular
                :size="50"
                color="primary"
                indeterminate
              />
            </div>
            <template v-else>
              <v-col
                cols="12"
                class="pa-0"
              >
                <h3 class="Details-title pt-1 mx-5 title mb-2 text-center">
                  ORDEN
                </h3>
              </v-col>
              <v-col
                cols="3"
                class="pa-0 pl-4 my-2"
              >
                # Orden
              </v-col>
              <v-col
                cols="3"
                class="pa-0 pr-4 my-2 text-right Details-divider"
              >
                {{ details.nOrden }}
              </v-col>
              <v-col
                cols="3"
                class="pa-0 my-2"
              >
                <v-btn
                  text
                  color="blue"
                  x-small
                  class="px-4"
                >
                  Mesa
                </v-btn>
              </v-col>
              <v-col
                cols="3"
                class="pa-0 pr-4 my-2 text-right"
              >
                {{ details.mesa }}
              </v-col>
              <v-col
                cols="3"
                class="pa-0 pl-4 my-2"
              >
                Mozo
              </v-col>
              <v-col
                cols="3"
                class="pa-0 pr-4 my-2 text-right Details-divider text-truncate"
              >
                {{ details.mozo }}
              </v-col>
              <v-col
                cols="3"
                class="pa-0 pl-4 my-2"
              >
                PAX
              </v-col>
              <v-col
                cols="3"
                class="pa-0 pr-4 my-2 text-right"
              >
                {{ details.pax }}
              </v-col>
              <v-col
                cols="3"
                class="pa-0 pl-4 my-2"
              >
                T/C
              </v-col>
              <v-col
                cols="3"
                class="pa-0 pr-4 my-2 text-right Details-divider"
              >
                3.20
              </v-col>
              <v-col
                cols="3"
                class="pa-0 pl-4 my-2"
              >
                T/C
              </v-col>
              <v-col
                cols="3"
                class="pa-0 pr-4 my-2 text-right"
              >
                1.00
              </v-col>
              <v-col
                cols="12"
                class="pa-0"
              >
                <h3 class="Details-title pt-2 mx-5 title mb-2 text-center">
                  PAGOS
                </h3>
              </v-col>
              <v-col
                cols="6"
                class="pa-0 my-2 mb-5 pb-5 text-center"
              >
                Consumo
              </v-col>
              <v-col
                cols="2"
                class="pa-0 my-2 mb-5 pb-5 pl-4 green--text"
              >
                S/.
              </v-col>
              <v-col
                cols="4"
                class="pa-0 my-2 mb-5 pb-5 text-right pr-4"
              >
                {{ details.preTotal }}
              </v-col>
              <v-col
                cols="6"
                class="pa-0 py-2 Details-divider"
              />
              <v-col
                cols="2"
                class="pa-0 py-2 pl-4 green--text"
              >
                S/.
              </v-col>
              <v-col
                cols="4"
                class="pa-0 py-2 pr-4 text-right"
              >
                {{ details.total }}
              </v-col>
              <v-col
                cols="6"
                class="pa-0 py-2 text-center Details-divider"
              >
                TOTAL A PAGAR
              </v-col>
              <v-col
                cols="2"
                class="pa-0 py-2 pl-4 green--text"
              >
                $
              </v-col>
              <v-col
                cols="4"
                class="pa-0 py-2 pr-4 text-right"
              >
                {{ details.totalD }}
              </v-col>
              <v-col
                cols="6"
                class="pa-0 py-2 Details-divider"
              />
              <v-col
                cols="2"
                class="pa-0 py-2 pl-4 green--text"
              >
                €
              </v-col>
              <v-col
                cols="4"
                class="pa-0 py-2 pr-4 text-right"
              >
                {{ details.totalE }}
              </v-col>
              <v-col
                cols="6"
                class="pa-0 my-2 text-center"
              >
                <v-btn
                  text
                  color="blue"
                  small
                  disabled
                >
                  SPLITMEZA
                </v-btn>
              </v-col>
              <v-col
                cols="6"
                class="pa-0 my-2 text-center"
              >
                <v-btn
                  text
                  color="blue"
                  small
                  disabled
                >
                  ENVIAR ORDEN
                </v-btn>
              </v-col>
              <v-col
                cols="6"
                class="pa-0 my-2 text-center"
              >
                <v-btn
                  text
                  color="blue"
                  small
                  disabled
                >
                  PRE-CUENTA
                </v-btn>
              </v-col>
              <v-col
                cols="6"
                class="pa-0 my-2 text-center"
              >
                <v-btn
                  text
                  color="blue"
                  small
                  :to="{ name: 'mozo-menu', params: { id: details.id } }"
                >
                  NUEVA ORDEN
                </v-btn>
              </v-col>
            </template>
          </v-row>
        </v-col>
        <v-col
          class="Details-card red lighten-1 pt-5 pr-5"
          cols="7"
        >
          <img
            src="@/assets/img/carta/aros.svg"
            alt="aros"
            class="Details-card-aros"
          >
          <v-row class="mx-0 Details-content white pa-2">
            <div
              v-if="loadingPedido"
              class="Loader fill-height d-flex justify-center align-center"
            >
              <v-progress-circular
                :size="50"
                color="primary"
                indeterminate
              />
            </div>
            <template v-else>
              <v-col
                cols="12"
                class="pa-0"
              >
                <h3 class="Details-title pt-1 mx-5 title mb-2 text-center">
                  PEDIDOS
                </h3>
              </v-col>
              <v-col cols="2" />
              <v-col
                cols="6"
                class="pa-0 my-2 text-center"
              >
                Descripción
              </v-col>
              <v-col
                cols="2"
                class="pa-0 my-2 text-center"
              >
                Cantidad
              </v-col>
              <v-col
                cols="2"
                class="pa-0 my-2 text-center"
              >
                Precio
              </v-col>
              <v-col
                cols="12"
                class="pa-0"
              >
                <v-row
                  v-for="pedido in details.pedidos"
                  :key="pedido.id"
                  class="mx-0 my-3"
                >
                  <v-col cols="2" />
                  <v-col
                    class="pa-0"
                    cols="6"
                  >
                    <div class="subtitle-2">
                      {{ pedido.nombre_platillo }}
                    </div>
                    <small class="Details-pedidos-desc caption">{{ pedido.comentario }}</small>
                  </v-col>
                  <v-col
                    class="pa-0 d-flex align-center justify-center"
                    cols="2"
                  >
                    {{ pedido.cantidad }}
                  </v-col>
                  <v-col
                    class="pa-0 d-flex align-center justify-center"
                    cols="2"
                  >
                    {{ pedido.subtotal }}
                  </v-col>
                </v-row>
              </v-col>
              <v-col class="fill-height" />
            </template>
          </v-row>
        </v-col>
      </v-row>
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
        />
      </div>
    </template>

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

    <loading-dialog />

    <alert-notifications />
  </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'

import mozoService from '@/services/mozo'

import LoadingDialog from '@/components/loading/LoadingDialog'
import LoadingFish from '@/components/loading/LoadingFish'
import ErrorMessage from '@/components/messages/ErrorMessage'
import AlertNotifications from '@/components/messages/AlertNotifications'

export default {
  components: {
    LoadingDialog,
    LoadingFish,
    ErrorMessage,
    AlertNotifications
  },
  filters: {
    persona (capacidad) {
      if (capacidad === 1) {
        return capacidad + ' persona'
      } else {
        return capacidad + ' personas'
      }
    }
  },
  data: () => ({
    // General
    title: 'Mesas',
    overlay: false,
    // Data para las mesas
    messageMesas: '',
    mesas: [],
    allMesas: [],
    isLoadBtn: false,
    disabled: false,
    refresh: false,
    // Backup
    backup: {
      mesas: [],
      mesasIndex: true,
      pageTotal: 0
    },
    // Data para la paginación
    pagination: 12,
    pageTotal: 0,
    page: 1,
    // Details
    details: {
      id: 0,
      nOrden: 0,
      mesa: 0,
      mozo: '',
      pax: 0,
      pedidos: [],
      preTotal: 0,
      descuento: 0,
      total: 0,
      totalD: 0,
      totalE: 0
    },
    // Pedidos
    loadingPedido: true
  }),
  computed: {
    numberOfSelecteds () {
      return this.selected.length
    },
    ...mapState(['loadingFish', 'allPedidosState', 'auth'])
  },
  async created () {
    this.loadingFishMutation(true)
    await this.getMesas()
    this.loadingFishMutation(false)
  },
  methods: {
    // OBTENER MESAS
    async getMesas () {
      try {
        this.loadingTitleMutation('Actualizando información')
        this.loadingDialogMutation(true)

        if (this.allPedidosState.length === 0 || this.refresh) {
          await this.getPedidosAction()
          this.refresh = false
        }

        if (this.allPedidosState) {
          const mesas = this.allPedidosState
          if (mesas.length > 0) {
            this.allMesas = mesas.filter(e => e.mozo_id === this.auth.user.id)
            if (!this.allPedidosState.length) {
              this.messageMesas = 'No se encontraron mesas ocupadas'
              return
            }
            this.page = 1
            this.paginate()
            this.messageMesas = ''
          } else {
            this.messageMesas = 'No se encontraron mesas'
            this.pageTotal = 0
          }
        } else {
          this.messageMesas = 'No se encontraron mesas'
          this.mesas = []
          this.pageTotal = 0
        }
      } catch (error) {
        this.pageTotal = 0
        this.messageMesas = 'Error al conectar con el servidor'
      } finally {
        this.loadingDialogMutation(false)
      }
    },

    refreshMesas () {
      this.refresh = true
      this.getMesas()
    },

    // PAGINAR MESAS
    paginate () {
      if (this.allMesas.length > this.pagination) {
        this.mesas = this.allMesas.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page))
        this.pageTotal = Math.ceil(this.allMesas.length / this.pagination)
      } else {
        this.mesas = this.allMesas
        this.pageTotal = 0
      }
    },
    // DETAILS
    // Toggle Detail
    toggleDetails () {
      this.$refs.details.classList.toggle('Details-active')
      document.querySelector('.Details-arrow').classList.toggle('Details-arrow-active')
      this.overlay = !this.overlay
    },
    async selectDetail (i) {
      const pedido = this.mesas[i]
      try {
        this.toggleDetails()
        this.loadingPedido = true
        const { data } = await mozoService.getPedido(pedido.id)
        const nombre = data.mozo_nombre.split(' ')
        const config = {
          id: data.id,
          nOrden: data.numero_orden,
          mesa: pedido.mesa_numero,
          mozo: nombre[0] + data.rol,
          pax: pedido.mesa_capacidad,
          pedidos: data.detalles_pedidos
        }
        this.assignDetaials(config)

        this.loadingPedido = false
      } catch (error) {
        console.log(error)
      }
    },
    // ASSIGN
    // assign details
    assignDetaials (config) {
      this.details.preTotal = 0
      let preTotal = 0

      config.pedidos.forEach(e => {
        preTotal += parseFloat(e.subtotal)
      })
      preTotal = preTotal.toFixed(2)
      this.details = {
        ...config,
        preTotal
      }
      this.assignTotales(preTotal)
    },
    assignTotales (preTotal) {
      const total = preTotal
      let totalD = total / 3.2
      totalD = totalD.toFixed(2)
      let totalE = total / 2.1
      totalE = totalE.toFixed(2)
      this.details = {
        ...this.details,
        total,
        totalD,
        totalE
      }
    },
    ...mapMutations(['loadingDialogMutation', 'loadingTitleMutation', 'loadingFishMutation', 'snackbarMutation']),
    ...mapActions(['getPedidosAction'])
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
  width: 98%;
  height: 80%;
  z-index: 100;
  bottom: 0;
  left: 50%;
  transform: translate(-50%, 92%);
  transition: .45s;
  &-arrow {
    position: fixed;
    top: 0;
    left: 37.5%;
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
  /* &-wrapper {
    max-height: 100%;
    width: 100%;
    overflow-y: auto;
    &::-webkit-scrollbar {
      display: none;
    }
  } */
  &-wrapper {
    height: 100%;
  }
  &-content {
    height: 100%;
    overflow-y: auto;
    &::-webkit-scrollbar {
      display: none;
    }
  }
  &-card {
    position: relative;
    height: 100%;
    border-top-left-radius: 16px;
    border-top-right-radius: 16px;
    &-aros {
      position: absolute;
      top: 55px;
      left: -25px;
      width: 70px;
    }
  }
  &-title {
    border-bottom: 1px solid #ccc;
  }
  &-divider {
    border-right: 1px solid #ccc;
  }
  &-pedidos {
    &-desc {
      color: #2196f3;
    }
  }
}
.Loader {
  width: 100%;
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
.checkBox {
  transform: translateX(2.25em)
}
.actions {
  cursor: pointer;
  display: inline-block;
  width: 32px;
}
</style>
