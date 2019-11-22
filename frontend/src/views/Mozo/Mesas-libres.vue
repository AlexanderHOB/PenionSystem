<template>

  <LoadingFish v-if="loadingFish" />

  <div v-else>
    <v-layout>
      <v-flex xs12 v-if="messageMesas">
        <ErrorMessage :errorMessage="messageMesas" :refresh="refreshMesas" />
      </v-flex>
      <template v-else>
        <v-flex xs8>
          <v-layout row wrap>
            <v-flex v-for="(mesa, i) of mesas" :key="mesa.id" xs12 sm6 md4 lg3>
              <v-card @click="mesaSelected(i)">
                <v-img
                  :src="require('@/assets/img/mesas/mesaLibre.svg')"
                  contain
                  height="100"
                  class="mesa"
                ></v-img>
                <v-card-title class="pt-0">
                    <h2>{{ mesa.numero }}</h2>
                </v-card-title>
                <v-card-text class="pb-0">
                  <p class="black--text">Capacidad: {{ mesa.capacidad | persona }}</p>
                  <p>{{ mesa.descripcion }}</p>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex xs4>
          <v-card>
            <v-container>
              <v-card-title class="pb-5">
              <v-spacer></v-spacer>
              <h2>Mesa Número {{ mesaSelect.numero }}</h2>
              <v-spacer></v-spacer>
            </v-card-title>
            <v-card-text class="pb-0 black--text">
              <p>Capacidad: {{ mesaSelect.capacidad | persona }}</p>
              <p>Descripción: {{ mesaSelect.descripcion }}</p>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn text color="green">Usar mesa</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
            </v-container>
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
        ></v-pagination>
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
  data:() => ({
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
    pagination: 10,
    pageTotal: 0,
    page: 1,
    // Datos para selección de mesa
    mesaSelect: {}
  }),
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
        if(this.allMesasState.data){
          let mesas = this.allMesasState.data
          if(mesas.length > 0){
            this.allMesas = mesas.filter(e => {
              return e.condicion
            })
            if(!this.allMesas.length) {
              this.messageMesas = 'No se encontraron mesas libres'
              return
            }
            this.mesaSelect = this.allMesas[0]
            this.page = 1
            this.paginate()
            this.messageMesas = ''
          }else {
            this.messageMesas = 'No se encontraron mesas'
            this.pageTotal = 0
          }
        }else {
          this.messageMesas = response.data.message
          this.mesas = []
          this.pageTotal = 0
        }
        
      } catch (error) {
        this.pageTotal = 0
        this.messageMesas = 'Error al conectar con el servidor'
      }finally {
        this.loadingDialogMutation(false)
      }
    },

    refreshMesas(){
      if(this.refreshUI) {
        this.refreshUIMutation(false)
      }
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

    mesaSelected(index) {
      this.mesaSelect = this.mesas[index]      
    },

    ...mapMutations(['loadingDialogMutation', 'loadingTitleMutation', 'loadingFishMutation', 'snackbarMutation', 'refreshUIMutation']),
    ...mapActions(['allMesasAction'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'allMesasState', 'refreshUI'])
  },
  watch: {
    refreshUI() {
      if(this.refreshUI) {
        this.refreshMesas()
      }
    }
  },
  filters: {
    persona(capacidad){
      if(capacidad == 1){
        return capacidad + ' persona'
      }else {
        return capacidad + ' personas'
      }
    }
  },
  async created() {
    this.loadingFishMutation(true)
    await this.getMesas()
    this.loadingFishMutation(false)
  }
}
</script>

<style lang="scss" scoped>
.mesa {
  transform: translateY(25px);
}
</style>
