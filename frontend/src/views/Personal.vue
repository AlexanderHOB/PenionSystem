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
                    :rules="[rules.required, rules.counterApellidos]"
                    counter="50"
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-text-field 
                    color="blue"
                    label="Nombre"
                    v-model="nombre"
                    :rules="[rules.required, rules.counterNombre]"
                    counter="30"
                  ></v-text-field>
                </v-flex>
                <v-flex xs5>
                  <v-text-field 
                    color="blue"
                    label="Dirección"
                    v-model="direccion"
                    :rules="[rules.required, rules.counterDirección]"
                    counter="100"
                  ></v-text-field>
                </v-flex>
                <v-flex xs7>
                  <v-text-field 
                    color="blue"
                    label="Email"
                    v-model="email"
                    :rules="[rules.required, rules.counterEmail, rules.emailRules]"
                    counter="60"
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-text-field 
                    color="blue"
                    label="DNI"
                    v-model="dni"
                    :rules="[rules.required, rules.counterDni]"
                    counter="8"
                    mask="########"
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-text-field 
                    color="blue"
                    label="Celular"
                    v-model="celular"
                    :rules="[rules.required, rules.counterCelular]"
                    counter="9"
                    mask="#########"
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-select 
                    color="blue"
                    label="Puesto"
                    v-model="puesto"
                    :items="puestos"
                    :rules="[rules.required]"
                  ></v-select>
                </v-flex>
                <v-flex xs6>
                  <v-select 
                    color="blue"
                    label="Area"
                    v-model="area"
                    :items="areas"
                    :rules="[rules.required]"
                  ></v-select>
                </v-flex>
                <v-flex xs4>
                  <v-select 
                    color="blue"
                    label="Tipo de contrato"
                    v-model="tipo_contrato"
                    :items="tipo_contratos"
                    :rules="[rules.required]"
                  ></v-select>
                </v-flex>
                <v-flex xs4>
                  <v-menu
                    v-model="menuDate"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                  <template v-slot:activator="{ on }">
                    <v-text-field
                      color="blue"
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
                    color="blue"
                    label="Sueldo"
                    v-model="sueldo"
                    :rules="[rules.required]"
                    type="number"
                    prefix="$"
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
                <p>{{ fechaRegistroDetail }}</p>
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
      //Datos para el personal
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
        required: value => !!value || 'Required.',
        counterApellidos: value => value.length <= 50 || '50 caracteres como maximo',
        counterNombre: value => value.length <= 30 || '30 caracteres como maximo',
        counterDirección: value => value.length <= 100 || '100 caracteres como maximo',
        counterEmail: value => value.length <= 60 || '60 caracteres como maximo',
        counterDni: value => value.length == 8 || '8 caracteres como minimo',
        counterCelular: value => value.length == 9 || '9 caracteres como minimo',
        emailRules: value => /.+@.+/.test(value) || 'E-mail invalido'
      },
      // Data para editar el personal
      apellidos: '',
      nombre: '',
      direccion: '',
      email: '',
      dni: '',
      celular: '',
      puesto: '',
      area: '',
      tipo_contrato: '',
      fechaRegistro: '',
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
      emailDetail: '',
      dniDetail: '',
      celularDetail: '',
      puestoDetail: '',
      areaDetail: '',
      fechaRegistroDetail: '',
      sueldoDetail: '',
      disabledDate: false,
      // Datos para detalles del personal
      personalDetail: false,
      // Datos para Selects
      puestos: ['Caja', 'Mozo', 'Cocinero', 'Ayudante de Cocina', 'Almacen'],
      areas: ['Área de almacen', 'Área Caliente', 'Área Fría','Área mixta', 'Área de Ventas'],
      tipo_contratos: ['Semanal', 'Quincenal', 'Mensual']
    }
  },
  methods: {
    // OBTENER PERSONAL
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
        this.messagePersonal = 'Error al conctar con el servidor';
      }finally {
        this.loadingDialogMutation(false);
      }
    },

    // ACTUALIZAR PERSONAL
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
      this.fechaRegistroDetail = this.personal[index].fecha_registro.split('-').reverse().join('-');
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
        self.fechaRegistroDetail = '';
        self.sueldoDetail = '';
      }, 100);
    },

    // MODAL PARA EDITAR PERSONAL
    editarPersonalModal(index){
      this.apellidos = this.personal[index].apellidos;
      this.nombre = this.personal[index].nombres;
      this.direccion = this.personal[index].direccion;
      this.email = this.personal[index].email;
      this.dni = this.personal[index].dni;
      this.celular = this.personal[index].celular;
      this.puesto = this.personal[index].puesto_trabajo;
      this.area = this.personal[index].area_trabajo;
      this.tipo_contrato = this.personal[index].tipo_contrato;
      this.sueldo = this.personal[index].sueldo;
      this.fechaRegistro = this.personal[index].fecha_registro;
      this.index = index;
      this.id = this.personal[index].id;
      this.create = false;
      this.disabledDate = true;
      this.$refs.form.resetValidation();
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
      this.dni = '';
      this.celular = '';
      this.puesto = '';
      this.area = '';
      this.tipo_contrato = '';
      this.date = new Date().toISOString().substr(0, 10);
      this.fechaRegistro = '';
      this.sueldo = '';
      this.create = true;
      this.disabledDate = false;
      this.$refs.form.resetValidation();
    },

    // CREAR PERSONAL
    async registrarPersonal() {
      try {
        if (this.$refs.form.validate()) {
          this.loadingTitleMutation('Subiendo información del personal');
          this.createModalMutation(false);
          this.loadingDialogMutation(true);
          this.sueldo = parseFloat(parseFloat(this.sueldo).toFixed(2));
          let response = await axios.post(this.url + 'empleado/registrar', {
            apellidos: this.apellidos,
            nombres: this.nombre,
            dni: this.dni,
            celular: this.celular,
            email: this.email,
            direccion: this.direccion,
            area_trabajo: this.area,
            puesto_trabajo: this.puesto,
            tipo_contrato: this.tipo_contrato,
            fecha_registro: this.date,
            sueldo: this.sueldo
          }, this.config);

        this.snackbarMutation({value: true, text: 'Personal creado correctamente', color: 'success'});

        this.resetForm();

        if(this.personalTotal % 10 == 0){
            this.pageTotal++;
          }
          this.page = 1;
          await this.refreshPersonal(null, 'Registrando personal', true);
        }
      } catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un error al registrar el personal', color: 'error'});
        this.resetForm();
        this.loadingDialogMutation(false);
      }
    },

    // EDITAR PERSONAL
    async editarPersonal(){
      try {
        if (this.$refs.form.validate()) {

          let nombreBup = this.nombre;
          let descripcionBup = this.descripcion;

          this.closeModal();

          this.categorias[this.index].nombre = nombreBup;
          this.categorias[this.index].descripcion = descripcionBup;

          let response = await axios.post(this.url + 'categoria/platillo/actualizar/' + this.id, {
            nombre: nombreBup,
            descripcion: descripcionBup
          }, this.config);
          this.snackbarMutation({value: true, text: 'Categoria editada correctamente', color: 'success'});
        }
      }catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un erro al editar la categoria', color: 'error'});
      }
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
      if(this.create){
        await this.registrarPersonal();
      }else {
        await this.editarPersonal();
      }
      this.$refs.form.resetValidation();  
    },

    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'createModalState'])
  },
  watch: {
    date(val) {
      this.fechaRegistro = val.split('-').reverse().join('-');
    }
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
