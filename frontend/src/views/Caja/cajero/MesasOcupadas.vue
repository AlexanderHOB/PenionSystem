<template>

  <loading-fish v-if="loadingFish" />

  <v-container v-else fluid>
    <header class="d-flex justify-space-between align-center my-3">
      <h3 class="title">Mesas Libres</h3>
      <v-btn
        small
        fab
        dark
        color="blue"
        @click="refresh"
      >
        <v-icon>refresh</v-icon>
      </v-btn>
    </header>

    <v-row>
      <v-col
        v-for="(pedido, i) in pedidos"
        :key="pedido.mesa_id"
        cols="2"
      >
        <v-card @click="selectDetail(i)">
          <div class="d-flex flex-no-wrap justify-space-between">
            <div>
              <v-card-title
                class="title pr-0 pl-1"
              >
                Mesa {{ pedido.mesa_numero }}
              </v-card-title>

              <v-card-text class="pl-1">
                <p class="Mesa-capacidad mb-1">Capacidad</p>
                {{ pedido.mesa_capacidad }}
              </v-card-text>
            </div>

            <div class="Mesa-img-box pa-2 pt-4" :style="{'background-color':  pedido.color_mozo}">
              <img src="@/assets/img/mesas/mesaOcupada.svg" alt="mesa" class="Mesa-img">
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>

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
        <v-col class="Details-card red lighten-1" cols="4">
          <v-row class="mx-0 Details-content white pa-2">
            <v-col cols="12" class="pa-0">
              <h3 class="Details-title pt-1 mx-5 title mb-2 text-center">ORDEN</h3>
            </v-col>
            <v-col cols="3" class="pa-0 pl-4 my-2"># Orden</v-col>
            <v-col cols="3" class="pa-0 pr-4 my-2 text-right Details-divider">{{ details.nOrden }}</v-col>
            <v-col cols="3" class="pa-0 my-2">
              <v-btn
                text
                color="blue"
                :loading="loadingMesaBtn"
                @click="toggleEspecial"
                x-small
                class="px-4"
              >
                Mesa
              </v-btn>
            </v-col>
            <v-col cols="3" class="pa-0 pr-4 my-2 text-right">{{ details.mesa }} {{ details.especial ? '(*)' : '' }}</v-col>
            <v-col cols="3" class="pa-0 pl-4 my-2">Mozo</v-col>
            <v-col cols="3" class="pa-0 pr-4 my-2 text-right Details-divider text-truncate">{{ details.mozo }}</v-col>
            <v-col cols="3" class="pa-0 my-2">
              <v-btn
                text
                color="blue"
                x-small
                class="px-4"
                @click="pax = true"
              >
                PAX
              </v-btn>
            </v-col>
            <v-col cols="3" class="pa-0 pr-4 my-2 text-right">{{ details.pax }}</v-col>
            <v-col cols="3" class="pa-0 pl-4 my-2">T/C</v-col>
            <v-col cols="3" class="pa-0 pr-4 my-2 text-right Details-divider">3.20</v-col>
            <v-col cols="3" class="pa-0 pl-4 my-2">T/C</v-col>
            <v-col cols="3" class="pa-0 pr-4 my-2 text-right">1.00</v-col>
            <v-col cols="12" class="pa-0">
              <h3 class="Details-title pt-2 mx-5 title mb-2 text-center">PAGOS</h3>
            </v-col>
            <v-col cols="6" class="pa-0 my-2 text-center">Consumo</v-col>
            <v-col cols="2" class="pa-0 my-2 pl-4 green--text">S/.</v-col>
            <v-col cols="4" class="pa-0 my-2 text-right pr-4">{{ details.preTotal }}</v-col>
            <v-col cols="6" class="pa-0 my-2 text-center">
              <v-btn text color="blue" small @click="descuentos.show = true">
                Descuento
              </v-btn>
            </v-col>
            <v-col cols="2" class="pa-0 my-2 pl-4 green--text">S/.</v-col>
            <v-col cols="4" class="pa-0 my-2 text-right pr-4">{{ details.descuento }}</v-col>
            <v-col cols="6" class="pa-0 py-2 Details-divider"></v-col>
            <v-col cols="2" class="pa-0 py-2 pl-4 green--text">S/.</v-col>
            <v-col cols="4" class="pa-0 py-2 pr-4 text-right">{{ details.total }}</v-col>
            <v-col cols="6" class="pa-0 py-2 text-center Details-divider">TOTAL A PAGAR</v-col>
            <v-col cols="2" class="pa-0 py-2 pl-4 green--text">$</v-col>
            <v-col cols="4" class="pa-0 py-2 pr-4 text-right">{{ details.totalD }}</v-col>
            <v-col cols="6" class="pa-0 py-2 Details-divider"></v-col>
            <v-col cols="2" class="pa-0 py-2 pl-4 green--text">€</v-col>
            <v-col cols="4" class="pa-0 py-2 pr-4 text-right">{{ details.totalE }}</v-col>
            <v-col cols="6" class="pa-0 my-2 text-center">
              <v-btn text color="blue" small>
                SPLITMEZA
              </v-btn>
            </v-col>
              <v-col cols="6" class="pa-0 my-2 text-center">
                <v-btn text color="blue" small>
                  PRE-CUENTA
                </v-btn>
              </v-col>
            <v-col cols="12" class="pa-0 my-2 font-weight-bold text-center">FORMAS DE PAGO</v-col>
            <v-col cols="4" class="pa-0 my-2 text-center">
              <v-btn text color="blue" small @click="soles = true">
                Soles
              </v-btn>
            </v-col>
            <v-col cols="4" class="pa-0 my-2 text-center">
              <v-btn text color="blue" small>
                Dolar
              </v-btn>
            </v-col>
            <v-col cols="4" class="pa-0 my-2 text-center">
              <v-btn text color="blue" small>
                Crédito
              </v-btn>
            </v-col>
            <v-col cols="4" class="pa-0 my-2 text-center">
              <v-btn text color="blue" small>
                Tarjeto
              </v-btn>
            </v-col>
            <v-col cols="4" class="pa-0 my-2 text-center">
              <v-btn text color="blue" small>
                Combo
              </v-btn>
            </v-col>
            <v-col cols="4" class="pa-0 my-2 text-center">
              <v-btn text color="blue" small>
                Registrar
              </v-btn>
            </v-col>
          </v-row>
        </v-col>
        <v-col class="Details-card red lighten-1 pt-5 pr-5" cols="7">
          <img src="@/assets/img/carta/aros.svg" alt="aros" class="Details-card-aros">
          <v-row class="mx-0 Details-content white pa-2">
            <v-col cols="12" class="pa-0">
              <h3 class="Details-title pt-1 mx-5 title mb-2 text-center">PEDIDOS</h3>
            </v-col>
            <v-col cols="2" class="pa-0 my-2 text-center">Selección</v-col>
            <v-col cols="6" class="pa-0 my-2 text-center">Descripción</v-col>
            <v-col cols="2" class="pa-0 my-2 text-center">Cantidad</v-col>
            <v-col cols="2" class="pa-0 my-2 text-center">Precio</v-col>
            <v-col cols="12" class="pa-0">
              <v-row class="mx-0 my-3" v-for="(pedido, i) in details.pedidos" :key="pedido.id">
                <v-col class="pa-0 d-flex justify-center align-center" cols="2">
                  <v-checkbox
                    v-model="checkboxs[i].value"
                    @change="toggleSelect(i)"
                    dense
                    hide-details
                    class="mt-0 pt-0"
                  ></v-checkbox>
                </v-col>
                <v-col class="pa-0" cols="6">
                  <div class="subtitle-2">Ronda Marina (C/S, A/M Causa Lec/S) Doncella</div>
                  <small class="Details-pedidos-desc caption">{{ pedido.comentario }}</small>
                </v-col>
                <v-col class="pa-0 d-flex align-center justify-center" cols="2">{{ pedido.cantidad }}</v-col>
                <v-col class="pa-0 d-flex align-center justify-center" cols="2"> {{ pedido.total }}</v-col>
              </v-row>
            </v-col>
            <v-col class="pa-0 my-2 d-flex justify-center align-center" cols="2">
              <v-checkbox
                v-model="checkbox"
                @change="toggleAllSelect"
                dense
                hide-details
                class="mt-0 pt-0"
              ></v-checkbox>
            </v-col>
            <v-col class="pa-0 my-2 text-center" cols="6">
              Seleccionar Todo
            </v-col>
            <v-col class="pa-0 my-2 text-center" cols="4">
              <v-btn text color="blue" small @click="openReducir">
                Reducir ({{ numberOfSelecteds }})
              </v-btn>
            </v-col>
            <v-col class="fill-height"></v-col>
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
        ></v-pagination>
      </div>
    </template>
    
    <v-dialog
      v-model="descuentos.show"
      width="500"
    >
      <v-card>
        <v-card-title
          class="headline grey lighten-3"
          primary-title
        >
          Tipo de descuento
        </v-card-title>

        <v-card-text>
          <v-form
            ref="form"
            lazy-validation
            v-model="valid"
            @submit.prevent="setDescuento"
            autocomplete="off"
          >
            <v-row align="center">
              <v-col cols="6">
                <v-radio-group v-model="descuentos.radioGroup" @change="toggleDescuentoType">
                  <v-radio
                    v-for="item in descuentos.items"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value"
                  ></v-radio>
                </v-radio-group>
              </v-col>
              <v-col v-show="descuentosInput" cols="6">
                <v-text-field
                  label="Porcentaje"
                  v-model="porcentaje"
                  suffix="%"
                  v-mask="'###'"
                  :rules="[rules.porcentaje]"
                />
              </v-col>
              <v-col v-show="!descuentosInput" cols="6">
                <v-text-field
                  label="Valor"
                  v-model="valor"
                  prefix="S/"
                  :rules="[rules.number]"
                />
              </v-col>
              <v-col cols="12" class="d-none">
                <v-btn type="submit"></v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="setDescuento"
            :disabled="!valid" 
          >
            Confirmar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="reducir"
      width="500"
    >
      <v-card>
        <v-card-title
          class="headline grey lighten-3"
          primary-title
        >
          Reducir
        </v-card-title>

        <v-card-text>
          <h4 class="title mt-3">Lista de pedidos</h4>
          <div class="my-3" v-for="(pedido, i) in selectedPedidos" :key="pedido.id">
            <v-row class="mx-0 mb-2">
              <v-col class="pa-0" cols="8">
                <div class="subtitle-2">Ronda Marina (C/S, A/M Causa Lec/S) Doncella</div>
                <small class="Details-pedidos-desc caption">{{ pedido.comentario }}</small>
              </v-col>
              <v-col class="pa-0 d-flex align-center justify-center" cols="2">{{ pedido.cantidad }}</v-col>
              <v-col class="pa-0 d-flex align-center justify-center" cols="2"> {{ pedido.total }}</v-col>
            </v-row>
            <v-divider></v-divider>
          </div>
          
          <v-form
            ref="formReducir"
            lazy-validation
            v-model="validReducir"
            @submit.prevent="handleReducir"
          >
            <v-textarea
              v-model="justificacion"
              counter
              :rules="[rules.required]"
              label="Justificación"
            ></v-textarea>
          </v-form>
        </v-card-text>
        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="handleReducir"
            :disabled="!validReducir" 
          >
            Confirmar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="pax"
      width="500"
    >
      <v-card>
        <v-card-title
          class="headline grey lighten-3"
          primary-title
        >
          PAX - Mesa {{ details.mesa }}
        </v-card-title>

        <v-card-text>
          <v-form
            ref="formReducir"
            lazy-validation
            v-model="validPax"
            @submit.prevent="handlePax"
          >
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    label="Nro. Comensales"
                    v-model="nComensales"
                    v-mask="'##'"
                  />
                </v-col>
                <v-col cols="12">
                  <v-select
                    :items="mozos"
                    label="Seleccione Mozo"
                  ></v-select>
                </v-col>
              </v-row>
            </v-container>
          </v-form>
        </v-card-text>
        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="handlePax"
            :disabled="!validPax" 
          >
            Confirmar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="soles"
      width="500"
    >
      <v-card>
        <v-card-title
          class="headline grey lighten-3"
          primary-title
        >
          Soles
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="5">Efectivo</v-col>
              <v-col cols="2" class="text-center">S/.</v-col>
              <v-col cols="5" class="text-right">60.00</v-col>
              <v-col cols="5">Total a cobrar</v-col>
              <v-col cols="2" class="text-center">S/.</v-col>
              <v-col cols="5" class="text-right">49.00</v-col>
              <v-col cols="5">Cambio</v-col>
              <v-col cols="2" class="text-center">S/.</v-col>
              <v-col cols="5" class="text-right">11.00</v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            text
            @click="soles = false"
          >
            Confirmar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

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

