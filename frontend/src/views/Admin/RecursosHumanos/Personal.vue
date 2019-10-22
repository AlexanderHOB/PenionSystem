<template>

  <LoadingFish v-if="loadingFish" />

  <v-container v-else grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 v-if="messagePersonal">
        <ErrorMessage :errorMessage="messagePersonal" :refresh="refreshPersonal" />
      </v-flex>

      <template v-else>
        <v-flex xs12 class="d-flex align-center justify-space-between">
          <h1 class="display-1">{{ title }}</h1>
          <div
            <v-btn class="blue" dark fab small @click="refreshPersonal"><v-icon>replay</v-icon></v-btn>
          </div>
        </v-flex>
        <v-flex xs4 v-for="(empleado, i) of personal" :key="empleado.id" class="mb-4">
          <div class="personal-box">
            <div class="personal-content text-center">
              <h2 class="personal-title">{{ empleado.puesto_trabajo }}:</h2>
              <p class="mb-0 personal-apellido">{{ empleado.apellidos }}</p>
              <p class="mb-0 personal-nombre">{{ empleado.nombres }}</p>
            </div>
            <div class="personal-textBox text-center">
              <p class="mb-0 personal-text" v-show="empleado.condicion">Activo</p> 
              <p class="mb-0 personal-text" v-show="!empleado.condicion">Inactivo</p>
            </div>
            <img src="@/assets/iconos/edit.svg" alt="edit" class="personal-edit" @click="editarPersonalModal(i)">
            <img src="@/assets/iconos/detail.svg" alt="detail" class="personal-detail" @click="detailPersonalModal(i)">
            <PersonalBox class="personal-bg" :active="empleado.condicion" :activeInteraction="activarModal" :index="i" />
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
              <v-layout row wrap>
                <v-flex xs6>
                  <v-text-field 
                    label="Apellidos"
                    v-model="apellidos"
                    :rules="[rules.required, rules.counterApellidos]"
                    counter="50"
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-text-field 
                    label="Nombre"
                    v-model="nombre"
                    :rules="[rules.required, rules.counterNombre]"
                    counter="30"
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex>
                <v-flex xs5>
                  <v-text-field 
                    label="Dirección"
                    v-model="direccion"
                    :rules="[rules.required, rules.counterDirección]"
                    counter="100"
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex>
                <v-flex xs7>
                  <v-text-field 
                    label="Email"
                    v-model="email"
                    :rules="[rules.required, rules.counterEmail, rules.emailRules]"
                    counter="60"
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex>
                <v-flex xs4>
                  <v-select
                    v-model="tipo_documento"
                    :items="documentos"
                    label="Tipo de documento"
                    :disabled="disabled"
                  ></v-select>
                </v-flex>
                <v-flex xs4>
                  <v-text-field 
                    label="DNI"
                    v-model="n_documento"
                    :rules="[rules.required, rules.counterDni]"
                    counter="8"
                    v-mask="'########'"
                    v-if="tipo_documento === 'Dni'"
                    :disabled="disabled"
                  ></v-text-field>
                  <v-text-field 
                    label="N° Extranjero"
                    v-model="n_documento"
                    :rules="[rules.required, rules.counterExtranjero]"
                    counter="11"
                    v-mask="'###########'"
                    v-else
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex>
                <v-flex xs4>
                  <v-text-field 
                    label="Celular"
                    v-model="celular"
                    :rules="[rules.required, rules.counterCelular]"
                    counter="9"
                    v-mask="'#########'"
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-select 
                    label="Puesto"
                    v-model="puesto"
                    :items="puestos"
                    :rules="[rules.required]"
                    :disabled="disabled"
                  ></v-select>
                </v-flex>
                <v-flex xs6>
                  <v-select 
                    label="Area"
                    v-model="area"
                    :items="areas"
                    :rules="[rules.required]"
                    :disabled="disabled"
                  ></v-select>
                </v-flex>
                <v-flex xs4>
                  <v-select 
                    label="Tipo de contrato"
                    v-model="tipo_contrato"
                    :items="tipo_contratos"
                    :rules="[rules.required]"
                    :disabled="disabled"
                  ></v-select>
                </v-flex>
                <v-flex xs4>
                  <v-menu
                    v-model="menuDate"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      v-model="fechaRegistro"
                      label="Fecha de inicio"
                      prepend-inner-icon="event"
                      readonly
                      :rules="[rules.required]"
                      v-on="on"
                      :disabled="disabledDate"
                    ></v-text-field>
                  </template>
                  <v-date-picker header-color="blue" color="green lighten-1" :max="maxDate" :min="minDate" locale="es-pe" v-model="date" @input="menuDate = false"></v-date-picker>
                  </v-menu>
                </v-flex>
                <v-flex xs4>
                  <v-text-field 
                    label="Sueldo"
                    v-model="sueldo"
                    :rules="[rules.required]"
                    type="number"
                    prefix="$"
                    :disabled="disabled"
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
            <v-btn color="red darken-1" text @click="closeModal" :disabled="disabled">Cerrar</v-btn>
            <v-btn :disabled="!valid" v-show="create" color="green darken-1" text @click="registrarPersonal" :loading="isLoadBtn">Crear</v-btn>
            <v-btn :disabled="!valid" v-show="!create" color="green darken-1" text @click="editarPersonal" :loading="isLoadBtn">Editar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="personalDetail" max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 class="title-modal headline">Detalle del empleado</h3>
          <v-spacer></v-spacer>
          <v-btn icon color="blue" @click="closeDetailModal">
            <v-icon>close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text class="pb-0">
          <v-container class="white" grid-list-md>
            <v-layout row wrap>
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
              <v-flex xs12>
                <v-layout>
                  <v-flex xs3><strong>Dirección</strong></v-flex>
                  <v-flex xs9>
                    <p>{{ direccionDetail }}</p>
                  </v-flex>
                </v-layout>
              </v-flex>
              <v-flex xs12>
                <v-layout>
                  <v-flex xs3><strong>Tipo de Contrato</strong></v-flex>
                  <v-flex xs9>
                    <p>{{ tipo_contratoDetail }}</p>
                  </v-flex>
                </v-layout>
              </v-flex>
              <v-flex xs6>
                <v-layout>
                  <v-flex xs5><strong>N° Documento</strong></v-flex>
                  <v-flex xs7>
                    <p>{{ n_documentoDetail }}</p>
                  </v-flex>
                </v-layout>
              </v-flex>
              <v-flex xs6>
                <v-layout>
                  <v-flex xs5><strong>Celular</strong></v-flex>
                  <v-flex xs7>
                    <p>{{ celularDetail }}</p>
                  </v-flex>
                </v-layout>
              </v-flex>
              <v-flex xs6>
                <v-layout>
                  <v-flex xs5><strong>Puesto</strong></v-flex>
                  <v-flex xs7>
                    <p>{{ puestoDetail }}</p>
                  </v-flex>
                </v-layout>
              </v-flex>
              <v-flex xs6>
                <v-layout>
                  <v-flex xs5><strong>Area</strong></v-flex>
                  <v-flex xs7>
                    <p>{{ areaDetail }}</p>
                  </v-flex>
                </v-layout>
              </v-flex>
              <v-flex xs6>
                <v-layout>
                  <v-flex xs5><strong>Fecha de inicio</strong></v-flex>
                  <v-flex xs7>
                    <p>{{ fechaRegistroFormatDetail | reverseDate }}</p>
                  </v-flex>
                </v-layout>
              </v-flex>
              <v-flex xs6>
                <v-layout>
                  <v-flex xs5><strong>Sueldo</strong></v-flex>
                  <v-flex xs7>
                    <p>{{ sueldoDetail }}</p>
                  </v-flex>
                </v-layout>
              </v-flex>
              <v-flex xs12>
                <v-card flat>
                  <v-card-title>
                    Transacciones
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="historialSearch"
                      append-icon="search"
                      label="Search"
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-card-title>
                  <v-data-table
                    :headers="headers"
                    :items="historial"
                    :search="historialSearch"
                    :loading="historialLoading"
                    loading-text="Loading..."
                  >
                    <template v-slot:item.fecha_transaccion="{ item }">
                        <span>{{ item.fecha_transaccion | reverseDate }}</span>
                    </template>
                  </v-data-table>
                </v-card>
              </v-flex>
              <v-flex xs12 class="text-right pt-3">
                <v-btn color="blue darken-1" text @click="closeDetailModal">Cerrar</v-btn>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
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
          <v-btn color="green darken-1" icon @click="activarPersonal(index)"><v-icon>done</v-icon></v-btn>
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
import axios from 'axios'
import { mask } from 'vue-the-mask'

