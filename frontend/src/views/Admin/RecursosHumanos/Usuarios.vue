<template>
  <LoadingFish v-if="loadingFish" />

  <v-container v-else grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 v-if="messageUsuarios">
        <ErrorMessage :errorMessage="messageUsuarios" :refresh="refreshUsuarios" />
      </v-flex>

      <template v-else>
        <v-flex xs12 class="d-flex align-center justify-space-between">
          <h1 class="display-1">{{ title }}</h1>
          <div>
            <v-btn class="blue" dark fab small @click="refreshUsuarios"><v-icon>replay</v-icon></v-btn>
          </div>
        </v-flex>
        <v-flex xs4 v-for="(usuario, i) of usuarios" :key="usuario.id" class="mb-4">
          <div class="usuarios-box">
            <div class="usuarios-content text-center">
              <h2 class="usuarios-title">{{ usuario.rol }}:</h2>
              <p class="mb-0 usuarios-apellido">{{ usuario.apellidos }}</p>
              <p class="mb-0 usuarios-nombre">{{ usuario.nombres }}</p>
            </div>
            <div class="usuarios-textBox text-center">
              <p class="mb-0 usuarios-text" v-show="usuario.condicion">Activo</p> 
              <p class="mb-0 usuarios-text" v-show="!usuario.condicion">Inactivo</p>
            </div>
            <img src="@/assets/iconos/edit.svg" alt="edit" class="usuarios-edit" @click="editUsuarioModal(i)">
            <img src="@/assets/iconos/detail.svg" alt="detail" class="usuarios-detail" @click="detailUsuariolModal(i)">
            <PersonalBox class="usuarios-bg" :active="usuario.condicion" :activeInteraction="activarModal" :index="i" />
            <div class="usuarios-color" :style="{'background-color': usuario.color}"></div>
          </div>
        </v-flex>
      </template>
    </v-layout>

     <v-dialog v-model="createModalState" persistent max-width="600px">
      <v-card>
        <v-card-title class="pb-0">
          <v-spacer></v-spacer>
          <h3 class="headline title-modal">Datos del Usuario</h3>
          <v-spacer></v-spacer>
          <v-btn icon color="blue" @click="closeModal">
            <v-icon>close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <v-form
            ref="form"
            lazy-validation
            v-model="valid"
            @submit.prevent="formAction"
            autocomplete="off"
          >
            <v-container grid-list-lg>
              <v-layout row wrap justify-center>
                <v-flex xs12  v-show="create">
                  <v-autocomplete
                    v-model="personal"
                    :items="items"
                    :loading="isLoadingPersonal"
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
                  <p><strong>Email</strong></p>
                </v-flex>
                <v-flex xs9>
                  <p>{{ email }}</p>
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
                  <h3 v-show="create" class="headline title-modal text-center">Registrar Usuario</h3>
                  <h3 v-show="!create" class="headline title-modal text-center">Editar Usuario</h3>
                </v-flex>
                <v-flex xs8>
                  <v-autocomplete
                    v-model="rol"
                    :rules="[rules.required]"
                    :items="allRols"
                    item-text="nombre"
                    item-value="id"
                    hide-no-data
                    hide-selected
                    label="Rol"
                    :loading="isLoadingRols"
                    :disabled="disabled"
                    return-object
                    @focus="getAllRols"
                  ></v-autocomplete>
                </v-flex>
                <v-flex xs8 v-if="create">
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
                  <v-layout justify-center>
                    <v-color-picker hide-inputs v-model="color"></v-color-picker>
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
          <v-btn @click="changePassModal = true" dark color="blue" v-show="!create">Cambiar Password</v-btn>
          <v-spacer></v-spacer>
            <v-btn color="red darken-1" text @click="closeModal" :disabled="disabled">Cerrar</v-btn>
            <v-btn :disabled="!valid" v-show="create" color="green darken-1" text @click="registrarUsuario"  :loading="isLoadBtn">Crear</v-btn>
            <v-btn :disabled="!valid" v-show="!create" color="green darken-1" text @click="editarUsuario"  :loading="isLoadBtn">Editar</v-btn>
        </v-card-actions>
      </v-card>

      <v-dialog
        v-model="changePassModal"
        max-width="350"
      >
        <v-card>
          <v-card-title>
            <v-spacer></v-spacer>
            <h3 class="headline title-modal">Cambiar contraseña</h3>
            <v-spacer></v-spacer>
          </v-card-title>
          <v-card-text class="pb-0">
            <v-form
              ref="pass"
              lazy-validation
              v-model="valid"
              @submit.prevent="changePass"
              autocomplete="off"
            >
              <v-container grid-list-lg>
                <v-layout row wrap justify-center>
                  <v-flex xs12>
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
                </v-layout>
              </v-container>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red darken-1" icon @click="changePassModal = false" :disabled="disabled"><v-icon>close</v-icon></v-btn>
            <v-spacer></v-spacer>
            <v-btn color="green darken-1" icon @click="changePass" :loading="isLoadBtn"><v-icon>done</v-icon></v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-dialog>

    <v-dialog v-model="usuarioDetail" max-width="600px">
      <v-card>
        <v-card-title class="pb-0">
          <v-spacer></v-spacer>
          <h3 class="headline title-modal">Detalles del Usuario</h3>
          <v-spacer></v-spacer>
          <v-btn icon color="blue" @click="closeDetailModal">
            <v-icon>close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text class="pt-0">
            <v-container grid-list-lg>
              <v-layout row wrap>
                <v-flex xs12 v-show="isLoadingPersonal" class="pa-0">
                  <v-progress-linear :indeterminate="true" height="2"></v-progress-linear>
                </v-flex>
                <v-flex xs3>
                  <p><strong>Apellidos</strong></p>
                </v-flex>
                <v-flex xs9>
                  <p>{{ apellidosDetail }}</p>
                </v-flex>
                <v-flex xs3>
                  <p><strong>Nombres</strong></p>
                </v-flex>
                <v-flex xs9>
                  <p>{{ nombresDetail }}</p>
                </v-flex>
                <v-flex xs3>
                  <p><strong>Email</strong></p>
                </v-flex>
                <v-flex xs9>
                  <p>{{ emailDetail }}</p>
                </v-flex>
                <v-flex xs3>
                  <p><strong>N° Documento</strong></p>
                </v-flex>
                <v-flex xs3>
                  <p>{{ n_documentoDetail }}</p>
                </v-flex>
                <v-flex xs3>
                  <p><strong>Celular</strong></p>
                </v-flex>
                <v-flex xs3>
                  <p>{{ celularDetail }}</p>
                </v-flex>
                <v-flex xs3>
                  <p><strong>Rol</strong></p>
                </v-flex>
                <v-flex xs3>
                  <p>{{ rolDetail }}</p>
                </v-flex>
                 <v-flex xs3>
                  <p><strong>Área</strong></p>
                </v-flex>
                <v-flex xs3>
                  <p>{{ areaDetail }}</p>
                </v-flex>
              </v-layout>
            </v-container>
        
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
            <v-btn color="blue" text @click="closeDetailModal">Cerrar</v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="activeDialog"
      max-width="350"
    >
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 class="headline title-modal">Confirmación</h3>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-card-text>¿Realmente deseas <strong>{{ activarText }}</strong> al personal?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" icon @click="activeDialog = false"><v-icon>close</v-icon></v-btn>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" icon @click="activarUsuario(index)"><v-icon>done</v-icon></v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
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
        ></v-pagination>
      </div>
    </template>

    <LoadingDialog />

    <AlertNotifications />

  </v-container>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'

