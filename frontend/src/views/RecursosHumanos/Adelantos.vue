<template>
  <v-container grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 class="d-flex align-center justify-space-between">
        <h1 class="display-1">{{ title }}</h1>
        <div>
          <v-btn class="blue" dark fab small @click="refreshAdelantos"><v-icon>replay</v-icon></v-btn>
        </div>
      </v-flex>
      <v-flex xs12>
        <v-data-table
          :headers="headers"
          :items="adelantos"
          :loading="isLoad"
          loading-text="Loading..."
          class="elevation-1"
        >
          <template v-slot:item.fecha_transaccion="{ item }">
              <span>{{ item.fecha_transaccion | reverseDate }}</span>
          </template>
          <template v-slot:item.action="{ item }">
            <v-btn icon @click="editarAdelantoModal(item.id)">
              <v-icon>create</v-icon>
            </v-btn>
          </template>
        </v-data-table>
      </v-flex>
    </v-layout>

    <v-dialog v-model="createModalState" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 class="headline title-modal">Datos del personal</h3>
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
                  <h3 v-show="create" class="headline title-modal text-center">Registrar Adelanto</h3>
                  <h3 v-show="!create" class="headline title-modal text-center">Editar Adelanto</h3>
                </v-flex>
                <v-flex xs6>
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
                      v-model="fechaAdelanto"
                      label="Fecha de adelanto"
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
                <v-flex xs6>
                  <v-text-field 
                    label="Monto"
                    v-model="monto"
                    :rules="[rules.required]"
                    type="number"
                    prefix="$"
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12>
                  <v-textarea
                    label="Motivo"
                    auto-grow
                    @click:append="motivo = ''"
                    v-model="motivo"
                    :rules="[rules.counter]"
                    counter="250"
                    append-icon="clear"
                    @keydown.enter="formAction"
                    :disabled="disabled"
                  ></v-textarea>
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
            <v-btn color="red darken-1" text @click="closeModal">Cerrar</v-btn>
            <v-btn :disabled="!valid" v-show="create" color="green darken-1" text @click="registrarAdelanto"  :loading="isLoadBtn">Crear</v-btn>
            <v-btn :disabled="!valid" v-show="!create" color="green darken-1" text @click="editarAdelanto"  :loading="isLoadBtn">Editar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <AlertNotifications />

  </v-container>
</template>

<script>
import axios from 'axios';
import AlertNotifications from '../../components/messages/AlertNotifications';

import { mapState, mapMutations, mapActions } from 'vuex';

