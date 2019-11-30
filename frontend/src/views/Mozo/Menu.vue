<template>
  <LoadingFish v-if="loadingFish" />

  <v-container
    v-else
    grid-list-xl
  >
    <v-layout
      row
      wrap
      class="pt-3"
    >
      <v-flex
        v-show="messagePlatillos"
        xs12
      >
        <ErrorMessage
          :error-message="messagePlatillos"
          :refresh="refreshPlatillos"
        />
      </v-flex>
      <v-flex
        v-show="!messagePlatillos"
        xs12
      >
        <v-layout
          row
          wrap
        >
          <v-flex xs12>
            <v-layout>
              <v-flex xs2>
                <v-select
                  v-model="categoria"
                  :items="categorias"
                  item-text="nombre"
                  item-value="id"
                  label="Categorias"
                  :disabled="disabledCategories"
                  return-object
                />
              </v-flex>
              <v-flex xs2 />
              <v-flex xs2>
                <v-select
                  v-model="typeOfSearch"
                  :items="typesOfSearch"
                  label="Buscar por"
                />
              </v-flex>
              <v-flex xs4>
                <v-text-field
                  v-model="searchQuery"
                  solo
                  label="Buscar Platillo..."
                  color="blue"
                  append-icon="search"
                  :disabled="searchDisabled"
                />
              </v-flex>
              <v-flex xs1 />
              <v-flex
                xs1
                class="text-center"
              >
                <v-btn
                  small
                  fab
                  dark
                  color="blue"
                  class="mt-2"
                  @click="refreshPlatillos"
                >
                  <v-icon>refresh</v-icon>
                </v-btn>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex
            v-show="searchMessage"
            xs12
          >
            <p class="text-center">
              {{ searchMessage }}
            </p>
          </v-flex>
          <v-flex
            v-for="(platillo, i) of platillos"
            :key="platillo.id"
            class="platillo"
            xs3
          >
            <mozo-platillo-box
              class="platillo-bg"
              :add-platillo="toggePedidoModal"
              :index="i"
            />
            <p class="platillo-text text-center">
              {{ platillo.nombre }}<br>{{ platillo.precio }}
            </p>
            <span class="platillo-add">+</span>
          </v-flex>
        </v-layout>
      </v-flex>
    </v-layout>

    <div
      v-show="filtered"
      class="text-center"
    >
      <v-btn
        dark
        color="blue"
        @click="showAll"
      >
        Mostrar Todos
      </v-btn>
      <div class="Utils-height" />
    </div>

    <section
      v-if="detailShow"
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
                  :disabled="pedido.splitDisabledBtn"
                  @click="openSplitModal"
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
                  :disabled="pedido.disabledSendBtn"
                  @click="pedido.send = true"
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
                  :disabled="pedido.preCuentaDisabledBtn"
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
                  disabled
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
              <v-col
                cols="2"
                class="pa-0 my-2 text-center"
              >
                Selección
              </v-col>
              <v-col
                cols="2"
                class="pa-0 my-2 "
              >
                Opciones
              </v-col>
              <v-col
                cols="5"
                class="pa-0 my-2 text-center"
              >
                Descripción
              </v-col>
              <v-col
                cols="1"
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
                  v-for="(orden, i) in ordenes"
                  :key="orden.key"
                  class="mx-0 my-3"
                >
                  <v-col
                    class="pa-0 checkBox d-flex justify-center align-center"
                    cols="1"
                  >
                    <template v-if="orden.estado === 'Nuevo'">
                      <v-checkbox
                        v-model="checkboxs[i].value"
                        dense
                        hide-details
                        class="mt-0 pt-0"
                        @change="toggleSelect(i)"
                      />
                    </template>
                  </v-col>
                  <v-col
                    class="pa-0 pt-3 text-center"
                    cols="3"
                  >
                    <template v-if="orden.estado === 'Nuevo'">
                      <img
                        src="@/assets/img/mozo/eliminar.svg"
                        alt="eliminiar"
                        class="actions"
                        @click="removePlatillo(orden.key)"
                      >
                      <img
                        src="@/assets/img/mozo/aumentar.svg"
                        alt="aumentar"
                        class="actions"
                        @click="increasePlatillo(orden.key)"
                      >
                      <img
                        src="@/assets/img/mozo/disminuir.svg"
                        alt="disminuir"
                        class="actions"
                        @click="decreasePlatillo(orden.key)"
                      >
                    </template>
                  </v-col>
                  <v-col
                    class="pa-0"
                    cols="5"
                  >
                    <div class="subtitle-2">
                      {{ orden.nombre_platillo }}
                    </div>
                    <small class="Details-pedidos-desc caption">{{ orden.comentario }}</small>
                  </v-col>
                  <v-col
                    class="pa-0 d-flex align-center justify-center"
                    cols="1"
                  >
                    {{ orden.cantidad }}
                  </v-col>
                  <v-col
                    class="pa-0 d-flex align-center justify-center"
                    cols="2"
                  >
                    {{ orden.subtotal }}
                  </v-col>
                </v-row>
              </v-col>
              <v-col
                class="pa-0 my-2 checkBox d-flex justify-center align-center"
                cols="1"
              >
                <v-checkbox
                  v-model="checkbox"
                  dense
                  hide-details
                  class="mt-0 pt-0"
                  @change="toggleAllSelect"
                />
              </v-col>
              <v-col
                class="pa-0 my-2 text-center"
                cols="8"
              >
                Seleccionar Todo
              </v-col>
              <v-col
                class="pa-0 my-2 text-center"
                cols="3"
              >
                <v-btn
                  text
                  color="blue"
                  small
                  @click="pedido.remove = true"
                >
                  Eliminar ({{ numberOfSelecteds }})
                </v-btn>
              </v-col>
              <v-col class="fill-height" />
            </template>
          </v-row>
        </v-col>
      </v-row>
    </section>

    <v-dialog
      v-model="pedido.modal"
      max-width="660"
    >
      <v-card>
        <v-card-title>
          <v-spacer />
          <h3 class="headline">
            {{ pedido.title }}
          </h3>
          <v-spacer />
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col
                cols="2"
                class="d-flex justify-center align-center"
              >
                <v-btn
                  color="green"
                  dark
                  fab
                  small
                  @click="decreaseQuantityPlatillos"
                >
                  -
                </v-btn>
              </v-col>
              <v-col cols="2">
                <v-text-field
                  v-model="pedido.cantidad"
                  v-mask="'###'"
                  label="Cantidad"
                  counter
                  maxlength="3"
                  @keyup.enter="addPlatillo"
                />
              </v-col>
              <v-col
                cols="2"
                class="d-flex justify-center align-center"
              >
                <v-btn
                  color="red"
                  dark
                  fab
                  small
                  @click="pedido.cantidad++"
                >
                  +
                </v-btn>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  v-model="pedido.comentario"
                  label="Comentario"
                  maxlength="25"
                  counter
                  @keyup.enter="addPlatillo"
                />
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer />
          <v-btn
            color="green darken-1"
            text
            @click="addPlatillo"
          >
            Añadir Platillo
          </v-btn>
          <v-spacer />
          <v-btn
            color="red darken-1"
            text
            @click="pedido.modal = false"
          >
            Cancelar
          </v-btn>
          <v-spacer />
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="pedido.remove"
      max-width="320"
    >
      <v-card>
        <v-card-title>
          <v-spacer />
          <h3 class="headline">
            ¿Estás seguro?
          </h3>
          <v-spacer />
        </v-card-title>

        <v-card-text>
          Los platillos seleccionados serán eliminados.
        </v-card-text>

        <v-card-actions>
          <v-spacer />
          <v-btn
            color="green darken-1"
            text
            @click="removePlatillos"
          >
            Aceptar
          </v-btn>
          <v-spacer />
          <v-btn
            color="red darken-1"
            text
            @click="pedido.remove = false"
          >
            Cancelar
          </v-btn>
          <v-spacer />
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="pedido.send"
      max-width="320"
      :persistent="pedido.loading"
    >
      <v-card>
        <v-card-title>
          <v-spacer />
          <h3 class="headline">
            ¿Estás seguro?
          </h3>
          <v-spacer />
        </v-card-title>

        <v-card-text>
          Enviar orden.
        </v-card-text>

        <v-card-actions>
          <v-spacer />
          <v-btn
            color="green darken-1"
            text
            :loading="pedido.loading"
            @click="sendData"
          >
            Aceptar
          </v-btn>
          <v-spacer />
          <v-btn
            color="red darken-1"
            text
            :disabled="pedido.disabled"
            @click="pedido.send = false"
          >
            Cancelar
          </v-btn>
          <v-spacer />
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="pedido.split"
      :persistent="pedido.splitLoading"
      max-width="480"
    >
      <v-card>
        <div
          v-if="pedido.mesasLoading"
          class="Loader Loader-single fill-height d-flex justify-center align-center"
        >
          <v-progress-circular
            :size="50"
            color="primary"
            indeterminate
          />
        </div>
        <template v-else>
          <v-card-title>
            <v-spacer />
            <h3 class="headline">
              Split
            </h3>
            <v-spacer />
          </v-card-title>

          <v-card-text>
            <v-container>
              <v-row
                v-for="(orden, i) in pedido.pedidoFiltered"
                :key="orden.key"
              >
                <v-col cols="8">
                  <h4 class="subtitle-2 pb-3">
                    {{ orden.nombre_platillo }}
                  </h4>
                  <p class="mb-0">
                    {{ orden.comentario }} | cantidad: {{ orden.cantidad }}
                  </p>
                </v-col>
                <v-col cols="4">
                  <v-select
                    v-model="pedido.mesasModel[i].value"
                    class="pt-0"
                    :items="pedido.mesas"
                    :disabled="pedido.splitDisabled"
                    label="Mesas"
                    item-value="mesa_id"
                    item-text="mesa_numero"
                    dense
                    single-line
                    clearable
                  />
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer />
            <v-btn
              color="green darken-1"
              text
              :loading="pedido.splitLoading"
              @click="splitMesa"
            >
              Aceptar
            </v-btn>
            <v-spacer />
            <v-btn
              color="red darken-1"
              text
              :disabled="pedido.splitDisabled"
              @click="pedido.split = false"
            >
              Cancelar
            </v-btn>
            <v-spacer />
          </v-card-actions>
        </template>
      </v-card>
    </v-dialog>

    <template v-if="pageTotal">
      <div class="text-center mt-5">
        <v-pagination
          v-model="page"
          :length="pageTotal"
          :total-visible="7"
          circle
          @input="paginate"
          @next="paginate"
          @previous="paginate"
        />
        <div class="Utils-height" />
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
  </v-container>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import { mask } from 'vue-the-mask'

