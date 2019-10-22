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
        v-for="mesa in mesas"
        :key="mesa.id"
        cols="2"
      >
        <v-card>
          <div class="d-flex flex-no-wrap justify-space-between">
            <div>
              <v-card-title
                class="title pr-0"
              >
                Mesa {{ mesa.numero }}
              </v-card-title>

              <v-card-text>
                <p class="Mesa-capacidad mb-1">Capacidad</p>
                {{ mesa.capacidad }}
              </v-card-text>
            </div>

            <div class="Mesa-img-box pa-2">
              <img src="@/assets/img/mesas/mesaLibre.svg" alt="mesa" class="Mesa-img">
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>

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

    <loading-dialog />

    <alert-notifications />

  </v-container>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'

import LoadingFish from '@/components/loading/LoadingFish'
import AlertNotifications from '@/components/messages/AlertNotifications'
import LoadingDialog from '@/components/loading/LoadingDialog'


export default {
  components: {
    LoadingDialog,
    LoadingFish,
    AlertNotifications
  },
  data () {
    return {
      checkbox: false,
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
      page: 1
    }
  },
  methods: {
    // Get mesas
    async getMesas () {
      try {
        if (this.allMesasState.length === 0) {
          await this.getMesasAction()
        }

        this.allMesas = [ ...this.allMesasState.libres ]
        this.page = 1
        this.paginate()
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Ocurrio un error en el servidor :C',
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
        this.allMesas = [ ...this.allMesasState.libres ]
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
      if(this.allMesas.length > this.pagination){
        this.mesas = this.allMesas.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page))
        this.pageTotal = Math.ceil(this.allMesas.length / this.pagination)
      }else {
         this.mesas = this.allMesas
          this.pageTotal = 0
      }
    },
    ...mapActions(['getMesasAction']),
    ...mapMutations(['snackbarMutation', 'loadingTitleMutation', 'loadingDialogMutation'])
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
</style>