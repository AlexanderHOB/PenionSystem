<template>

  <loading-fish v-if="loadingFish" />

  <v-container v-else fluid class="">
        <h3 class="title">Mesas Libres</h3>
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
                  <img src="../../assets/img/mesas/mesaLibre.svg" alt="mesa" class="Mesa-img">
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

        <AlertNotifications />

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
</style>