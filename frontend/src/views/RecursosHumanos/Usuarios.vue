<template>
  <LoadingFish v-if="loadingFish" />

  <v-container v-else grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 v-if="messageUsuarios">
        <ErrorMessage :errorMessage="messageUsuarios" :refresh="getUsuarios" />
      </v-flex>

      <template v-else>
        <v-flex xs12 class="d-flex align-center">
          <h1 class="display-1">{{ title }}</h1>
          <div class="text-xs-right">
            <v-btn class="blue" dark fab small @click="refreshUsuarios"><v-icon>replay</v-icon></v-btn>
          </div>
        </v-flex>
        <v-flex xs4 v-for="(usuario, i) of usuarios" :key="usuario.id" class="mb-4">
          <div class="usuarios-box">
            <div class="usuarios-content text-xs-center">
              <!-- <h2 class="usuarios-title">{{ usuario.puesto_trabajo }}:</h2> -->
              <p class="mb-0 usuarios-apellido">{{ usuario.apellidos }}</p>
              <p class="mb-0 usuarios-nombre">{{ usuario.nombres }}</p>
            </div>
            <div class="usuarios-textBox text-xs-center">
              <p class="mb-0 usuarios-text" v-show="usuario.condicion">Activo</p> 
              <p class="mb-0 usuarios-text" v-show="!usuario.condicion">Inactivo</p>
            </div>
            <img src="../../assets/iconos/detail.svg" alt="detail" class="usuarios-detail" @click="detailUsuariolModal(i)">
            <PersonalBox class="usuarios-bg" :active="usuario.condicion" :activeInteraction="activarModal" :index="i" />
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
          @input="paginate"
          @next="paginate"
          @previous="paginate"
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

import { mapState, mapMutations, mapActions } from 'vuex';

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
      //Datos para el usuario
      messageUsuarios: '',
      usuarios: [],
      allUsuarios: [],
      refresh: false,
      // Backup
      backup: {
        usuarios: [],
        usuariosIndex: true,
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
    // OBTENER USUARIOS
    async getUsuarios(){
      try {
        if(this.backup.usuarios.length != 0){
          this.searchQueryMutation('');
          return;
        }

        this.loadingTitleMutation('Actualizando información');
        this.loadingDialogMutation(true);

        // let response = await axios.get(this.url + 'users', this.config);
        if(this.allUsuariosState.length == 0 || this.refresh){
          await this.allUsuariosAction();
          this.refresh = false;
        }
        if(this.allUsuariosState.data){
          let usuarios = this.allUsuariosState.data;
          if(usuarios.length > 0){
            this.allUsuarios = usuarios.filter(function(e){
              return e.condicion
            });
            this.paginate();
            this.messageUsuarios = '';
          this.searchDisabledMutation(false);
          }else {
            this.messageUsuarios = 'No se encontro ningún usuario';
            this.pageTotal = 0;
          }
          this.headerActionsMutation({create: true, report: false});
        }else {
          this.headerActionsMutation({create: false, report: false});
          this.messageUsuarios = response.data.message;
          this.usuarios = [];
          this.pageTotal = 0;
          this.searchDisabledMutation(true);
        }
      } catch (error) {
        this.headerActionsMutation({create: false, report: false});
        this.pageTotal = 0;
        this.messageUsuarios = 'Error al conctar con el servidor';
        this.searchDisabledMutation(true);
        console.log(error);
      }finally {
        this.loadingDialogMutation(false);
      }
    },

    refreshUsuarios(){
      this.refresh = true;
      this.getUsuarios();
    },

    // PAGINAR USUARIOS
    paginate(){
      if(this.allUsuarios.length > this.pagination){
        this.usuarios = this.allUsuarios.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page));
        this.pageTotal = Math.ceil(this.allUsuarios.length / this.pagination);
      }else {
         this.usuarios = this.allUsuarios;
          this.pageTotal = 0;
      }
    },

    // BUSCAR USUARIOS
    searchUsuarios(query){
      if(query != '' && query != null){
        if(this.usuarios.length != 0 && this.backup.usuariosIndex){
          this.backup.usuarios = this.usuarios;
          if(this.pageTotal != 0){
            this.backup.pageTotal = this.pageTotal;
          }
          this.backup.usuariosIndex = false;
        }

        if(this.messageUsuarios.length != 0){
          this.messageUsuarios = '';
        }

        this.usuarios = this.allUsuarios.filter(function(e){
          return e.nombres.toLowerCase().search(query.toLowerCase()) != -1 || e.apellidos.toLowerCase().search(query.toLowerCase()) != -1 ;
        });

        if(this.usuarios.length == 0){
          this.messageUsuarios = 'No se encontro usuario con el nombre ' + query;
        }
        
        this.pageTotal = 0;
      }else {
        if(this.backup.usuarios.length != 0){
          this.usuarios = this.backup.usuarios;
          this.backup.usuarios = [];
          this.messageUsuarios = '';
          if(this.backup.pageTotal != 0){
            this.pageTotal = this.backup.pageTotal;
            this.backup.pageTotal = 0;
          }
          this.backup.usuariosIndex = true;
        }
      }
    },

    // ABRIR USUARIO DETAIL MODAL
    detailUsuariolModal(){

    },

    // ACTIVAR MODAL / INACTIVE
    activarModal(){

    },

    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation', 'searchQueryMutation', 'searchPlaceholderMutation', 'searchDisabledMutation']),
    ...mapActions(['allUsuariosAction'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'createModalState', 'searchQuery', 'allUsuariosState'])
  },
    watch: {
    searchQuery(){
      this.searchUsuarios(this.searchQuery);
    }
  },
  async created(){
    this.headerActionsMutation({create: false, report: false});
    this.searchDisabledMutation(true);
    this.loadingFishMutation(true);
    await this.getUsuarios();
    this.loadingFishMutation(false);
  },
  beforeMount(){
    this.breadcrumbMutation('Recursos Humanos \\ Usuarios');
    this.searchPlaceholderMutation('Nombre del usuario...');
  }
}
</script>

<style lang="scss" scoped>
.usuarios {
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