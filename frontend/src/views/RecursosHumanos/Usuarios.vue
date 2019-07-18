<template>
  <LoadingFish v-if="loadingFish" />

  <v-container v-else grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 v-if="messagePersonal">
        <ErrorMessage :errorMessage="messagePersonal" :refresh="getPersonal" />
      </v-flex>

      <template v-else>
        <v-flex xs12 class="d-flex align-center">
          <h1 class="display-1">{{ title }}</h1>
          <div class="text-xs-right">
            <v-btn class="blue" dark fab small @click="getPersonal"><v-icon>replay</v-icon></v-btn>
          </div>
        </v-flex>
        <v-flex xs4 v-for="(empleado, i) of personal" :key="empleado.id" class="mb-4">
          <div class="personal-box">
            <div class="personal-content text-xs-center">
              <!-- <h2 class="personal-title">{{ empleado.puesto_trabajo }}:</h2> -->
              <p class="mb-0 personal-apellido">{{ empleado.apellidos }}</p>
              <p class="mb-0 personal-nombre">{{ empleado.nombres }}</p>
            </div>
            <div class="personal-textBox text-xs-center">
              <p class="mb-0 personal-text" v-show="empleado.condicion">Activo</p> 
              <p class="mb-0 personal-text" v-show="!empleado.condicion">Inactivo</p>
            </div>
            <img src="../../assets/iconos/detail.svg" alt="detail" class="personal-detail" @click="detailPersonalModal(i)">
            <PersonalBox class="personal-bg" :active="empleado.condicion" :activeInteraction="activarModal" :index="i" />
          </div>
        </v-flex>
      </template>
    </v-layout>

    
    <template v-if="pageTotal">
      <div class="text-xs-center mt-4">
        <v-pagination
          v-model="page"
          :length="pageTotal"
          color="blue"
          circle
          @input="paginatePersonal"
          @next="paginatePersonal"
          @previous="paginatePersonal"
        ></v-pagination>
      </div>
    </template>

    <LoadingDialog />

    <AlertNotifications />

  </v-container>
</template>

<script>
import axios from 'axios';
import LoadingDialog from '../../components/loading/LoadingDialog';
import LoadingFish from '../../components/loading/LoadingFish';
import PersonalBox from '../../components/box/PersonalBox';
import ErrorMessage from '../../components/messages/ErrorMessage';
import AlertNotifications from '../../components/messages/AlertNotifications';

import { mapState, mapMutations } from 'vuex';

