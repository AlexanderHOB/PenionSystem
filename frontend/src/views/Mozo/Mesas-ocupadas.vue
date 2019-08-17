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
              <v-card class="mesa-card">
                <v-img
                  :src="require('../../assets/img/mesas/mesaOcupada.svg')"
                  contain
                  height="100"
                  class="mesa"
                ></v-img>
                <v-card-title class="pt-0">
                  <h2>{{ mesa.numero }}</h2>
                  <div class="mesa-color"  :style="{'background-color':  '#2151aa'}"></div>
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
              <h2>Mozos</h2>
              <v-spacer></v-spacer>
            </v-card-title>
            <v-card-text class="text-center">
              <p class="mozo-name" :style="{'background-color':  '#2151aa'}">Tenorio Camarena Felix Martin</p>
              <p class="mozo-name" :style="{'background-color':  '#ffa12a'}">Tenorio Camarena Felix Martin</p>
              <p class="mozo-name" :style="{'background-color':  '#02aab2'}">Tenorio Camarena Felix Martin</p>
              <p class="mozo-name" :style="{'background-color':  '#c2a2ac'}">Luisn Enrique Roque Ccantp</p>
              <p class="mozo-name" :style="{'background-color':  '#f55ac4'}">Tenorio Camarena Felix Martin</p>
            </v-card-text>
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
import axios from 'axios';

import LoadingDialog from '../../components/loading/LoadingDialog'
import LoadingFish from '../../components/loading/LoadingFish'
import ErrorMessage from '../../components/messages/ErrorMessage'
import AlertNotifications from '../../components/messages/AlertNotifications'

import { mapState, mapMutations, mapActions } from 'vuex'

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
    async getMesas(){
      try {
        this.loadingTitleMutation('Actualizando información');
        this.loadingDialogMutation(true);

        if(this.allMesasState.length == 0 || this.refresh){
          await this.allMesasAction();
          this.refresh = false;
        }
        if(this.allMesasState.data){
          let mesas = this.allMesasState.data;
          if(mesas.length > 0){
            this.allMesas = mesas.filter(e => {
              return !e.condicion
            });
            if(!this.allMesas.length) {
              this.messageMesas = 'No se encontraron mesas ocupadas';
              return
            }
            this.mesaSelect = this.allMesas[0]
            this.page = 1;
            this.paginate();
            this.messageMesas = '';
          }else {
            this.messageMesas = 'No se encontraron mesas';
            this.pageTotal = 0;
          }
        }else {
          this.messageMesas = response.data.message;
          this.mesas = [];
          this.pageTotal = 0;
        }
        
      } catch (error) {
        this.pageTotal = 0;
        this.messageMesas = 'Error al conectar con el servidor';
      }finally {
        this.loadingDialogMutation(false);
      }
    },

    refreshMesas(){
      this.refresh = true;
      this.getMesas();
    },

    // PAGINAR MESAS
    paginate(){
      if(this.allMesas.length > this.pagination){
        this.mesas = this.allMesas.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page));
        this.pageTotal = Math.ceil(this.allMesas.length / this.pagination);
      }else {
         this.mesas = this.allMesas;
          this.pageTotal = 0;
      }
    },

    mesaSelected(index) {
      this.mesaSelect = this.mesas[index]      
    },

    ...mapMutations(['loadingDialogMutation', 'loadingTitleMutation', 'loadingFishMutation', 'snackbarMutation']),
    ...mapActions(['allMesasAction'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'allMesasState'])
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
    this.loadingFishMutation(true);
    await this.getMesas();
    this.loadingFishMutation(false);
  }
}
</script>

<style lang="scss" scoped>
.mesa {
  transform: translateY(25px);
  &-card {
    position: relative;
  }
  &-color {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 25px;
    height: 25px;
    border-radius: 50%;
  }
}
.mozo {
  &-name {
    padding: 8px 0;
    font-size: 16px;
    border-radius: 8px;
  }
}
</style>