import LoadingFish from '@/components/loading/LoadingFish'
import AlertNotifications from '@/components/messages/AlertNotifications'
import LoadingDialog from '@/components/loading/LoadingDialog'

export default {
  components: {
    LoadingFish,
    LoadingDialog,
    AlertNotifications
  },
  directives: {
    mask
  },
  data () {
    return {
      checkbox: false,
      // General
      overlay: false,
      // loaders
      loadingFish: true,
      // Mesas
      pedidos: [],
      allPedidos: [],
      backup: {
        pedidos: [],
        pedidosIndex: true,
        pageTotal: 0
      },
      // Pagination
      pagination: 12,
      pageTotal: 0,
      page: 1,
      // Forms
      rules: {
        required: value => !!value || 'Required.',
        porcentaje: value => value <= 100 || '100% como maximo',
        number: value => !isNaN(value) || 'Solo números'
      },
      valid: true,
      // Details
      detailActive: false,
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
      checkboxs: [],
      selected:[],
      loadingMesaBtn: false,
      // Descuentos
      descuentos: {
        show: false,
        radioGroup: 1,
        items: [
          { label: 'Porcentaje', value: 1 },
          { label: 'Valor', value: 2 }
        ]
      },
      descuentosInput: true,
      porcentaje: 0,
      valor: 0,
      // Reducir
      reducir: false,
      validReducir: true,
      selectedPedidos: [],
      justificacion: '',
      // Pax
      pax: false,
      validPax: true,
      nComensales: 0,
      mozos: [
        'aomine',
        'nose'
      ],
      // Pagos
      soles: false
    }
  },
  methods: {
    // INIT COMPONENT
    // Get mesas
    async getMesas () {
      try {
        if (this.allMesasState.length === 0)  await this.getMesasAction()

        this.allPedidos = [ ...this.allMesasState.pedidos ]
        this.page = 1
        this.paginate()
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Ocurrio un error en el servidor :c',
          color: 'error'
        })
      } finally {
        this.loadingFish = false
      }
    },
    // Refresh mesas
    async refresh () {
      try {
        this.loadingTitleMutation('Actualizando información');
        this.loadingDialogMutation(true)
        await this.getMesasAction()
        this.allPedidos = [ ...this.allMesasState.pedidos ]
        this.page = 1
        this.paginate()
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Ocurrio un error en el servidor :C',
          color: 'error'
        })
      } finally {
        this.loadingDialogMutation(false)
      }
    },
    // Paginate
    paginate () {
      if(this.allPedidos.length > this.pagination){
        this.pedidos = this.allPedidos.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page))
        this.pageTotal = Math.ceil(this.allPedidos.length / this.pagination)
      }else {
         this.pedidos = this.allPedidos
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
    selectDetail (i) {
      const pedido = this.pedidos[i]
      let nombre = pedido.nombre_mozo.split(' ')
      const config = {
        nOrden: pedido.numero_orden,
        mesa: pedido.mesa_numero,
        especial: pedido.especial,
        mozo: nombre[0]+pedido.rol_mozo,
        pax: pedido.mesa_capacidad,
        pedidos: pedido.detalles_pedidos,
        descuento: 0
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

      this.toggleDetails()
    },
    // DETAILS ACTIONS
    // Descuentos
    toggleDescuentoType () {
      this.descuentosInput = !this.descuentosInput
      this.$refs.form.resetValidation()
    },
    setDescuento () {
      if (!this.$refs.form.validate()) return
      if (this.descuentosInput) {
        this.details.descuento = (this.details.preTotal * (this.porcentaje / 100)).toFixed(2)
      } else {
        this.details.descuento = this.valor
      }
      this.assignTotales(this.details.preTotal, this.details.descuento)
      this.descuentos.show = false
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
    // Reducir
    openReducir () {
      if (this.selected.length === 0) return
      this.selectedPedidos = this.details.pedidos.filter(e => {
        let verify = false
        this.selected.forEach(item => {
          if (item.id === e.id) {
            verify = true
          }
        })
        return verify
      })
      this.reducir = true
    },
    async handleReducir () {
      if (!this.$refs.formReducir.validate()) return
      try {
      this.reducir = false
      } catch (error) {
        
      } finally {

      }
    },
    // Mesa
    async toggleEspecial () {
      try {
        this.loadingMesaBtn = true
        this.details.especial = !this.details.especial
      } catch (error) {
        console.log(error)        
      } finally {
        this.loadingMesaBtn = false
      }
    },
    // Pax
    handlePax () {
      if (!this.$refs.formPax.validate()) return
      try {
        this.pax = false
      } catch (error) {
        
      } finally {

      }
    },
    // ASSIGN
    // assign details
    assignDetaials (config) {
      this.details.preTotal = 0
      let preTotal = 0
      
      config.pedidos.forEach(e => {
        preTotal += parseFloat(e.total)
      })
      preTotal = preTotal.toFixed(2)
      this.details = { 
        ...config,
        preTotal
     }
    this.assignTotales(preTotal, config.descuento)
    },
     assignTotales (preTotal, descuento) {
      const total = (preTotal - descuento).toFixed(2)
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
    ...mapActions(['getMesasAction']),
    ...mapMutations(['snackbarMutation', 'loadingTitleMutation', 'loadingDialogMutation'])
  },
  computed: {
    numberOfSelecteds () {
      return this.selected.length
    },
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
      width: 100px;
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