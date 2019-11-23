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
              :add-platillo="addPlatillo"
              :index="i"
            />
            <p class="platillo-text text-center">
              {{ platillo.nombre }}
            </p>
            <span class="platillo-add">+</span>
          </v-flex>
        </v-layout>
      </v-flex>
      <!-- <v-flex
        v-show="!messagePlatillos"
        xs4
      >
        <v-card>
          <v-card-title class="pb-5">
            <v-spacer />
            <h2>PEDIDO</h2>
            <v-spacer />
          </v-card-title>
          <v-card-text class="black--text">
            <v-layout
              row
              wrap
            >
              <v-flex xs4>
                <strong>N° pedido</strong>
              </v-flex>
              <v-flex xs8>
                12345
              </v-flex>
              <v-flex xs4>
                <strong>Mozo</strong>
              </v-flex>
              <v-flex xs8>
                luisMozo
              </v-flex>
              <v-flex xs4>
                <strong>Mesa</strong>
              </v-flex>
              <v-flex xs8>
                1
              </v-flex>
              <v-flex xs12>
                <v-data-table
                  :headers="headers"
                  :items="ordenes"
                  class="elevation-1"
                >
                  <template v-slot:item.action="{ item }">
                    <div class="text-center px-0">
                      <img
                        src="@/assets/img/mozo/eliminar.svg"
                        alt="aumentar"
                        class="actions"
                        @click="removePlatillo(item.id)"
                      >
                      <img
                        src="@/assets/img/mozo/aumentar.svg"
                        alt="aumentar"
                        class="actions"
                        @click="increasePlatillo(item.id)"
                      >
                      <img
                        src="@/assets/img/mozo/disminuir.svg"
                        alt="aumentar"
                        class="actions"
                        @click="decreasePlatillo(item.id)"
                      >
                    </div>
                  </template>
                </v-data-table>
              </v-flex>
            </v-layout>
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn
              class="yellow"
              rounded
            >
              Comanda
            </v-btn>
            <v-spacer />
          </v-card-actions>
        </v-card>
      </v-flex> -->
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
                  v-for="(pedido, i) in details.pedidos"
                  :key="pedido.id"
                  class="mx-0 my-3"
                >
                  <v-col
                    class="pa-0 checkBox d-flex justify-center align-center"
                    cols="1"
                  >
                    <v-checkbox
                      v-model="checkboxs[i].value"
                      dense
                      hide-details
                      class="mt-0 pt-0"
                      @change="toggleSelect(i)"
                    />
                  </v-col>
                  <v-col
                    class="pa-0 pt-3 text-center"
                    cols="3"
                  >
                    <!-- @click="removePlatillo(item.id)" -->
                    <!-- @click="increasePlatillo(item.id)" -->
                    <!-- @click="decreasePlatillo(item.id)" -->
                    <img
                      src="@/assets/img/mozo/eliminar.svg"
                      alt="aumentar"
                      class="actions"
                    >
                    <img
                      src="@/assets/img/mozo/aumentar.svg"
                      alt="aumentar"
                      class="actions"
                    >
                    <img
                      src="@/assets/img/mozo/disminuir.svg"
                      alt="aumentar"
                      class="actions"
                    >
                  </v-col>
                  <v-col
                    class="pa-0"
                    cols="5"
                  >
                    <div class="subtitle-2">
                      {{ pedido.nombre_platillo }}
                    </div>
                    <small class="Details-pedidos-desc caption">{{ pedido.comentario }}</small>
                  </v-col>
                  <v-col
                    class="pa-0 d-flex align-center justify-center"
                    cols="1"
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
                <!-- @click="openReducir" -->
                <v-btn
                  text
                  color="blue"
                  small
                >
                  Reducir ({{ numberOfSelecteds }})
                </v-btn>
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
          :total-visible="7"
          circle
          @input="paginate"
          @next="paginate"
          @previous="paginate"
        />
        <div class="Pagination-help" />
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
      headers: [
        { text: 'Actions', align: 'center', sortable: false, value: 'action' },
        { text: 'Pedido', align: 'center', width: 120, sortable: false, value: 'platillo' },
        { text: 'Cant', align: 'center', class: 'px-0', value: 'cantidad' },
        { text: 'Valor', align: 'center', class: 'px-0', value: 'valor' }
      ],
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
      selected: [],
      loadingPedido: true,
      detailShow: false
    }
  },
  computed: {
    numberOfSelecteds () {
      return this.selected.length
    },
    ...mapState(['loadingFish', 'allPlatillosState', 'allCategoriasState'])
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
      if (this.refreshUI) {
        this.refreshUIMutation(false)
      }
      this.refresh = true
      this.getPlatillos()
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
        if (this.allCategoriasState.length === 0) {
          await this.allCategoriasAction()
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
        this.snackbarMutation({ value: true, text: 'Error al obtener las categorias', color: 'error' })
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
    toggleDetailShow () {
      if (this.$route.params.id) {
        this.detailShow = true
        const self = this
        setTimeout(function () {
          self.toggleDetails()
          self.selectDetail()
        }, 1000)
      }
    },
    async selectDetail () {
      try {
        this.loadingPedido = true
        const { data } = await mozoService.getPedido(this.$route.params.id)
        const nombre = data.mozo_nombre.split(' ')
        const config = {
          nOrden: data.numero_orden,
          mesa: '25',
          mozo: nombre[0] + data.rol,
          pax: '6',
          pedidos: data.detalles_pedidos
        }
        this.assignDetaials(config)
        this.selected = []
        this.checkboxs = []
        config.pedidos.forEach(e => {
          this.checkboxs.push({
            value: 0,
            id: e.id
          })
        })
        this.loadingPedido = false
      } catch (error) {
        console.log(error)
      }
    },
    // Pedidos
    toggleSelect (i) {
      const obj = this.checkboxs[i]
      if (obj.value) {
        this.selected.push(obj)
      } else {
        this.selected.splice(i, 1)
      }
    },
    toggleAllSelect (value) {
      this.selected = []
      if (value) {
        this.checkboxs.forEach((e, i, arr) => {
          this.selected.push(e)
          arr[i].value = 1
        })
      } else {
        this.checkboxs.forEach((e, i, arr) => {
          arr[i].value = 0
        })
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
    // ACTIONS
    // add Platillo
    addPlatillo (index) {
      const platillo = this.platillos[index]
      let pedido = null
      if (this.ordenes.length) {
        const pedidoIndex = this.ordenes.findIndex(e => e.id === platillo.id)
        if (pedidoIndex !== -1) {
          this.ordenes[pedidoIndex].cantidad = this.ordenes[pedidoIndex].cantidad + 1
          return
        }
      }

      pedido = {
        id: platillo.id,
        platillo: platillo.nombre,
        msg: 'poca cantidad de sal',
        cantidad: 1,
        valor: platillo.precio
      }

      this.ordenes.push(pedido)
    },
    // Incrementar platillo
    increasePlatillo (id) {
      const pedidoIndex = this.getIndex(id, this.ordenes)
      this.ordenes[pedidoIndex].cantidad = this.ordenes[pedidoIndex].cantidad + 1
    },
    // Decrementar Platillo
    decreasePlatillo (id) {
      const pedidoIndex = this.getIndex(id, this.ordenes)
      if (this.ordenes[pedidoIndex].cantidad === 1) {
        this.removePlatillo(id, pedidoIndex)
        return
      }
      this.ordenes[pedidoIndex].cantidad = this.ordenes[pedidoIndex].cantidad - 1
    },
    // Remove Platillo
    removePlatillo (id, index) {
      let pedidoIndex = index
      if (!pedidoIndex) {
        pedidoIndex = this.getIndex(id, this.ordenes)
      }

      this.ordenes.splice(pedidoIndex, 1)
    },
    // get Index
    getIndex (id, arr) {
      return arr.findIndex(e => e.id === id)
    },
    // Active Class
    activeClass () {
      const active = document.querySelector('.Navbar-link-active')
      if (active) {
        active.classList.remove('Navbar-link-active')
      }
    },
    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'loadingTitleMutation', 'headerBreadcrumbMutation', 'snackbarMutation']),
    ...mapActions(['allPlatillosAction', 'allCategoriasAction'])
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
.Pagination {
  &-help {
    min-height: 12vh;
  }
}
</style>
