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
            <v-btn class="blue" dark fab small @click="refreshPersonal(page, 'Actualizando información')"><v-icon>replay</v-icon></v-btn>
          </div>
        </v-flex>
        <v-flex xs4 v-for="(empleado, i) of personal" :key="empleado.id" class="mb-4">
          <div class="personal-box">
            <div class="personal-content text-xs-center">
              <h2 class="personal-title">{{ empleado.puesto_trabajo }}:</h2>
              <p class="mb-0 personal-apellido">{{ empleado.apellidos }}</p>
              <p class="mb-0 personal-nombre">{{ empleado.nombres }}</p>
            </div>
            <div class="personal-textBox text-xs-center">
              <p class="mb-0 personal-text" v-show="empleado.condicion">Activo</p> 
              <p class="mb-0 personal-text" v-show="!empleado.condicion">Inactivo</p>
            </div>
            <img src="../assets/iconos/edit.svg" alt="edit" class="personal-edit" @click="editarPersonalModal(i)">
            <img src="../assets/iconos/detail.svg" alt="detail" class="personal-detail" @click="detailPersonalModal(i)">
            <PersonalBox class="personal-bg" :active="empleado.condicion" :activeInteraction="activarPersonal" :index="i" />
          </div>
        </v-flex>
      </template>
    </v-layout>

    <v-dialog v-model="createModalState" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 v-show="create" class="headline title-modal">Registrar Personal</h3>
          <h3 v-show="!create" class="headline title-modal">Editar Personal</h3>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-card-text class="pt-0">
          <v-form
            ref="form"
            lazy-validation
            v-model="valid"
            @submit.prevent="formAction"
          >
            <v-container>
              <v-layout row wrap>
                <v-flex xs6>
                  <v-text-field 
                    color="blue"
                    label="Apellidos"
                    v-model="apellidos"
                    :rules="[rules.required]"
                    counter
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-text-field 
                    color="blue"
                    label="Nombre"
                    v-model="nombre"
                    :rules="[rules.required]"
                    counter
                  ></v-text-field>
                </v-flex>
                <v-flex xs5>
                  <v-text-field 
                    color="blue"
                    label="Dirección"
                    v-model="direccion"
                    :rules="[rules.required]"
                    counter
                  ></v-text-field>
                </v-flex>
                <v-flex xs7>
                  <v-text-field 
                    color="blue"
                    label="Email"
                    v-model="email"
                    :rules="[rules.required]"
                    counter
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-text-field 
                    color="blue"
                    label="DNI"
                    v-model="dni"
                    :rules="[rules.required]"
                    counter
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-text-field 
                    color="blue"
                    label="Celular"
                    v-model="celular"
                    :rules="[rules.required]"
                    counter
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-text-field 
                    color="blue"
                    label="Puesto"
                    v-model="puesto"
                    :rules="[rules.required]"
                    counter
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-text-field 
                    color="blue"
                    label="Area"
                    v-model="area"
                    :rules="[rules.required]"
                    counter
                  ></v-text-field>
                </v-flex>
                <v-flex xs4>
                  <v-text-field 
                    color="blue"
                    label="Tipo de contrato"
                    v-model="tipo_contrato"
                    :rules="[rules.required]"
                    counter
                  ></v-text-field>
                </v-flex>
                <v-flex xs4>
                  <v-text-field 
                    color="blue"
                    label="Fecha de inicio"
                    v-model="fechaInicio"
                    :rules="[rules.required]"
                    counter
                  ></v-text-field>
                </v-flex>
                <v-flex xs4>
                  <v-text-field 
                    color="blue"
                    label="Sueldo"
                    v-model="sueldo"
                    :rules="[rules.required]"
                    counter
                  ></v-text-field>
                </v-flex>
                <v-flex x12 class="d-none">
                  <v-btn type="submit"></v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
            <v-btn color="red darken-1" flat @click="closeModal">Cerrar</v-btn>
            <v-btn :disabled="!valid" v-show="create" color="green darken-1" flat @click="registrarPersonal">Crear</v-btn>
            <v-btn :disabled="!valid" v-show="!create" color="green darken-1" flat @click="editarPersonal">Editar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="personalDetail" max-width="600px">
      <v-container class="white" grid-list-md>
        <v-layout row wrap>
          <v-flex xs12>
            <h3 class="title-modal headline text-xs-center pb-3">Detalle del empleado</h3>
          </v-flex>
          <v-flex xs12>
            <v-layout row wrap>
              <v-flex xs3><strong>Apellidos</strong></v-flex>
              <v-flex xs9>
                <p>{{ apellidosDetail }}</p>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs12>
              <v-layout>
              <v-flex xs3><strong>Nombre</strong></v-flex>
              <v-flex xs9>
                <p>{{ nombreDetail }}</p>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs12>
            <v-layout>
              <v-flex xs3><strong>Email</strong></v-flex>
              <v-flex xs9>
                <p>{{ emailDetail }}</p>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs6>
            <v-layout>
              <v-flex xs3><strong>DNI</strong></v-flex>
              <v-flex xs9>
                <p>{{ dniDetail }}</p>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs6>
            <v-layout>
              <v-flex xs3><strong>Celular</strong></v-flex>
              <v-flex xs9>
                <p>{{ celularDetail }}</p>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs6>
            <v-layout>
              <v-flex xs3><strong>Puesto</strong></v-flex>
              <v-flex xs9>
                <p>{{ puestoDetail }}</p>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs6>
            <v-layout>
              <v-flex xs3><strong>Area</strong></v-flex>
              <v-flex xs9>
                <p>{{ areaDetail }}</p>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs6>
            <v-layout>
              <v-flex xs5><strong>Fecha de inicio</strong></v-flex>
              <v-flex xs7>
                <p>{{ fechaInicioDetail }}</p>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs6>
            <v-layout>
              <v-flex xs3><strong>Sueldo</strong></v-flex>
              <v-flex xs9>
                <p>{{ sueldoDetail }}</p>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs12 class="py-0 text-xs-right">
            <v-btn color="blue darken-1" flat @click="closeDetailModal">Cerrar</v-btn>
          </v-flex>
        </v-layout>
      </v-container>
    </v-dialog>

    <template v-if="pageTotal">
      <div class="text-xs-center mt-4">
        <v-pagination
          v-model="page"
          :length="pageTotal"
          color="blue"
          circle
          :disabled="paginateDisabled"
          @input="refreshPersonal"
          @next="refreshPersonal"
          @previous="refreshPersonal"
        ></v-pagination>
      </div>
    </template>

    <LoadingDialog />

    <AlertNotifications />

  </v-container>