import adminService from '@/services/admin'

import LoadingDialog from '@/components/loading/LoadingDialog'
import LoadingFish from '@/components/loading/LoadingFish'
import PersonalBox from '@/components/box/PersonalBox'
import ErrorMessage from '@/components/messages/ErrorMessage'
import AlertNotifications from '@/components/messages/AlertNotifications'


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
      color: '#000000',
      rol: null,
      allRols: [],
      isLoadingRols: false,
      showPass: false,
      create: true,
      index: 0,
      id: 0,
      changePassModal: false,
      disabled: true,
      isLoadBtn: false,
      //Datos para el personal
      personal: {},
      allPersonal: [],
      allValidPersonal: [],
      isLoadingPersonal: false,
      apellidos: '',
      nombres: '',
      email: '',
      n_documento: '',
      celular: '',
      puesto: '',
      area: '',
      // Datos para el detail
      usuarioDetail: false,
      apellidosDetail: '',
      nombresDetail: '',
      emailDetail: '',
      n_documentoDetail: '',
      celularDetail: '',
      rolDetail: '',
      areaDetail: '',
      // Datos para la paginación
      pagination: 10,
      pageTotal: 0,
      page: 1,
      // Datos para activar/desactivar el modal
      activeDialog: false,
      activarText: '',
    }
  },
  methods: {
    // OBTENER USUARIOS
    async getUsuarios(){
      try {
        if(this.backup.usuarios.length != 0){
          this.searchQueryMutation('')
          return
        }

        this.loadingTitleMutation('Actualizando información')
        this.loadingDialogMutation(true)

        // let response = await axios.get(this.url + 'users', this.config)
        if(this.allUsuariosState.length == 0 || this.refresh){
          await this.allUsuariosAction()
          this.refresh = false
        }
        if(this.allUsuariosState.data){
          let usuarios = this.allUsuariosState.data
          if(usuarios.length > 0){
            this.allUsuarios = usuarios
            this.paginate()
            this.messageUsuarios = ''
            this.searchDisabledMutation(false)
          }else {
            this.messageUsuarios = 'No se encontro ningún usuario'
            this.pageTotal = 0
          }
          this.headerActionsMutation({create: true, report: false})
        }else {
          this.headerActionsMutation({create: false, report: false})
          this.messageUsuarios = response.data.message
          this.usuarios = []
          this.pageTotal = 0
          this.searchDisabledMutation(true)
        }
      } catch (error) {
        this.headerActionsMutation({create: false, report: false})
        this.pageTotal = 0
        this.messageUsuarios = 'Error al conctar con el servidor'
        this.searchDisabledMutation(true)
      }finally {
        this.loadingDialogMutation(false)
      }
    },

    refreshUsuarios(){
      this.refresh = true
      this.getUsuarios()
    },

    // PAGINAR USUARIOS
    paginate(){
      if(this.allUsuarios.length > this.pagination){
        this.usuarios = this.allUsuarios.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page))
        this.pageTotal = Math.ceil(this.allUsuarios.length / this.pagination)
      }else {
         this.usuarios = this.allUsuarios
          this.pageTotal = 0
      }
    },

    // BUSCAR USUARIOS
    searchUsuarios(query){
      if(query != '' && query != null){
        if(this.usuarios.length != 0 && this.backup.usuariosIndex){
          this.backup.usuarios = this.usuarios
          if(this.pageTotal != 0){
            this.backup.pageTotal = this.pageTotal
          }
          this.backup.usuariosIndex = false
        }

        if(this.messageUsuarios.length != 0){
          this.messageUsuarios = ''
        }

        this.usuarios = this.allUsuarios.filter(function(e){
          return e.nombres.toLowerCase().search(query.toLowerCase()) != -1 || e.apellidos.toLowerCase().search(query.toLowerCase()) != -1 
        })

        if(this.usuarios.length == 0){
          this.messageUsuarios = 'No se encontro usuario con el nombre ' + query
        }
        
        this.pageTotal = 0
      }else {
        if(this.backup.usuarios.length != 0){
          this.usuarios = this.backup.usuarios
          this.backup.usuarios = []
          this.messageUsuarios = ''
          if(this.backup.pageTotal != 0){
            this.pageTotal = this.backup.pageTotal
            this.backup.pageTotal = 0
          }
          this.backup.usuariosIndex = true
        }
      }
    },

    // OBTENER TODO EL PERSONAL
    async getAllPersonal(){
      try {

        this.isLoadingPersonal = true

        // let response = await axios.get(this.url + 'empleados', this.config)
        if(this.allPersonalState.length == 0){
          await this.allPersonalAction()
        }
        if(this.allPersonalState.data){
          let personal = this.allPersonalState.data
          if(personal.length > 0){

            this.allPersonal = personal

            this.allValidPersonal = personal.filter(function(e){
              return e.condicion && (e.puesto_trabajo === 'Mozo' ||  e.puesto_trabajo === 'Caja')
            })

            for(let usuario of this.allUsuarios){
              this.allValidPersonal = this.allValidPersonal.filter(function(e){
                return e.id !== usuario.empleado_id
              })
            }
          }else {
            this.allPersonal = []
          }
        }else {
          this.allPersonal = []
        }
      } catch (error) {
        this.allPersonal = []
        this.snackbarMutation({value: true, text: 'Error al obtener el personal', color: 'error'})

      }finally {
        this.isLoadingPersonal = false
      }
    },

    // ASIGNAR PERSONAL
    asignPersonal(){
      this.apellidos = this.personal.apellidos
      this.nombres = this.personal.nombres
      this.email = this.personal.email
      this.n_documento = this.personal.documento
      this.celular = this.personal.celular
      this.puesto = this.personal.puesto_trabajo
      this.area = this.personal.area_trabajo
      this.id = this.personal.id
      this.disabled = false
    },

    // Obtener todos los roles
    async getAllRols(){
      try {

        this.isLoadingRols = true

        if(this.allRolsState.length == 0){
          await this.allRolsAction()
        }
        if(this.allRolsState.data){
          let rols = this.allRolsState.data
          if(rols.length > 0){

            this.allRols = rols

          }else {
            this.allRols = []
          }
        }else {
          this.allRols = []
        }
      } catch (error) {
        this.allRols = []
        this.snackbarMutation({value: true, text: 'Error al obtener los roles', color: 'error'})
      }finally {
        this.isLoadingRols = false
      }
    },

    // ABRIR USUARIO DETAIL MODAL
    async detailUsuariolModal(index){
      let usuario = this.usuarios[index]
      this.isLoadingPersonal = true

      this.usuarioDetail = true

      await this.getAllPersonal()

      let personal = this.allPersonal.filter(function(e){
        return e.id ==  usuario.empleado_id
      })
      personal = personal[0]

      this.apellidosDetail = personal.apellidos
      this.nombresDetail = personal.nombres
      this.emailDetail = personal.email
      this.n_documentoDetail = personal.documento
      this.celularDetail = personal.celular
      this.rolDetail = usuario.rol
      this.areaDetail = personal.area_trabajo
    },

    // CERRAR MODAL DETALE
    closeDetailModal(){
      this.usuarioDetail = false
      let self = this
      setTimeout(function(){
        self.apellidosDetail = ''
        self.nombresDetail = ''
        self.emailDetail = ''
        self.n_documentoDetail = ''
        self.celularDetail = ''
        self.rolDetail = ''
        self.areaDetail = ''
      }, 100)
    },

    // MODAL PARA EDITAR USUARIO
    async editUsuarioModal(index){
      this.create = false
      this.disabled = false

      let usuario = this.usuarios[index]
      
      this.color = usuario.color
      this.index = index

      this.createModalMutation(true)

      this.getAllRols()
      
      let rol = {
        id: usuario.rol_id,
        nombre: usuario.rol
      }

      this.rol = rol
      this.disabled = true

      await this.getAllPersonal()

      let personal = this.allPersonal.filter(function(e){
        return e.id ==  usuario.empleado_id
      })
      this.personal = personal[0]
      
      this.asignPersonal()
    },

    // CERRAR MODAL
    closeModal(){
      this.createModalMutation(false)
      setTimeout(this.resetForm, 100)
    },

    // LIMPIAR FORMUALRIO
    resetForm(){
      this.password = '',
      this.color = '#000000',
      this.rol = null,
      this.personal = {}
      this.apellidos = ''
      this.nombres = ''
      this.email = '',
      this.n_documento = ''
      this.celular = ''
      this.puesto = ''
      this.area = ''
      this.disabled = true
      this.create = true
      this.isLoadingPersonal = false
      this.$refs.form.resetValidation()
    },

    // REGISTRAR USUARIO
    async registrarUsuario(){
      try {
        if (this.$refs.form.validate()) {
          this.isLoadBtn = true
          this.disabled = true

          let response = await axios.post(this.url + 'auth/signup', 
            {
              email: this.email,
              color: this.color,
              empleado_id: this.id,
              rol_id: this.rol.id,
              password: this.password
            },
            {
              headers: {
                Apikey: this.config.headers.Apikey,
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
              }
            }
          )

          this.closeModal()

          if(this.pageTotal == 0 && this.backup.pageTotal != 0){
              this.pageTotal = this.backup.pageTotal
          }

          if(response.data){
            this.allUsuarios.reverse()
            this.allUsuarios.push(response.data)
            this.allUsuarios.reverse()
            this.snackbarMutation({value: true, text: 'Usuario creado correctamente', color: 'success'})
            this.paginate()
            this.page = 1
            this.backup.usuarios = []
            this.messageUsuarios = ''
            this.backup.usuariosIndex = true
            let self = this
            this.allValidPersonal = this.allValidPersonal.filter(function(e){
              return e.id !== self.id
            })
          }else{
            this.snackbarMutation({value: true, text: 'Ocurrio un error al registrar al usuario', color: 'error'})
          }
        }
      } catch (error) {
        this.closeModal()
        this.snackbarMutation({value: true, text: 'Ocurrio un error en el servidor', color: 'error'})
      }finally {
        this.isLoadBtn = false
        this.disabled = false
      }
    },

    // EDITAR USUARIO
    async editarUsuario(){
      try {
        if (this.$refs.form.validate()) {

          let colorBup = this.color
          let rolNombreBup = this.rol.nombre
          let rolIdBup = this.rol.id

          this.closeModal()

          this.usuarios[this.index].color = colorBup
          this.usuarios[this.index].rol = rolNombreBup
          this.usuarios[this.index].rol_id = rolIdBup


          let response = await axios.put(this.url + 'user/actualizar/' + this.usuarios[this.index].id, {
            color: colorBup,
            rol_id: rolIdBup
          }, this.config)
          this.snackbarMutation({value: true, text: 'Usuario editado correctamente', color: 'success'})
        }
      }catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un error al editar el usuario', color: 'error'})
      }
    },

    // Cambiar contraseña
    async changePass() {
      try {
        if (this.$refs.pass.validate()) {
          this.isLoadBtn = true
          this.disabled = true

          let response = await axios.put(this.url + 'user/updatePass/' + this.usuarios[this.index].id, {
            password: this.password
          }, this.config)

          this.closeModal()

          if(response.data){
            this.snackbarMutation({value: true, text: 'Contraseña cambiada correctamente', color: 'success'})
          }else{
            this.snackbarMutation({value: true, text: 'Ocurrio un error al cambiar la contraseña', color: 'error'})
          }
        }
      } catch (error) {
        this.closeModal()
        this.snackbarMutation({value: true, text: 'Ocurrio un error en el servidor', color: 'error'})
      }finally {
        this.isLoadBtn = false
        this.disabled = false
      }
    },

    // ACTIVAR MODAL / INACTIVE
    activarModal(index){
      this.index = index
      if(this.usuarios[index].condicion){
        this.activarText = 'desactivar'
      }else{
        this.activarText = 'activar'
      }
      this.activeDialog = true
    },

    //  ACTIVAR 
    async activarUsuario(index){
      try {
        this.id = this.usuarios[index].id
        this.activeDialog = false
        var self = this
        if(this.usuarios[index].condicion){
          this.usuarios[index].condicion = 0
          let response = await axios.put(this.url + 'user/desactivar/' + this.id, {},this.config)
          this.snackbarMutation({value: true, text: 'Personal desactivado correctamente', color: 'success'})
        }else { 
          this.usuarios[index].condicion = 1
          let response = await axios.put(this.url + 'user/activar/' + this.id, {}, this.config)
          this.snackbarMutation({value: true, text: 'Personal activado correctamente', color: 'success'})
        }
      } catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un error', color: 'error'})
      }
    },

    // ACCION DEL FORMULARIO
    async formAction(){
      if(this.create){
        await this.registrarUsuario()
      }else {
        await this.editarUsuario()
      }
    },

    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation', 'searchQueryMutation', 'searchPlaceholderMutation', 'searchDisabledMutation']),
    ...mapActions(['allUsuariosAction', 'allPersonalAction', 'allRolsAction'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'createModalState', 'searchQuery', 'allUsuariosState', 'allPersonalState', 'allRolsState']),
    items(){
      return this.allValidPersonal.map(entry => {
        const fullName = `${entry.apellidos} ${entry.nombres}`

        return Object.assign({}, entry, { fullName })
      })
    }
  },
    watch: {
    searchQuery(){
      this.searchUsuarios(this.searchQuery)
    },
    changePassModal() {
      if(this.changePassModal == false){
        this.$refs.pass.resetValidation()
        this.valid = true
      }
    }
  },
  async created(){
    this.headerActionsMutation({create: false, report: false})
    this.searchDisabledMutation(true)
    this.loadingFishMutation(true)
    await this.getUsuarios()
    this.loadingFishMutation(false)
  },
  beforeMount(){
    this.breadcrumbMutation('Recursos Humanos \\ Usuarios')
    this.searchPlaceholderMutation('Nombre del usuario...')
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
  &-color {
    position: absolute;
    top: 5%;
    right: 2.5%;
    width: 25px;
    height: 25px;
    border-radius: 50%;
  }
}
.title-modal {
  background-image: $primary-gradient;
  background-clip: text;
  -webkit-text-fill-color: transparent;
}
// Borrar
.color-picker {
  height: 20px;
}
</style>