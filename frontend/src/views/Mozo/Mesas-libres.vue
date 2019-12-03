<template>
  <LoadingFish v-if="loadingFish" />

  <div v-else>
    <v-layout wrap>
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
        <v-flex xs12>
          <header class="d-flex justify-space-between align-center my-3">
            <h3 class="title">
              Mesas Libres
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
          v-for="(mesa, i) of mesas"
          :key="mesa.id"
          xs12
          sm6
          md4
          lg3
        >
          <v-card @click="mesaSelected(i)">
            <div class="d-flex flex-no-wrap justify-space-between">
              <div>
                <v-card-title
                  class="title pr-0"
                >
                  Mesa {{ mesa.numero }}
                </v-card-title>

                <v-card-text>
                  <p class="Mesa-capacidad mb-1">
                    Capacidad
                  </p>
                  {{ mesa.capacidad | persona }}
                </v-card-text>
              </div>

              <div class="Mesa-img-box pa-2">
                <img
                  src="@/assets/img/mesas/mesaLibre.svg"
                  alt="mesa"
                  class="Mesa-img"
                >
              </div>
            </div>
          </v-card>
        </v-flex>
      </template>
    </v-layout>

    <v-dialog
      v-model="detail"
      max-width="600"
      :persistent="loadingBtn"
    >
      <v-card>
        <v-container>
          <v-card-title class="pb-5">
            <v-spacer />
            <h2>Mesa Número {{ mesaSelect.numero }}</h2>
            <v-spacer />
          </v-card-title>
          <v-card-text class="pb-0 black--text">
            <p>Capacidad: {{ mesaSelect.capacidad | persona }}</p>
            <p>Descripción: {{ mesaSelect.descripcion }}</p>
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn
              text
              color="green"
              :loading="loadingBtn"
              @click="goPedido"
            >
              Usar mesa
            </v-btn>
            <v-spacer />
            <v-btn
              text
              color="red"
              :disabled="loadingBtn"
              @click="detail = false"
            >
              Cancelar
            </v-btn>
            <v-spacer />
          </v-card-actions>
        </v-container>
      </v-card>
    </v-dialog>

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

    <LoadingDialog />

    <AlertNotifications />
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
    title: 'Mesas',
    // Data para las mesas
    messageMesas: '',
    mesas: [],
    allMesas: [],
    isLoadBtn: false,
    disabled: false,
    refresh: false,
    index: 0,
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
    // Datos para selección de mesa
    detail: false,
    mesaSelect: {},
    loadingBtn: false
  }),
  computed: {
    ...mapState(['auth', 'loadingFish', 'allMesasState'])
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

        if (this.allMesasState.length === 0 || this.refresh) {
          await this.allMesasAction()
          this.refresh = false
        }
        if (this.allMesasState.data) {
          const mesas = this.allMesasState.data
          if (mesas.length > 0) {
            this.allMesas = mesas.filter(e => e.estado === 'Disponible' && e.condicion)
            if (!this.allMesas.length) {
              this.messageMesas = 'No se encontraron mesas libres'
              return
            }
            this.mesaSelect = this.allMesas[0]
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
    // Seleccionar mesa
    mesaSelected (index) {
      this.index = index
      this.mesaSelect = this.mesas[index]
      this.detail = true
    },
    // Ir al pedido
    async goPedido () {
      try {
        this.loadingBtn = true
        const pedido = {
          tipo_pedido: 'Normal',
          mozo_id: this.auth.user.id,
          total: 0,
          descuento: 0,
          especial: 0,
          mesa_id: this.mesaSelect.id
        }

        const { data } = await mozoService.createPedido(pedido)
        this.allMesasSplice(this.mesaSelect.id)
        this.detail = false
        this.$router.push({ name: 'mozo-menu', params: { id: data.pedido_id } })
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Ocurrio un error en el servidor.',
          color: 'error'
        })
      } finally {
        this.loadingBtn = false
      }
    },
    ...mapMutations(['loadingDialogMutation', 'loadingTitleMutation', 'loadingFishMutation', 'snackbarMutation', 'allMesasSplice']),
    ...mapActions(['allMesasAction'])
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
</style>