</template>

<script>
import axios from 'axios';
import LoadingDialog from '../components/loading/LoadingDialog';
import LoadingFish from '../components/loading/LoadingFish';
import PersonalBox from '../components/box/PersonalBox';
import ErrorMessage from '../components/messages/ErrorMessage';
import AlertNotifications from '../components/messages/AlertNotifications';

import { mapState, mapMutations } from 'vuex';
import { setTimeout } from 'timers';
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
      title: 'Personal',
      //Datos para las categorias
      messagePersonal: '',
      personal: [],
      personalTotal: 0,
      // Datos para la paginación
      pageTotal: 0,
      page: 1,
      paginateDisabled: false,
      // Datos para valdiar formulario
      valid: true,
      rules: {
        required: value => !!value || 'Required.'
      },
      // Data para editar Categoria
      apellidos: '',
      nombre: '',
      direccion: '',
      email: '',
      dni: '',
      celular: '',
      puesto: '',
      area: '',
      tipo_contrato: '',
      fechaInicio: '',
      sueldo: '',
      create: true,
      index: 0,
      id: 0,
      // Detail
      apellidosDetail: '',
      nombreDetail: '',
      emailDetail: '',
      dniDetail: '',
      celularDetail: '',
      puestoDetail: '',
      areaDetail: '',
      fechaInicioDetail: '',
      sueldoDetail: '',
      // Datos para detalles de la categoria
      personalDetail: false
    }
  },
  methods: {
    // OBTENER CATEOGRIAS
    async getPersonal(){
      try {
        this.loadingTitleMutation('Accediendo a la información');
        this.loadingDialogMutation(true);

        let response = await axios.get(this.url + 'empleado', this.config);

        if(response.data.data){
          let data = response.data;
          let personal = data.data;
          if(personal.length > 0){
            this.personal = personal;
            this.messagePersonal = '';
            this.personalTotal = data.total;
            console.log(personal);
            if(data.last_page && data.last_page > 1){
              this.pageTotal = data.last_page;
            }
            this.page = 1;
          }else {
            this.messagePersonal = 'No se encontro ningun personal';
            this.pageTotal = 0;
          }
          this.headerActionsMutation({create: true, report: false});
        }else {
          this.headerActionsMutation({create: false, report: false});
          this.messagePersonal = response.data.message;
          this.personal = [];
          this.pageTotal = 0;
        }
      } catch (error) {
        this.headerActionsMutation({create: false, report: false});
        this.messageCategorias = 'Error al conctar con el servidor';
      }finally {
        this.loadingDialogMutation(false);
      }
    },

    // ACTUALIZAR CATEGORIAS
    async refreshPersonal(page = this.page, loadingTitle ='Accediendo a la información', create = false){
      try {
        this.paginateDisabled = true;
        this.loadingTitleMutation(loadingTitle);
        if(!create){
          this.loadingDialogMutation(true);
        }

        let response = await axios.get(this.url + 'empleado', {
          params: {
            page: this.page
          },
          headers: {
            Authorization: this.config.headers.Authorization,
            'Content-Type': 'application/json'
          }
        });

        if(response.data.data){
          let data = response.data;
          let personal = data.data;
          if(personal.length > 0){
            this.personal = personal;
            this.messagePersonal = '';
            if(data.total != this.personalTotal){
              this.personalTotal = data.total;
            }
            if(data.last_page && data.last_page > 1){
                if(data.last_page != this.pageTotal){
                  this.pageTotal = data.last_page;
                }
            }
          }else {
            this.messagePersonal = 'No se encontro ningun personal';
            this.pageTotal = 0;
          }
          this.headerActionsMutation({create: true, report: false});
        }else {
          this.headerActionsMutation({create: false, report: false});
          this.messagePersonal = response.data.message;
          this.personal = [];
          this.pageTotal = 0;
        }

      } catch (error) {
        this.headerActionsMutation({create: false, report: false});
        this.pageTotal = 0;
        this.messagePersonal = 'Error al conectar con el servidor';
      }finally {
        this.loadingDialogMutation(false);
        this.paginateDisabled = false;
      }
    },

    // MODAL DE DETALLE
    detailPersonalModal(index){
      this.apellidosDetail = this.personal[index].apellidos;
      this.nombreDetail = this.personal[index].nombres;
      this.emailDetail = this.personal[index].email;
      this.dniDetail = this.personal[index].dni;
      this.celularDetail = this.personal[index].celular;
      this.puestoDetail = this.personal[index].puesto_trabajo;
      this.areaDetail = this.personal[index].area_trabajo;
      this.fechaInicioDetail = '';
      this.sueldoDetail = this.personal[index].sueldo;
      this.personalDetail = true;
    },

    // CERRAR MODAL DE DETALLE
    closeDetailModal(){
      this.personalDetail = false;
      let self = this;
      setTimeout(function(){
        self.apellidosDetail = '';
        self.nombreDetail = '';
        self.emailDetail = '';
        self.dniDetail = '';
        self.celularDetail = '';
        self.puestoDetail = '';
        self.areaDetail = '';
        self.fechaInicioDetail = '';
        self.sueldoDetail = '';
      }, 100);
    },

    // MODAL PARA EDITAR CATEGORIA
    editarPersonalModal(index){
    
    },

    // CERRAR MODAL
    closeModal(){
      this.createModalMutation(false);
    },

    // LIMPIAR FORMUALRIO
    resetForm(){
    
    },

    // CREAR PERSONAL
    async registrarPersonal() {

    },

    // EDITAR CATEGORIA
    async editarPersonal(){

    },

    //  ACTIVAR 
    async activarPersonal(index){
      try {
        this.index = index;
        this.id = this.personal[index].id;
        if(this.personal[index].condicion){
          this.personal[index].condicion = 0;
          let response = await axios.put(this.url + 'empleado/desactivar/' + this.id, {},this.config);
          this.snackbarMutation({value: true, text: 'Personal desactivado correctamente', color: 'success'});
        }else { 
          this.personal[index].condicion = 1;
          let response = await axios.put(this.url + 'empleado/activar/' + this.id, {}, this.config);
          this.snackbarMutation({value: true, text: 'Personal activado correctamente', color: 'success'});
        }
      } catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un error', color: 'error'});
      }
    },

    // ACCION DEL FORMULARIO
    async formAction(){
    
    },

    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'createModalState'])
  },
  created(){
    this.headerActionsMutation({create: false, report: false});
    this.loadingFishMutation(true);
  },
  async mounted(){
    this.breadcrumbMutation('Recursos Humanos \\ Personal');
    await this.getPersonal();
    this.loadingFishMutation(false);
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
  &-edit {
    left: 50%;
  }
  &-detail {
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