export default {
  components: {
    AlertNotifications
  },
  data(){
    return {
      title: 'Adelantos',
      //Datos para los adelantos
      adelantos: [],
      headers: [
        { text: 'Personal', align: 'left',  value: 'tipo' },
        { text: 'Fecha', align: 'center', width: 120, value: 'fecha_transaccion' },
        { text: 'Monto', align: 'center', value: 'monto' },
        { text: 'Motivo', align: 'center', value: 'motivo' },
        { text: 'Acciones', align: 'center', sortable: false, value: 'action' }
      ],
      isLoad: true,
      refresh: false,
      // Backup
      backup: {
        adelantos: [],
        adelantosIndex: true
      },
      // Datos para valdiar formulario
      valid: true,
      rules: {
        required: value => !!value || 'Required.',
        counter: value => value.length <= 250 || '250 carácteres como máximo'
      },
      // Data para editar el adelanto
      fechaAdelanto: new Date().toISOString().substr(0, 10).split('-').reverse().join('-'),
      monto: '',
      motivo: '',
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
      // Datos para el data picker
      date: new Date().toISOString().substr(0, 10),
      menuDate: false,
      maxDate: new Date().toISOString().substr(0, 10),
      minDate: '2010',
      disabledDate: true
    }
  },
  methods: {
    // OBTENER ADELANTOS
    async getAdelantos(){
      try {
        if(this.backup.adelantos.length != 0){
          this.searchQueryMutation('');
          return;
        }

        this.searchDisabledMutation(true);
        this.isLoad = true;

        // let response = await axios.get(this.url + 'historial/adelanto', this.config);
        if(this.allAdelantosState.length == 0 || this.refresh){
          await this.allAdelantosAction();
          this.refresh = false;
        }
        if(this.allAdelantosState.data){
          let adelantos = this.allAdelantosState.data;
          if(adelantos.length > 0){
            this.adelantos = adelantos;
            this.searchDisabledMutation(false);
          }else {
            this.adelantos = [];
          }
          this.headerActionsMutation({create: true, report: false});
        }else {
          this.headerActionsMutation({create: false, report: false});
          this.adelantos = [];
          this.snackbarMutation({value: true, text: 'Error al recibir la información', color: 'error'});
          this.searchDisabledMutation(true);
        }
      } catch (error) {
        this.headerActionsMutation({create: false, report: false});
        this.snackbarMutation({value: true, text: 'Error al conctar con el servidor', color: 'error'});
        this.searchDisabledMutation(true);
      }finally {
        this.isLoad = false;
      }
    },

    refreshAdelantos(){
      this.refresh = true;
      this.getAdelantos();
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
          if(personal.length > 0){
            this.allPersonal = personal.filter(function(e){
              return e.condicion
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
      this.disabledDate = false;
    },

    // SEARCH ADELANTOS
    searchAdelantos(query){
      if(query != '' && query != null){
        if(this.adelantos.length != 0 && this.backup.adelantosIndex){
          this.backup.adelantos = this.adelantos;
          this.backup.adelantosIndex = false;
        }

        // this.adelantos = this.adelantos.filter(function(e){
        //   return e.nombres.toLowerCase().search(query.toLowerCase()) != -1 || e.apellidos.toLowerCase().search(query.toLowerCase()) != -1;
        // });

        this.adelantos = this.backup.adelantos.filter(function(e){
          return e.fecha_transaccion.search(query) != -1;
        });

      }else {
        if(this.backup.adelantos.length != 0){
          this.adelantos = this.backup.adelantos;
          this.backup.adelantos = [];
          this.searchQueryMutation('');
          this.backup.adelantosIndex = true;
        }
      }
    },

    // MODAL PARA EDITAR ADELANTO
    async editarAdelantoModal(id){
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
      this.personal = {};
      this.apellidos = '';
      this.nombres = '';
      this.n_documento = '';
      this.celular = '';
      this.puesto = '';
      this.area = '';
      this.disabledDate = false;
      this.date = new Date().toISOString().substr(0, 10);
      this.fechaAdelanto = this.date.split('-').reverse().join('-');
      this.disabled = true;
      this.disabledDate = true;
      this.monto = '';
      this.motivo = '';
      this.create = true;
      this.isLoadingPersonal = false;
      this.$refs.form.resetValidation();
    },

    // REGISTRAR ADELANTO
    async registrarAdelanto() {
      try {
        if (this.$refs.form.validate()) {
        // this.loadingDialogMutation(true);
          this.createModalMutation(false);

          let response = await axios.post(this.url + 'empleado/registrar', {

          }, this.config);

          this.snackbarMutation({value: true, text: 'Aumento registrado correctamente', color: 'success'});

          this.resetForm();

          this.backup.adelantos = [];
          await this.getAdelantos();
        }
      } catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un error al registrar el aumento', color: 'error'});
        this.resetForm();
        // this.loadingDialogMutation(false);
      }
    },

    // EDITAR ADELANTO
    async editarAdelanto(){
      try {
        if (this.$refs.form.validate()) {

          // let apellidosBup = this.apellidos;

          this.closeModal();

          // this.adelantos[this.index].apellidos = apellidosBup;

          let response = await axios.put(this.url + 'empleado/actualizar/' + this.id, {

          }, this.config);
          this.snackbarMutation({value: true, text: 'Adelanto editado correctamente', color: 'success'});
        }
      }catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un error al editar el adelanto', color: 'error'});
      }
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

    ...mapMutations(['createModalMutation', 'headerActionsMutation', 'breadcrumbMutation', 'snackbarMutation', 'searchQueryMutation', 'searchPlaceholderMutation', 'searchDisabledMutation']),
    ...mapActions(['allAdelantosAction', 'allPersonalAction'])
  },
  computed: {
    ...mapState(['url', 'config', 'createModalState', 'searchQuery', 'allAdelantosState', 'allPersonalState']),
    items(){
      return this.allPersonal.map(entry => {
        const fullName = `${entry.apellidos} ${entry.nombres}`

        return Object.assign({}, entry, { fullName })
      })
    }
  },
  watch: {
    date(val) {
      this.fechaAdelanto = val.split('-').reverse().join('-');
    },
    searchQuery(){
      this.searchAdelantos(this.searchQuery);
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
    await this.getAdelantos();
  },
  beforeMount(){
    this.breadcrumbMutation('Recursos Humanos \\ Adelantos');
    this.searchPlaceholderMutation('Nombre del personal o fecha de transacción...');
  }
}
</script>

<style lang="scss" scoped>
.title-modal {
  background-image: $primary-gradient;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>