export default {
  components: {
    LoadingDialog,
    LoadingFish,
    PersonalBox,
    ErrorMessage,
    AlertNotifications
  },
  data(){
    return {
      title: 'Usuarios',
      //Datos para el personal
      messagePersonal: '',
      personal: [],
      allPersonal: [],
      personalTotal: 0,
      // Backup
      backup: {
        personal: [],
        personalIndex: true,
        pageTotal: 0
      },
      // Datos para la paginación
      pagination: 5,
      pageTotal: 0,
      page: 1,
      paginateDisabled: false,
    }
  },
  methods: {
    // OBTENER PERSONAL
    async getPersonal(){
      try {
        if(this.backup.personal.length != 0){
          this.searchQueryMutation('');
          return;
        }

        this.loadingTitleMutation('Actualizando información');
        this.loadingDialogMutation(true);

        let response = await axios.get(this.url + 'users', this.config);
        console.log(response);
        if(response.data){
          let personal = response.data;
          if(personal.length > 0){
            this.allPersonal = personal.filter(function(e){
              return e.condicion
            });
            this.paginatePersonal();
            this.messagePersonal = '';
          this.searchDisabledMutation(false);
          }else {
            this.messagePersonal = 'No se encontro ningún usuario';
            this.pageTotal = 0;
          }
          this.headerActionsMutation({create: true, report: false});
        }else {
          this.headerActionsMutation({create: false, report: false});
          this.messagePersonal = response.data.message;
          this.personal = [];
          this.pageTotal = 0;
          this.searchDisabledMutation(true);
        }
      } catch (error) {
        this.headerActionsMutation({create: false, report: false});
        this.pageTotal = 0;
        this.messagePersonal = 'Error al conctar con el servidor';
        this.searchDisabledMutation(true);
      }finally {
        this.loadingDialogMutation(false);
      }
    },

    // PAGINAR PERSONAL
    paginatePersonal(){
      if(this.allPersonal.length > this.pagination){
        this.personal = this.allPersonal.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page));
        this.pageTotal = Math.ceil(this.allPersonal.length / this.pagination);
      }else {
         this.personal = this.allPersonal;
          this.pageTotal = 0;
      }
    },

    // BUSCAR PERSONAL
    searchPersonal(query){
      if(query != '' && query != null){
        if(this.personal.length != 0 && this.backup.personalIndex){
          this.backup.personal = this.personal;
          if(this.pageTotal != 0){
            this.backup.pageTotal = this.pageTotal;
          }
          this.backup.personalIndex = false;
        }

        if(this.messagePersonal.length != 0){
          this.messagePersonal = '';
        }

        this.personal = this.allPersonal.filter(function(e){
          return e.nombres.toLowerCase().search(query.toLowerCase()) != -1 || e.apellidos.toLowerCase().search(query.toLowerCase()) != -1 ;
        });

        if(this.personal.length == 0){
          this.messagePersonal = 'No se encontro usuario con el nombre ' + query;
        }
        
        this.pageTotal = 0;
      }else {
        if(this.backup.personal.length != 0){
          this.personal = this.backup.personal;
          this.backup.personal = [];
          this.messagePersonal = '';
          if(this.backup.pageTotal != 0){
            this.pageTotal = this.backup.pageTotal;
            this.backup.pageTotal = 0;
          }
          this.backup.personalIndex = true;
        }
      }
    },

    // ABRIR PERSONA DETAIL MODAL
    detailPersonalModal(){

    },

    // ACTIVAR MODAL / INACTIVE
    activarModal(){

    },

    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation', 'searchQueryMutation', 'searchPlaceholderMutation', 'searchDisabledMutation'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'createModalState', 'searchQuery'])
  },
    watch: {
    searchQuery(){
      this.searchPersonal(this.searchQuery);
    }
  },
  async created(){
    this.loadingFishMutation(true);
    await this.getPersonal();
    this.loadingFishMutation(false);
  },
  beforeMount(){
    this.headerActionsMutation({create: false, report: false});
    this.breadcrumbMutation('Recursos Humanos \\ Usuarios');
    this.searchPlaceholderMutation('Nombre del usuario...');
    this.searchDisabledMutation(true);
  }
}
</script>

<style lang="scss" scoped>
.personal {
  &-box {
    position: relative;
    font-family: 'Concert One', cursive;
  }
  &-content {
    position: absolute;
    top: 45%;
    left: 72%;
    width: 40%;
    z-index: 2;
    transform: translate(-50%, -50%);
  }
  &-apellido,
  &-nombre,
  &-text {
    font-size: 18px;
  }
  &-title {
    font-size: 24px;
    margin-bottom: 4px;
    line-height: 100%;
  }
  &-textBox {
    position: absolute;
    top: 48%;
    left: 15.5%;
    transform: translateY(-50%);
    width: 27%;
    pointer-events: none;
  }
   &-text{
    color: #fff; 
  }
  &-edit,
  &-detail {
    position: absolute;
    bottom: 0;
    transform: translateY(20px);
    width: 50px;
    transition: .5s;
    cursor: pointer;
    &:hover {
      transform: translateY(20px) scale(1.1);
    }
  }
  &-detail {
    left: 50%;
  }
  &-impresion {
    left: 79%;
  }
  &-bg {
    width: 100%;
    box-shadow: 0 0 6px 2px rgba(0, 0, 0, .2);
    border-radius: 20px;
  }
}
.title-modal {
  background-image: $primary-gradient;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>