import mozoService from '@/services/mozo'

import LoadingDialog from '@/components/loading/LoadingDialog'
import LoadingFish from '@/components/loading/LoadingFish'
import ErrorMessage from '@/components/messages/ErrorMessage'
import AlertNotifications from '@/components/messages/AlertNotifications'
import MozoPlatilloBox from '@/components/box/MozoPlatilloBox'

export default {
  components: {
    LoadingDialog,
    LoadingFish,
    ErrorMessage,
    AlertNotifications,
    MozoPlatilloBox
  },
  directives: {
    mask
  },
  data () {
    return {
      // General
      overlay: false,
      // Datos para las platillos
      messagePlatillos: '',
      platillos: [],
      allPlatillos: [],
      isLoadBtn: false,
      refresh: false,
      // Backup
      backup: {
        platillos: [],
        platillosIndex: true,
        pageTotal: 0
      },
      // Search data
      searchQuery: '',
      searchDisabled: true,
      searchMessage: '',
      typeOfSearch: 'nombre',
      typesOfSearch: ['codigo', 'nombre'],
      // Datos para las categorias
      categorias: [],
      categoria: {},
      disabledCategories: true,
      // Filtered
      filtered: false,
      // Datos para la paginación
      pagination: 12,
      pageTotal: 0,
      page: 1,
      // Datos para los pedidos
      ordenes: [],
      // Details
      details: {
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
      checkbox: false,
      checkboxs: [],
      selecteds: [],
      loadingPedido: true,
      detailShow: false,
      pedido: {
        modal: false,
        title: '',
        disabledSendBtn: true,
        disabled: false,
        loading: false,
        platillo: null,
        comentario: '',
        cantidad: 1,
        remove: false,
        send: false,
        split: false,
        splitLoading: false,
        splitDisabled: false,
        splitDisabledBtn: true,
        mesasLoading: true,
        pedidoFiltered: [],
        mesas: [],
        mesasModel: [],
        preCuentaDisabledBtn: true
      }
    }
  },
  computed: {
    numberOfSelecteds () {
      return this.selecteds.length
    },
    ...mapState(['loadingFish', 'allPlatillosState', 'allCategoriasState', 'allPedidosState'])
  },
  watch: {
    searchQuery () {
      if (this.searchQuery) {
        this.disabledCategories = true
      } else {
        this.disabledCategories = false
      }
      this.searchPlatillos(this.searchQuery)
    },
    categoria () {
      if (this.categoria) {
        this.platillosFiltered(this.categoria)
      }
    }
  },
  async created () {
    this.activeClass()
    this.loadingFishMutation(true)
    this.getCategorias()
    await this.getPlatillos()
    this.loadingFishMutation(false)
    this.toggleDetailShow()
  },
  beforeMount () {
    this.headerBreadcrumbMutation('Mozo \\ Menu')
  },
  methods: {
    // OBTENER PLATILLOS
    async getPlatillos () {
      try {
        if (this.backup.platillos.length !== 0) {
          this.searchQuery = ''
          return
        }
        this.loadingTitleMutation('Actualizando información')
        this.loadingDialogMutation(true)

        if (this.allPlatillosState.length === 0 || this.refresh) {
          await this.allPlatillosAction()
          this.refresh = false
        }

        if (this.allPlatillosState.data) {
          const platillos = this.allPlatillosState.data
          if (platillos.length > 0) {
            this.allPlatillos = platillos.filter(e => e.condicion)
            this.page = 1
            this.paginate()
            this.searchDisabled = false
            this.messagePlatillos = ''
          } else {
            this.messagePlatillos = 'No se encontraron platillos'
            this.pageTotal = 0
          }
        } else {
          this.messagePlatillos = 'No se encontraron platillos'
          this.platillos = []
          this.pageTotal = 0
        }
      } catch (error) {
        this.pageTotal = 0
        this.messagePlatillos = 'Error al conctar con el servidor'
      } finally {
        this.loadingDialogMutation(false)
      }
    },
    refreshPlatillos () {
      this.refresh = true
      this.getPlatillos()
      this.getCategorias()
      this.getDetail()
    },
    // PAGINAR PLATILLOS
    paginate () {
      if (this.allPlatillos.length > this.pagination) {
        this.platillos = this.allPlatillos.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page))
        this.pageTotal = Math.ceil(this.allPlatillos.length / this.pagination)
      } else {
        this.platillos = this.allPlatillos
        this.pageTotal = 0
      }
    },
    // SEARCH PLATILLOS
    searchPlatillos (query) {
      if (query !== '' && query !== null) {
        if (this.platillos.length !== 0 && this.backup.platillosIndex) {
          this.backup.platillos = this.platillos
          if (this.pageTotal !== 0) {
            this.backup.pageTotal = this.pageTotal
          }
          this.backup.platillosIndex = false
        }

        if (this.searchMessage.length !== 0) {
          this.searchMessage = ''
        }

        if (this.typeOfSearch === 'codigo') {
          this.platillos = this.allPlatillos.filter(e => e.codigo.toLowerCase().includes(query.toLowerCase()))
        } else {
          this.platillos = this.allPlatillos.filter(e => e.nombre.toLowerCase().includes(query.toLowerCase()))
        }

        if (this.platillos.length === 0) {
          this.searchMessage = `No se encontraron platillos con el nombre "${query}"`
        }
        this.pageTotal = 0
      } else {
        if (this.backup.platillos.length !== 0) {
          this.platillos = this.backup.platillos
          this.backup.platillos = []
          this.searchMessage = ''
          if (this.backup.pageTotal !== 0) {
            this.pageTotal = this.backup.pageTotal
            this.backup.pageTotal = 0
          }
          this.backup.platillosIndex = true
        }
      }
    },
    // OBTENER CATEGORIAS
    async getCategorias () {
      try {
        if (this.allCategoriasState.length === 0 || this.refresh) {
          await this.allCategoriasAction()
          this.refresh = false
        }
        if (this.allCategoriasState.data) {
          const categorias = this.allCategoriasState.data
          if (categorias.length > 0) {
            this.categorias = categorias.filter(function (e) {
              return e.condicion
            })
          }
          this.disabledCategories = false
        }
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Error al obtener las categorias',
          color: 'error'
        })
      }
    },
    // Filtrar Platillos
    platillosFiltered (categoria) {
      this.filtered = true
      this.pageTotal = 0
      const platillos = this.allPlatillos.filter(e => {
        return categoria.id === e.categoria_id
      })
      this.platillos = platillos
    },
    // Show All
    showAll () {
      this.categoria = null
      this.filtered = false
      this.page = 1
      this.paginate()
    },
    // DETAILS
    // Toggle Detail
    toggleDetails () {
      this.$refs.details.classList.toggle('Details-active')
      document.querySelector('.Details-arrow').classList.toggle('Details-arrow-active')
      this.overlay = !this.overlay
    },
    // toggle detail modaal
    toggleDetailShow () {
      if (this.$route.params.id) {
        this.detailShow = true
        const self = this
        setTimeout(function () {
          self.toggleDetails()
          self.getDetail()
        }, 1000)
      }
    },
    // getDetail
    async getDetail () {
      try {
        this.loadingPedido = true
        const { data } = await mozoService.getPedido(this.$route.params.id)
        
        const nombre = data.mozo_nombre.split(' ')
        const config = {
          id: data.id,
          nOrden: data.numero_orden,
          mesa: data.mesa_numero,
          mesa_id: data.mesa_id,
          mozo: nombre[0] + data.rol,
          pax: data.mesa_capacidad,
          pedidos: data.detalles_pedidos,
          estado: data.estado,
          total: data.total
        }

        this.ordenes = data.detalles_pedidos
        this.assignDetaials(config)
        this.selecteds = []
        this.checkboxs = []
        this.ordenes.forEach(e => {
          if (e.estado === 'Nuevo') {
            this.checkboxs.push({
              value: 0,
              id: e.key,
              state: 1
            })
          } else {
            this.checkboxs.push({
              value: 0,
              id: e.key,
              state: 0
            })
          }
        })
        this.handlePledidoState()
        this.toggleDisabledSend()
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Error al obtener el pedido',
          color: 'error'
        })
      } finally {
        this.loadingPedido = false
      }
    },
    // Pedidos
    toggleSelect (i) {
      const obj = this.checkboxs[i]
      if (obj.value) {
        this.selecteds.push(obj)
      } else {
        this.selecteds.forEach((e, index, arr) => {
          if (obj.id === e.id) {
            arr.splice(index, 1)
          }
        })
      }
    },
    // Toggle All Selecy
    toggleAllSelect (value) {
      this.selecteds = []
      if (value) {
        this.checkboxs.forEach((e, i, arr) => {
          if (e.state) {
            this.selecteds.push(e)
            arr[i].value = 1
          }
        })
      } else {
        this.checkboxs.forEach((e, i, arr) => {
          if (e.state) {
            arr[i].value = 0
          }
        })
      }
    },
    // ASSIGN
    // assign details
    assignDetaials (config) {
      this.details.preTotal = 0
        let preTotal = 0
      if (!config.total) {
        config.pedidos.forEach(e => {
          preTotal += parseFloat(e.subtotal)
        })
        preTotal = preTotal.toFixed(2)
      } else {
        preTotal = (parseFloat(config.total)).toFixed(2)
      }
      this.details = {
          ...config,
          preTotal
        }
      this.assignTotales(preTotal)
    },
    // assign Totales
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
    // handle total
    handleTotal () {
      this.details.preTotal = 0
      let preTotal = 0

      this.ordenes.forEach(e => {
        preTotal += parseFloat(e.subtotal)
      })
      preTotal = preTotal.toFixed(2)
      this.details = {
        ...this.details,
        preTotal
      }
      this.assignTotales(preTotal)
    },
    // ACTIONS
    // Pedidio Modal
    toggePedidoModal (index) {
      this.pedido.platillo = this.platillos[index]
      this.pedido.title = this.pedido.platillo.nombre
      this.pedido.modal = true
    },
    // Disminuir cantidad de platillos
    decreaseQuantityPlatillos (i) {
      if (this.pedido.cantidad !== 0) this.pedido.cantidad--
    },
    // add Platillo
    addPlatillo () {
      if (!this.pedido.cantidad) return
      if (!this.pedido.comentario) this.pedido.comentario = '-'
      const platillo = this.pedido.platillo
      const precio = Math.round(parseFloat(platillo.precio), 2)
      const pedido = {
        id: platillo.id,
        detalle_pedido_id: 0,
        key: Date.now(),
        nombre_platillo: platillo.nombre,
        comentario: this.pedido.comentario,
        cantidad: this.pedido.cantidad,
        precio: precio,
        subtotal: precio * this.pedido.cantidad,
        estado: 'Nuevo'
      }

      this.checkboxs.push({
        value: 0,
        id: pedido.key,
        state: 1
      })

      this.ordenes.push(pedido)

      this.pedido.cantidad = 1
      this.pedido.comentario = ''
      this.pedido.disabledSendBtn = false
      this.details.estado = 'Nuevo'
      this.handleTotal()
      this.handlePledidoState()
      this.pedido.modal = false
    },
    // Incrementar platillo
    increasePlatillo (id) {
      const pedidoIndex = this.getIndex(id, this.ordenes)
      this.ordenes[pedidoIndex].cantidad++
      this.ordenes[pedidoIndex].subtotal += this.ordenes[pedidoIndex].precio
      this.handleTotal()
    },
    // Decrementar Platillo
    decreasePlatillo (id) {
      const pedidoIndex = this.getIndex(id, this.ordenes)
      if (this.ordenes[pedidoIndex].cantidad === 1) {
        this.removePlatillo(id, pedidoIndex)
        this.toggleDisabledSend()
        this.handleTotal()
        this.handleRemovePedido()
        return
      }
      this.ordenes[pedidoIndex].cantidad--
      this.ordenes[pedidoIndex].subtotal -= this.ordenes[pedidoIndex].precio
      this.handleTotal()
    },
    // Remove Platillo
    removePlatillo (id, index) {
      let pedidoIndex = index

      if (!pedidoIndex) {
        pedidoIndex = this.getIndex(id, this.ordenes)
      }
      this.ordenes.splice(pedidoIndex, 1)

      const indexTwo = this.getIndex(id, this.checkboxs)
      this.checkboxs.splice(indexTwo, 1)
      this.handleTotal()
      this.toggleDisabledSend()
      this.handleRemovePedido()
    },
    // Remove Platillos
    removePlatillos () {
      if (this.checkbox) {
        this.checkbox = false
        this.selecteds = []

        const checboxArr = []
        this.checkboxs.forEach(e => {
          if (e.state) checboxArr.push(e.id)
        })
        checboxArr.forEach(e => {
          const index = this.getIndex(e, this.ordenes)
          this.checkboxs.splice(index, 1)
        })

        const ordenesArr = []
        this.ordenes.forEach(e => {
          if (e.estado === 'Nuevo') ordenesArr.push(e.key)
        })
        ordenesArr.forEach(e => {
          const index = this.getIndex(e, this.ordenes)
          this.ordenes.splice(index, 1)
        })

        this.pedido.disabledSendBtn = true
      } else {
        this.selecteds.forEach((e, i) => {
          const indexOne = this.getIndex(e.id, this.ordenes)
          this.ordenes.splice(indexOne, 1)
          const indexTwo = this.getIndex(e.id, this.checkboxs)
          this.checkboxs.splice(indexTwo, 1)
        })
        this.toggleDisabledSend()
        this.toggleAllSelect()
      }

      this.handleRemovePedido()
      this.handleTotal()
      this.pedido.remove = false
    },
    // Send data
    async sendData () {
      try {
        this.pedido.loading = true
        this.pedido.disabled = true
        const IGV = 0
        const detallesPedido = []
        this.ordenes.forEach(e => {
          if (e.estado === 'Nuevo') {
            const precio = Math.round(parseFloat(e.precio), 2)
            const value = Math.round(parseFloat(e.subtotal), 2)
            const pedido = {
              mesa_id: this.details.mesa_id,
              platillo_id: e.id,
              cantidad: e.cantidad,
              valor_unitario: precio,
              precio_unitario: precio * (1 + IGV),
              comentario: e.comentario,
              subtotal: value,
              total: value * (1 + IGV),
              estado: 'Preparado'
            }
            detallesPedido.push(pedido)
          }
        })

        this.selecteds = []
        this.checkboxs.forEach((e, i, arr) => {
          arr[i].state = 0
        })
        this.pedido.disabledSendBtn = true

        const { data } = await mozoService.updatePedido(detallesPedido, this.details.id)
        
        // inyectar id
        let index = 0
        this.ordenes.forEach((e, i, arr) => {
          if (e.estado === 'Nuevo') {
            arr[i].detalle_pedido_id = data[index].id
            arr[i].estado = 'Preparado'
            index++
          }
        })
        
        this.details.estado = 'Produccion'
        this.handlePledidoState()

        this.snackbarMutation({
          value: true,
          text: 'Pedido enviado.',
          color: 'success'
        })
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Error al enviar el pedido.',
          color: 'error'
        })
      } finally {
        this.pedido.loading = false
        this.pedido.disabled = false
        this.pedido.send = false
      }
    },
    // Split
    // Open Split Modal
    async openSplitModal () {
      try {
        this.pedido.mesasLoading = true
        this.pedido.split = true
        await this.getPedidosAction()
        this.pedido.mesas = this.allPedidosState
        this.pedido.pedidoFiltered = this.ordenes.filter(e => e.estado === 'Preparado')
        this.pedido.mesasModel = []
        this.pedido.pedidoFiltered.forEach(e => {
          const obj = {
            value: ''
          }
          this.pedido.mesasModel.push(obj)
        })
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Error al enviar el pedido.',
          color: 'error'
        })
      } finally {
        this.pedido.mesasLoading = false
      }
    },
    async splitMesa () {
      try {
        this.pedido.splitLoading = true
        this.pedido.splitDisabled = true
        console.log(this.pedido.mesasModel)
        const request = {
          detalle_pedidos: []
        }
        this.pedido.pedidoFiltered.forEach((e, i) => {
          if (!this.pedido.mesasModel[i].value) return
          console.log(e)
          const obj = {
            id: e.detalle_pedido_id,
            pedido_id: this.details.id,
            mesa_id: this.pedido.mesasModel[i].value
          }
          request.detalle_pedidos.push(obj)
        })
        console.log(request)
        // const { data } = await mozoService.split(request)
        this.snackbarMutation({
          value: true,
          text: 'Split realizado satisfactoriamente.',
          color: 'success'
        })
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Error al hacer split.',
          color: 'error'
        })
      } finally {
        // this.pedido.split = false
        // this.pedido.splitLoading = false
        // this.pedido.splitDisabled = false
      }
    },
    // UTILS
    // get Index
    getIndex (id, arr) {
      return arr.findIndex(e => e.key === id)
    },
    // Toogle Disabled Send Button
    toggleDisabledSend () {
      this.pedido.disabledSendBtn = true
      this.ordenes.forEach(e => {
        if (e.estado === 'Nuevo') this.pedido.disabledSendBtn = false
      })
      if (this.details.estado !== 'Nuevo') this.pedido.disabledSendBtn = true
    },
    // Handle Pedido State
    handlePledidoState () {
      if (this.details.estado) {
        if (this.details.estado === 'Nuevo') {
          this.handleButtonsDisableds(true, true)
        } else if (this.details.estado === 'Produccion') {
          this.handleButtonsDisableds(false, false)
        } else if (this.details.estado === 'Pendiente') {
          this.handleButtonsDisableds(true, true)
        } else if (this.details.estado === 'Finalizado') {
          this.handleButtonsDisableds(true, true)
          // disabled add platillos
        }
      }
    },
    // Handle Remove Pedido
    handleRemovePedido() {
      let index = 0
      this.ordenes.forEach(e => {
        if (index === 1) return
        if (e.estado !== 'Nuevo' && e.estado === 'Preparado') {
          this.details.estado = 'Produccion'
          this.handlePledidoState()
        } else if (e.estado !== 'Nuevo' && e.estado === 'Finalizado') {
          this.details.estado = 'Pendiente'
          this.handlePledidoState()
          index = 1
        }
      })
    },
    // Handle Buttons Disableds
    handleButtonsDisableds (split, preCuenta) {
      this.pedido.splitDisabledBtn = split
      this.pedido.preCuentaDisabledBtn = preCuenta
    },
    // Active Class
    activeClass () {
      const active = document.querySelector('.Navbar-link-active')
      if (active) {
        active.classList.remove('Navbar-link-active')
      }
    },
    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'loadingTitleMutation', 'headerBreadcrumbMutation', 'snackbarMutation']),
    ...mapActions(['allPlatillosAction', 'allCategoriasAction', 'getPedidosAction'])
  }
}
</script>

<style lang="scss" scoped>
.platillo {
  position: relative;
  font-family: 'Concert One', cursive;
  &-text {
     position: absolute;
    top: 45%;
    left: 40%;
    transform: translateY(-50%);
    font-size: 16px;
    width: 45%;
    line-height: 100%;
  }
  &-add {
    position: absolute;
    top: 45%;
    left: 11%;
    transform: translateY(-50%);
    color: #fff;
    font-size: 64px;
    pointer-events: none;
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
  &-single {
    height: 300px;
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
.checkBox {
  transform: translateX(2.25em)
}
.actions {
  cursor: pointer;
  display: inline-block;
  width: 32px;
}
.Utils {
  &-height {
    min-height: 12vh;
  }
}
</style>
