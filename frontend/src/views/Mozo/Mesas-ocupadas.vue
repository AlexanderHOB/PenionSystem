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
              Mesas Ocupadas
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
          v-for="mesa of mesas"
          :key="mesa.id"
          xs12
          sm6
          md4
          lg3
        >
          <v-card>
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
    // Backup
    backup: {
      mesas: [],
      mesasIndex: true,
      pageTotal: 0
    },
    // Data para la paginación
    pagination: 12,
    pageTotal: 0,
    page: 1
  }),
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'allPedidosState'])
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
            this.allMesas = mesas
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
</style>
