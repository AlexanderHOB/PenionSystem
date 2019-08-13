<template>
  <LoadingFish v-if="loadingFish" />

  <v-container v-else grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 v-if="messageUsuarios">
        <ErrorMessage :errorMessage="messageUsuarios" :refresh="refreshUsuarios" />
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
              <h2 class="usuarios-title">{{ usuario.rol }}:</h2>
              <p class="mb-0 usuarios-apellido">{{ usuario.apellidos }}</p>
              <p class="mb-0 usuarios-nombre">{{ usuario.nombres }}</p>
            </div>
            <div class="usuarios-textBox text-xs-center">
              <p class="mb-0 usuarios-text" v-show="usuario.condicion">Activo</p> 
              <p class="mb-0 usuarios-text" v-show="!usuario.condicion">Inactivo</p>
            </div>
            <img src="../../assets/iconos/edit.svg" alt="edit" class="usuarios-edit" @click="editUsuarioModal(i)">
            <img src="../../assets/iconos/detail.svg" alt="detail" class="usuarios-detail" @click="detailUsuariolModal(i)">
            <PersonalBox class="usuarios-bg" :active="usuario.condicion" :activeInteraction="activarModal" :index="i" />
          </div>
        </v-flex>
      </template>
    </v-layout>

     <v-dialog v-model="createModalState" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 class="headline title-modal">Datos del Usuario</h3>
          <v-spacer></v-spacer>
          <v-btn icon color="blue--text" @click="closeModal">
            <v-icon>close</v-icon>
          </v-btn>
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
                <v-flex xs12  v-show="create">
                  <v-autocomplete
                    v-model="personal"
                    :items="items"
                    :loading="isLoadingPersonal"
                    color="blue"
                    hide-no-data
                    hide-selected
                    item-text="fullName"
                    item-value="id"
                    label="Personal"
                    prepend-icon="search"
                    return-object
                    @focus="getAllPersonal"
                    @input="asignPersonal"
                  ></v-autocomplete>
                </v-flex>
                <v-flex xs12 v-show="!create && isLoadingPersonal" class="pa-0">
                  <v-progress-linear :indeterminate="true" height="2"></v-progress-linear>
                </v-flex>
                <v-flex xs3>
                  <p><strong>Apellidos</strong></p>
                </v-flex>
                <v-flex xs9>
                  <p>{{ apellidos }}</p>
                </v-flex>
                <v-flex xs3>
                  <p><strong>Nombres</strong></p>
                </v-flex>
                <v-flex xs9>
                  <p>{{ nombres }}</p>
                </v-flex>
                <v-flex xs3>
                  <p><strong>N° Documento</strong></p>
                </v-flex>
                <v-flex xs3>
                  <p>{{ n_documento }}</p>
                </v-flex>
                <v-flex xs3>
                  <p><strong>Celular</strong></p>
                </v-flex>
                <v-flex xs3>
                  <p>{{ celular }}</p>
                </v-flex>
                <v-flex xs3>
                  <p><strong>Puesto</strong></p>
                </v-flex>
                <v-flex xs3>
                  <p>{{ puesto }}</p>
                </v-flex>
                <v-flex xs3>
                  <p><strong>Área</strong></p>
                </v-flex>
                <v-flex xs3>
                  <p>{{ area }}</p>
                </v-flex>
                <v-flex xs12>
                  <h3 v-show="create" class="headline title-modal text-xs-center">Registrar Usuario</h3>
                  <h3 v-show="!create" class="headline title-modal text-xs-center">Editar Usuario</h3>
                </v-flex>
                <v-flex xs6>
                  <v-select
                    color="blue"
                    v-model="rol"
                    :rules="[rules.required]"
                    :items="allRols"
                    item-text="nombre"
                    item-value="id"
                    label="Rol"
                    :disabled="disabled"
                    return-object
                  ></v-select>
                </v-flex>
                <v-flex xs6>
                  <v-text-field
                    v-model="password"
                    :append-icon="showPass ? 'visibility' : 'visibility_off'"
                    :rules="[rules.required, rules.counterPass, rules.minPass]"
                    :type="showPass ? 'text' : 'password'"
                    label="Password"
                    counter="20"
                    @click:append="showPass = !showPass"
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12>
                  <v-layout wrap justify-space-around>
                    <v-flex xs2 class="color-picker red" @click="color = '#F44336'"></v-flex>
                    <v-flex xs2 class="color-picker blue" @click="color = '#2196F3'"></v-flex>
                    <v-flex xs2 class="color-picker green" @click="color = '#4CAF50'"></v-flex>
                    <v-flex xs2 class="color-picker orange" @click="color = '#FF9800'"></v-flex>
                    <v-flex xs2 class="color-picker purple" @click="color = '#9C27B0'"></v-flex>
                    <v-flex xs6 class="color-picker mt-4" :style="{'background-color': color}"></v-flex>
                  </v-layout>
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
            <v-btn :disabled="!valid" v-show="create" color="green darken-1" flat @click="registrarUsuario"  :loading="isLoadBtn">Crear</v-btn>
            <v-btn :disabled="!valid" v-show="!create" color="green darken-1" flat @click="editarUsuario"  :loading="isLoadBtn">Editar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    
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
      // Datos para valdiar formulario
      valid: true,
      rules: {
        required: value => !!value || 'Required.',
        counterPass: value => value.length <= 20 || '20 carácteres como máximo',
        minPass: value => value.length >= 8 || '8 Carácteres como mínimo',
      },
      // Data para editar el usuario
      password: '',
      color: '#000',
      rol: {},
      allRols: [
        {id: 4, nombre: 'Mozo'},
        {id: 3, nombre: 'Cajero'},
        {id: 1, nombre: 'Administrador'},
      ],
      showPass: false,
      create: true,
      index: 0,
      id: 0,
      disabled: true,
      isLoadBtn: false,
      //Datos para el personal
      personal: {},
      allPersonal: [],
      isLoadingPersonal: false,
      apellidos: '',
      nombres: '',
      n_documento: '',
      celular: '',
      puesto: '',
      area: '',
      // Datos para la paginación
      pagination: 5,
      pageTotal: 0,
      page: 1
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

    // OBTENER TODO EL PERSONAL
    async getAllPersonal(){
      try {
        if(this.allPersonal.length != 0){
          return;
        }

        this.isLoadingPersonal = true;

        // let response = await axios.get(this.url + 'empleados', this.config);
        if(this.allPersonalState.length == 0){
          await this.allPersonalAction();
        }
        if(this.allPersonalState.data){
          let personal = this.allPersonalState.data;
          let self = this;
          if(personal.length > 0){
            this.allPersonal = personal.filter(function(e){
              if(self.allUsuarios.length === 0){
                return e.condicion && (e.puesto_trabajo === 'Mozo' ||  e.puesto_trabajo === 'Caja')
              }else {
                for(let usuario of self.allUsuarios){
                return e.condicion && e.id != usuario.id && (e.puesto_trabajo === 'Mozo' ||  e.puesto_trabajo === 'Caja')
                }
              }
            });
          }else {
            this.allPersonal = [];
          }
        }else {
          this.allPersonal = [];
        }
      } catch (error) {
        this.allPersonal = [];
        this.snackbarMutation({value: true, text: 'Error al obtener el personal', color: 'error'});

      }finally {
        this.isLoadingPersonal = false;
      }
    },

    // ASIGNAR PERSONAL
    asignPersonal(){
      this.apellidos = this.personal.apellidos;
      this.nombres = this.personal.nombres;
      this.n_documento = this.personal.documento;
      this.celular = this.personal.celular;
      this.puesto = this.personal.puesto_trabajo;
      this.area = this.personal.area_trabajo;
      this.id = this.personal.id;
      this.disabled = false;
    },

    // ABRIR USUARIO DETAIL MODAL
    detailUsuariolModal(){

    },

    // MODAL PARA EDITAR ADELANTO
    async editUsuarioModal(id){
      this.create = false;
      let adelanto = this.adelantos.filter(function(e){
        return e.id == id;
      });
      adelanto = adelanto[0];
      this.fechaAdelanto = adelanto.fecha_transaccion.split('-').reverse().join('-');
      this.monto = adelanto.monto;
      this.motivo = adelanto.motivo;
      this.disabled = false;

      this.isLoadingPersonal = true;
      var personal = [];

      this.createModalMutation(true);

      await this.getAllPersonal();

      personal = this.allPersonal.filter(function(e){
        return e.id ==  adelanto.persona_id;
      });
      this.personal = personal[0];

      this.asignPersonal();
      this.disabledDate = true;
    },

    // CERRAR MODAL
    closeModal(){
      this.createModalMutation(false);
      setTimeout(this.resetForm, 100);
    },

    // LIMPIAR FORMUALRIO
    resetForm(){
      this.password = '',
      this.color = '#000',
      this.rol = {},
      this.personal = {};
      this.apellidos = '';
      this.nombres = '';
      this.n_documento = '';
      this.celular = '';
      this.puesto = '';
      this.area = '';
      this.disabled = true;
      this.create = true;
      this.isLoadingPersonal = false;
      this.$refs.form.resetValidation();
    },

    // REGISTRAR USUARIO
    async registrarUsuario(){
      try {
        if (this.$refs.form.validate()) {
          this.isLoadBtn = true;
          this.disabled = true;
          let response = await axios.post(this.url + 'auth/signup', {
            id: this.id,
            email: this.personal.email,
            color: this.color,
            empleado_id: this.personal.id,
            rol_id: this.rol.id,
            password: this.password
          },
          {
            headers: {
              Apikey: this.config.headers.Apikey,
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            }
          });

          this.closeModal();

          if(this.pageTotal == 0 && this.backup.pageTotal != 0){
              this.pageTotal = this.backup.pageTotal;
          }

          if(response.data){
            let usuario = {
              apellidos: response.data.empleado.persona.apellidos,
              celular:  response.data.empleado.persona.celular,
              condicion: 1,
              direccion: response.data.empleado.persona.direccion,
              documento: response.data.empleado.persona.documento,
              email: response.data.empleado.persona.email,
              id: response.data.empleado_id,
              nombres: response.data.empleado.persona.nombres,
              rol: response.data.rol.nombre,
              rol_id: response.data.rol_id
            }
            this.allUsuarios.reverse();
            this.allUsuarios.push(usuario);
            this.allUsuarios.reverse();
            this.snackbarMutation({value: true, text: 'Personal creado correctamente', color: 'success'});
            this.paginate();
            this.page = 1;
            this.backup.usuarios = [];
            this.messageUsuarios = '';
            this.backup.usuariosIndex = true;
          }else{
            this.snackbarMutation({value: true, text: 'Ocurrio un error al registrar al personal', color: 'error'});
          }
        }
      } catch (error) {
        this.closeModal();
        this.snackbarMutation({value: true, text: 'Ocurrio un error en el servidor', color: 'error'});
      }finally {
        this.isLoadBtn = false;
        this.disabled = false;
        this.disabledDate = false;
      }
    },

    // EDITAR USUARIO
    async editarUsuario(){

    },

    // ACTIVAR MODAL / INACTIVE
    activarModal(){

    },

    // ACCION DEL FORMULARIO
    async formAction(){
      if(this.create){
        await this.registrarAdelanto();
      }else {
        await this.editarAdelanto();
      }
      this.$refs.form.resetValidation();  
    },

    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation', 'searchQueryMutation', 'searchPlaceholderMutation', 'searchDisabledMutation']),
    ...mapActions(['allUsuariosAction', 'allPersonalAction', 'allRolsAction'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'createModalState', 'searchQuery', 'allUsuariosState', 'allPersonalState', 'allRolsState']),
    items(){
      return this.allPersonal.map(entry => {
        const fullName = `${entry.apellidos} ${entry.nombres}`

        return Object.assign({}, entry, { fullName })
      })
    }
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
// Borrar
.color-picker {
  height: 20px;
}
</style>