import LoadingDialog from '@/components/loading/LoadingDialog'
import LoadingFish from '@/components/loading/LoadingFish'
import PersonalBox from '@/components/box/PersonalBox'
import ErrorMessage from '@/components/messages/ErrorMessage'
import AlertNotifications from '@/components/messages/AlertNotifications'

import { mapState, mapMutations, mapActions } from 'vuex'

export default {
  components: {
    LoadingDialog,
    LoadingFish,
    PersonalBox,
    ErrorMessage,
    AlertNotifications
  },
  directives: {
    mask,
  },
  data(){
    return {
      title: 'Personal',
      //Datos para el personal
      messagePersonal: '',
      personal: [],
      allPersonal: [],
      isLoadBtn: false,
      disabled: false,
      refresh: false,
      // Backup
      backup: {
        personal: [],
        personalIndex: true,
        pageTotal: 0
      },
      // Datos para la paginación
      pageTotal: 0,
      page: 1,
      pagination: 10,
      // Datos para valdiar formulario
      valid: true,
      rules: {
        required: value => !!value || 'Required.',
        counterApellidos: value => value.length <= 50 || '50 caracteres como maximo',
        counterNombre: value => value.length <= 30 || '30 caracteres como maximo',
        counterDirección: value => value.length <= 100 || '100 caracteres como maximo',
        counterEmail: value => value.length <= 60 || '60 caracteres como maximo',
        counterDni: value => value.length == 8 || '8 caracteres como minimo',
        counterExtranjero: value => value.length == 11 || '11 caracteres como minimo',
        counterCelular: value => value.length == 9 || '9 caracteres como minimo',
        emailRules: value => /.+@.+/.test(value) || 'E-mail invalido'
      },
      // Data para editar el personal
      apellidos: '',
      nombre: '',
      direccion: '',
      email: '',
      n_documento: '',
      celular: '',
      puesto: '',
      area: '',
      tipo_documento: 'Dni',
      tipo_contrato: '',
      fechaRegistro: new Date().toISOString().substr(0, 10).split('-').reverse().join('-'),
      date: new Date().toISOString().substr(0, 10),
      sueldo: '',
      menuDate: false,
      maxDate: new Date().toISOString().substr(0, 10),
      minDate: '2010',
      create: true,
      index: 0,
      id: 0,
      // Detail
      apellidosDetail: '',
      nombreDetail: '',
      direccionDetail: '',
      emailDetail: '',
      n_documentoDetail: '',
      celularDetail: '',
      puestoDetail: '',
      areaDetail: '',
      tipo_contratoDetail: '',
      fechaRegistroDetail: '',
      fechaRegistroFormatDetail: '',
      sueldoDetail: '',
      disabledDate: false,
      // Datos para detalles del personal
      personalDetail: false,
      // Datos para Selects
      puestos: ['Caja', 'Mozo', 'Cocinero', 'Ayudante de Cocina', 'Almacen'],
      areas: ['Área de almacen', 'Área Caliente', 'Área Fria','Área mixta', 'Área de Ventas'],
      tipo_contratos: ['planilla','mensual','semanal'],
      documentos: ['Dni', 'Extranjero'],
      // Datos para activar/desactivar el modal
      activeDialog: false,
      activarText: '',
      // Datos para el historial de transacciones
      historialSearch: '',
      historialLoading: false,
      headers: [
        { text: 'Tipo', align: 'center', sortable: false, value: 'tipo' },
        { text: 'Fecha', align: 'center', value: 'fecha_transaccion' },
        { text: 'Monto', align: 'center', sortable: false, value: 'monto' },
        { text: 'Motivo', align: 'center', sortable: false, value: 'motivo' }
      ],
      historial: [],
      montoDescontar: 0
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

        // let response = await axios.get(this.url + 'empleados', this.config);
        if(this.allPersonalState.length == 0 || this.refresh){
          await this.allPersonalAction();
          this.refresh = false;
        }
        if(this.allPersonalState.data){
          let personal = this.allPersonalState.data;
          if(personal.length > 0){
            this.allPersonal = personal;
            this.page = 1;
            this.paginate();
            this.messagePersonal = '';
          }else {
            this.messagePersonal = 'No se encontro ningun personal';
            this.pageTotal = 0;
          }
          this.searchDisabledMutation(false);
          this.headerActionsMutation({create: true, report: false});
        }else {
          this.headerActionsMutation({create: false, report: false});
          this.searchDisabledMutation(true);
          this.messagePersonal = response.data.message;
          this.personal = [];
          this.pageTotal = 0;
        }
      } catch (error) {
        this.headerActionsMutation({create: false, report: false});
        this.searchDisabledMutation(true);
        this.messagePersonal = 'Error al conctar con el servidor';
        this.pageTotal = 0;
      }finally {
        this.loadingDialogMutation(false);
      }
    },

    refreshPersonal(){
      this.refresh = true;
      this.getPersonal();
    },

    // PAGINAR PESONAL
    paginate(){
      if(this.allPersonal.length > this.pagination){
        this.personal = this.allPersonal.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page));
        this.pageTotal = Math.ceil(this.allPersonal.length / this.pagination);
      }else {
         this.personal = this.allPersonal;
          this.pageTotal = 0;
      }
    },

    // SEARCH PERSONAL
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
          this.messagePersonal = 'No se encontro personal con el nombre ' + query;
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

    // MODAL DE DETALLE
    async detailPersonalModal(index){
      try {
        this.historialSearch = '';
        this.historial = [];
        this.montoDescontar = 0;
        //
        this.apellidosDetail = this.personal[index].apellidos;
        this.nombreDetail = this.personal[index].nombres;
        this.direccionDetail = this.personal[index].direccion;
        this.emailDetail = this.personal[index].email;
        this.n_documentoDetail = this.personal[index].documento;
        this.celularDetail = this.personal[index].celular;
        this.puestoDetail = this.personal[index].puesto_trabajo;
        this.areaDetail = this.personal[index].area_trabajo;
        this.tipo_contratoDetail = this.personal[index].tipo_contrato;
        this.fechaRegistroDetail = this.personal[index].fecha_registro;
        this.fechaRegistroFormatDetail = this.personal[index].fecha_registro;
        this.sueldoDetail = this.personal[index].sueldo;
        // 
        this.id = this.personal[index].id;
        this.historialLoading = true;
        this.personalDetail = true;
        let response = await axios.get(this.url + 'historial', {
          params: {
            id: this.id,
            fecha: this.fechaRegistroDetail
          },
          headers: {
            Apikey: this.config.headers.Apikey,
            'Content-Type': 'application/json'
          }
        });
        this.historialLoading = false;
        this.historial = response.data[0][0].transacciones;
        if(typeof response.data[1][0] != 'undefined'){
          this.montoDescontar = response.data[1][0].total;
        }
      } catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un error al traer la información', color: 'error'});
        this.historial = [];
        this.historialLoading = false;
        this.montoDescontar = 0;
      }
    },

    // CERRAR MODAL DE DETALLE
    closeDetailModal(){
      this.personalDetail = false;
      
      let self = this;
      setTimeout(function(){
        self.apellidosDetail = '';
        self.nombreDetail = '';
        self.emailDetail = '';
        self.n_documentoDetail = '';
        self.celularDetail = '';
        self.puestoDetail = '';
        self.areaDetail = '';
        self.fechaRegistroDetail = '';
        self.sueldoDetail = '';
        self.historial = [];
        self.historialSearch = '';
        self.montoDescontar = 0;
      }, 100);
    },

    // MODAL PARA EDITAR PERSONAL
    editarPersonalModal(index){
      this.apellidos = this.personal[index].apellidos;
      this.nombre = this.personal[index].nombres;
      this.direccion = this.personal[index].direccion;
      this.email = this.personal[index].email;
      this.n_documento = this.personal[index].documento;
      this.celular = this.personal[index].celular;
      this.puesto = this.personal[index].puesto_trabajo;
      this.area = this.personal[index].area_trabajo;
      this.tipo_documento = this.personal[index].tipo_documento;
      this.tipo_contrato = this.personal[index].tipo_contrato;
      this.sueldo = this.personal[index].sueldo;
      this.fechaRegistro = this.personal[index].fecha_registro.split('-').reverse().join('-');
      this.index = index;
      this.id = this.personal[index].id;
      this.create = false;
      this.disabledDate = true;
      this.createModalMutation(true);
    },

    // CERRAR MODAL
    closeModal(){
      this.createModalMutation(false);
      setTimeout(this.resetForm, 100);
    },

    // LIMPIAR FORMUALRIO
    resetForm(){
      this.apellidos = '';
      this.nombre = '';
      this.direccion = '';
      this.email = '';
      this.n_documento = '';
      this.celular = '';
      this.puesto = '';
      this.area = '';
      this.tipo_documento = 'Dni';
      this.tipo_contrato = '';
      this.date = new Date().toISOString().substr(0, 10);
      this.fechaRegistro = new Date().toISOString().substr(0, 10).split('-').reverse().join('-');
      this.sueldo = '';
      this.create = true;
      this.disabledDate = false;
      this.$refs.form.resetValidation();
    },

    // CREAR PERSONAL
    async registrarPersonal() {
      try {
        if (this.$refs.form.validate()) {
          this.isLoadBtn = true;
          this.disabled = true;
          this.disabledDate = true;
          this.sueldo = parseFloat(parseFloat(this.sueldo).toFixed(2));
          let response = await axios.post(this.url + 'empleado/registrar', {
            apellidos: this.apellidos,
            nombres: this.nombre,
            documento: this.n_documento,
            tipo_documento: this.tipo_documento,
            celular: this.celular,
            email: this.email,
            direccion: this.direccion,
            area_trabajo: this.area,
            puesto_trabajo: this.puesto,
            tipo_contrato: this.tipo_contrato,
            fecha_registro: this.date,
            sueldo: this.sueldo
          }, this.config);

          this.closeModal();

          if(this.pageTotal == 0 && this.backup.pageTotal != 0){
              this.pageTotal = this.backup.pageTotal;
          }
          if(response.data){
            this.allPersonal.reverse();
            this.allPersonal.push(response.data);
            this.allPersonal.reverse();
            this.snackbarMutation({value: true, text: 'Personal creado correctamente', color: 'success'});
            this.paginate();
            this.page = 1;
            this.backup.personal = [];
            this.messagePersonal = '';
            this.backup.personalIndex = true;
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

    // EDITAR PERSONAL
    async editarPersonal(){
      try {
        if (this.$refs.form.validate()) {

          let apellidosBup = this.apellidos;
          let nombresBup = this.nombre;
          let n_documentoBup = this.n_documento;
          let tipo_documentoBup = this.tipo_documento;
          let celularBup = this.celular;
          let emailBup = this.email;
          let direccionBup = this.direccion;
          let area_trabajoBup = this.area;
          let puesto_trabajoBup = this.puesto;
          let tipo_contratoBup = this.tipo_contrato;
          let sueldoBup =  this.sueldo;

          this.closeModal();

          this.personal[this.index].apellidos = apellidosBup;
          this.personal[this.index].nombres = nombresBup;
          this.personal[this.index].documento = n_documentoBup;
          this.personal[this.index].tipo_documento = tipo_documentoBup;
          this.personal[this.index].celular = celularBup;
          this.personal[this.index].email = emailBup;
          this.personal[this.index].direccion = direccionBup;
          this.personal[this.index].area_trabajo = area_trabajoBup;
          this.personal[this.index].puesto_trabajo = puesto_trabajoBup;
          this.personal[this.index].tipo_contrato = tipo_contratoBup;
          this.personal[this.index].sueldo = sueldoBup;

          let response = await axios.put(this.url + 'empleado/actualizar/' + this.id, {
            apellidos: apellidosBup,
            nombres: nombresBup,
            documento: n_documentoBup,
            tipo_documento: tipo_documentoBup,
            celular: celularBup,
            email: emailBup,
            direccion: direccionBup,
            area_trabajo: area_trabajoBup,
            puesto_trabajo: puesto_trabajoBup,
            tipo_contrato: tipo_contratoBup,
            sueldo: sueldoBup
          }, this.config);
          this.snackbarMutation({value: true, text: 'Personal editado correctamente', color: 'success'});
        }
      }catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un error al editar el personal', color: 'error'});
      }
    },

    // MODAL PARA ACTIVAR
    activarModal(index){
        this.index = index;
        if(this.personal[index].condicion){
          this.activarText = 'desactivar';
        }else{
          this.activarText = 'activar';
        }
        this.activeDialog = true;
    },    

    //  ACTIVAR 
    async activarPersonal(index){
      try {
        this.id = this.personal[index].id;
        this.activeDialog = false;
        var self = this;
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
      if(this.create){
        await this.registrarPersonal();
      }else {
        await this.editarPersonal();
      }
    },

    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation', 'searchQueryMutation', 'searchPlaceholderMutation', 'searchDisabledMutation']),
    ...mapActions(['allPersonalAction'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'createModalState', 'searchQuery', 'allPersonalState'])
  },
  watch: {
    date(val) {
      this.fechaRegistro = val.split('-').reverse().join('-');
    },
    searchQuery(){
      this.searchPersonal(this.searchQuery);
    }
  },
  filters: {
    reverseDate(str) {
      return str.split('-').reverse().join('-')
    }
  },
  async created(){
    this.headerActionsMutation({create: false, report: false});
    this.searchDisabledMutation(true);
    this.loadingFishMutation(true);
    await this.getPersonal();
    this.loadingFishMutation(false);
  },
  beforeMount(){
    this.breadcrumbMutation('Recursos Humanos \\ Personal');
    this.searchPlaceholderMutation('Nombre del personal...');
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
  background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>