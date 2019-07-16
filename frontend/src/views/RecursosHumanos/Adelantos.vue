<template>
  <v-container grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 class="d-flex align-center">
        <h1 class="display-1">{{ title }}</h1>
        <div class="text-xs-right">
          <v-btn class="blue" dark fab small @click="getAdelantos"><v-icon>replay</v-icon></v-btn>
        </div>
      </v-flex>
      <v-flex xs12>
         <v-data-table
          :headers="headers"
          :items="adelantos"
          :loading="isLoad"
          class="elevation-1"
        >
          <v-progress-linear v-slot:progress color="blue" indeterminate></v-progress-linear>
          <template v-slot:items="props">
            <td>{{ props.item.tipo }}</td>
            <td class="text-xs-right">{{ props.item.fecha_transaccion.split('-').reverse().join('-') }}</td>
            <td class="text-xs-right">{{ props.item.monto }}</td>
            <td class="text-xs-right">{{ props.item.motivo }}</td>
          </template>
        </v-data-table>
      </v-flex>
    </v-layout>

    <v-dialog v-model="createModalState" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 v-show="create" class="headline title-modal">Registrar Adelanto</h3>
          <h3 v-show="!create" class="headline title-modal">Editar Adelanto</h3>
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
                    label="..."
                    :rules="[rules.required]"
                    counter="50"
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
            <v-btn :disabled="!valid" v-show="create" color="green darken-1" flat @click="registrarAdelanto">Crear</v-btn>
            <v-btn :disabled="!valid" v-show="!create" color="green darken-1" flat @click="editarAdelanto">Editar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="adelantoDetail" max-width="600px">
      <v-container class="white" grid-list-md>
        <v-layout row wrap>
          <v-flex xs12>
            <h3 class="title-modal headline text-xs-center pb-3">Detalle del Adelanto</h3>
          </v-flex>
          <v-flex xs12>
            <v-layout row wrap>
              <v-flex xs3><strong>Apellidos</strong></v-flex>
              <v-flex xs9>
                <p></p>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs12 class="py-0 text-xs-right">
            <v-btn color="blue darken-1" flat @click="closeDetailModal">Cerrar</v-btn>
          </v-flex>
        </v-layout>
      </v-container>
    </v-dialog>

    <v-dialog
      v-model="deleteDialog"
      max-width="350"
    >
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 class="headline title-modal">Confirmación</h3>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-card-text>¿Realmente deseas eliminar el adelanto?</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" flat @click="deleteDialog = false">Cancelar</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click="deleteAdelanto(index)">Aceptar</v-btn>
          <v-spacer></v-spacer>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <AlertNotifications />

  </v-container>
</template>

<script>
import axios from 'axios';
import AlertNotifications from '../../components/messages/AlertNotifications';

import { mapState, mapMutations } from 'vuex';

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
        { text: 'Personal',align: 'left',  value: 'tipo' },
        { text: 'Fecha', align: 'center', width: 120, value: 'fecha_transaccion' },
        { text: 'Monto',align: 'center', value: 'monto' },
        { text: 'Motivo',align: 'center', value: 'motivo' },
      ],
      isLoad: true,
      // Backup
      backup: {
        adelantos: [],
        adelantosIndex: true
      },
      // Datos para valdiar formulario
      valid: true,
      rules: {
        required: value => !!value || 'Required.'
      },
      // Data para editar el adelanto
      create: true,
      index: 0,
      id: 0,
      // Detail

      // Datos para detalles del adelanto
      adelantoDetail: false,
      // Datos para activar/desactivar el modal
      deleteDialog: false,
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

        let response = await axios.get(this.url + 'historial/adelanto', this.config);
        if(response.data){
          let adelantos = response.data;
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

    // MODAL DE DETALLE
    detailAdelantoModal(index){
      this.adelantoDetail = true;
    },

    // CERRAR MODAL DE DETALLE
    closeDetailModal(){
      this.adelantoDetail = false;
      
      let self = this;
      setTimeout(function(){

      }, 100);
    },

    // MODAL PARA EDITAR ADELANTO
    editarAdelantoModal(index){

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

    // MODAL PARA  ELIMINAR
    deleteModal(index){
        this.index = index;
        this.deleteDialog = true;
    },    

    //  ACTIVAR 
    async deleteAdelanto(index){
      try {
        this.id = this.adelantos[index].id;
        this.deleteDialog = false;
      } catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un error', color: 'error'});
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

    ...mapMutations(['createModalMutation', 'headerActionsMutation', 'breadcrumbMutation', 'snackbarMutation', 'searchQueryMutation', 'searchPlaceholderMutation', 'searchDisabledMutation'])
  },
  computed: {
    ...mapState(['url', 'config', 'createModalState', 'searchQuery'])
  },
  watch: {
    searchQuery(){
      this.searchAdelantos(this.searchQuery);
    }
  },
  async created(){
    await this.getAdelantos();
  },
  beforeMount(){
    this.headerActionsMutation({create: false, report: false});
    this.breadcrumbMutation('Recursos Humanos \\ Adelantos');
    this.searchPlaceholderMutation('Nombre del personal...');
    this.searchDisabledMutation(true);
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