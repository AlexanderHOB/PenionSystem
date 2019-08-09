<template>

  <LoadingFish v-if="loadingFish" />

  <v-container grid-list-lg v-else>
    <v-layout row wrap>
      <v-flex xs12 v-if="messageMesas">
        <ErrorMessage :errorMessage="messageMesas" :refresh="refreshMesas" />
      </v-flex>
      <template v-else>
        <v-flex xs12 class="d-flex align-center">
          <h1 class="display-1">{{ title }}</h1>
          <div class="text-xs-right">
            <v-btn class="blue" dark fab small @click="refreshMesas"><v-icon>replay</v-icon></v-btn>
          </div>
        </v-flex>
        <v-flex v-for="(mesa, i) of mesas" :key="mesa.id" xs12 sm6 md4 lg3>
          <v-card>
            <v-img
            :src="require('../assets/img/mesas/mesaOcupada.svg')"
            contain
            height="100"
            class="mesa"
            ></v-img>
            <v-card-title class="pt-0">
                <h2>{{ mesa.numero }}</h2>
            </v-card-title>
            <v-card-text class="py-0">
              <p><strong>Capacidad: {{ mesa.capacidad | persona }}</strong></p>
              <p>{{ mesa.descripcion }}</p>
            </v-card-text>
            <v-card-actions>
              <v-btn color="green" flat @click="editarMesaModal(i)">Editar Mesa</v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </template>
    </v-layout>

    <v-dialog v-model="createModalState" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 v-show="create" class="headline title-modal">Crear Mesa</h3>
          <h3 v-show="!create" class="headline title-modal">Editar Mesa</h3>
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
                <v-flex xs12 sm6>
                  <v-text-field
                    color="blue"
                    label="Número de mesa"
                    v-model="numero"
                    :rules="[rules.required, rules.zero, unique]"
                    counter="2"
                    mask="##"
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex> 
                <v-flex xs12 sm6>
                  <v-text-field
                    color="blue"
                    label="Capacidad"
                    v-model="capacidad"
                    :rules="[rules.required, rules.maxValue, rules.zero]"
                    counter="2"
                    mask="##"
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12>
                  <v-textarea
                    color="blue"
                    label="Descripción"
                    auto-grow
                    @click:append="descripcion = ''"
                    v-model="descripcion"
                    :rules="[rules.counter]"
                    counter="50"
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
            <v-btn color="red darken-1" flat @click="closeModal" :disabled="disabled">Cerrar</v-btn>
            <v-btn :disabled="!valid" v-show="create" color="green darken-1" flat @click="crearMesa" :loading="isLoadBtn">Crear</v-btn>
            <v-btn :disabled="!valid" v-show="!create" color="green darken-1" flat @click="editarMesa" :loading="isLoadBtn">Editar</v-btn>
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
import LoadingDialog from '../components/loading/LoadingDialog';
import LoadingFish from '../components/loading/LoadingFish';
import ErrorMessage from '../components/messages/ErrorMessage';
import AlertNotifications from '../components/messages/AlertNotifications';

import { mapState, mapMutations, mapActions } from 'vuex';

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
    // Datos para valdiar formulario
    valid: true,
    rules: {
      required: value => !!value || 'Required.',
      counter: value => value.length <= 50 || '50 caracteres como maximo',
      zero: value => value.charAt(0) != 0 || 'el primer digito no puede ser 0',
      maxValue: value => value <= 30 || 'la capacidad no puede ser más de 30'
    },
    // Data para editar Mesa
    numero: '',
    capacidad: '',
    descripcion: '',
    create: true,
    index: 0,
    id: 0
  }),
  methods: {
    // OBTENER MESAS
    async getMesas(){
      try {
        if(this.backup.mesas.length != 0){
          this.searchQueryMutation('');
          return;
        }
        this.loadingTitleMutation('Actualizando información');
        this.loadingDialogMutation(true);

        // let response = await axios.get(this.url + 'mesas', this.config);
        if(this.allMesasState.length == 0 || this.refresh){
          await this.allMesasAction();
          this.refresh = false;
        }
        if(this.allMesasState.data){
          let mesas = this.allMesasState.data;
          if(mesas.length > 0){
            this.allMesas = mesas;
            this.page = 1;
            this.paginate();
            this.messageMesas = '';
          }else {
            this.messageMesas = 'No se encontraron mesas';
            this.pageTotal = 0;
          }
          this.searchDisabledMutation(false);
          this.headerActionsMutation({create: true, report: true});
        }else {
          this.headerActionsMutation({create: false, report: false});
          this.messageMesas = response.data.message;
          this.mesas = [];
          this.pageTotal = 0;
          this.searchDisabledMutation(true);
        }
        
      } catch (error) {
        this.headerActionsMutation({create: false, report: false});
        this.pageTotal = 0;
        this.messageMesas = 'Error al conctar con el servidor';
        this.searchDisabledMutation(true);
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

    // SEARCH MESAS
    searchMesas(query){
      if(!isNaN(query)){
        if(query != '' && query != null){
          if(this.mesas.length != 0 && this.backup.mesasIndex){
            this.backup.mesas = this.mesas;
            if(this.pageTotal != 0){
              this.backup.pageTotal = this.pageTotal;
            }
            this.backup.mesasIndex = false;
          }

          if(this.messageMesas.length != 0){
            this.messageMesas = '';
          }

          this.mesas = this.allMesas.filter(function(e){
            return  e.numero == query;
          });
          if(this.mesas.length == 0){
            this.messageMesas = 'No se encontraron mesas con el número ' + query;
          }
          this.pageTotal = 0;
        }else {
          if(this.backup.mesas.length != 0){
            this.mesas =this.backup.mesas;
            this.backup.mesas = [];
            this.messageMesas = '';
            if(this.backup.pageTotal != 0){
              this.pageTotal = this.backup.pageTotal;
              this.backup.pageTotal = 0;
            }
            this.backup.mesasIndex = true;
          }
        }
      }else {
        this.snackbarMutation({value: true, text: 'Solo valores numericos', color: 'error'});
      }
    },

    // MODAL PARA EDITAR MESA
    editarMesaModal(index){
      this.numero = this.mesas[index].numero;
      this.capacidad = this.mesas[index].capacidad;
      this.descripcion = this.mesas[index].descripcion;
      this.index = index;
      this.id = this.mesas[index].id;
      this.create = false;
      this.$refs.form.resetValidation();
      this.createModalMutation(true);
    },

    // CERRAR MODAL
    closeModal(){
      this.createModalMutation(false);
      setTimeout(this.resetForm, 100);
    },

    // LIMPIAR FORMULARIO
    resetForm(){
      this.numero = '';
      this.capacidad = '';
      this.descripcion = '';
      this.create = true;
      this.$refs.form.resetValidation();
    },

    // CREAR MESAS
    async crearMesa(){
      try {
        if (this.$refs.form.validate()) {
          this.isLoadBtn = true;
          this.disabled = true;

          if(this.descripcion == '' || this.descripcion == null){
            this.descripcion = 'Mesa sin descripción';
          }

          let response = await axios.post(this.url + 'mesa/registrar', {
            numero: parseInt(this.numero),
            capacidad: parseInt(this.capacidad),
            descripcion: this.descripcion
          }, this.config);

          this.closeModal();

          if(this.pageTotal == 0 && this.backup.pageTotal != 0){
              this.pageTotal = this.backup.pageTotal;
          }

          if(this.allMesas.length % 10 === 0){
            this.pageTotal++;
          }

          if(response.data){
            this.allMesas.push(response.data);
            this.snackbarMutation({value: true, text: 'Mesa creada correctamente', color: 'success'});
            this.page = this.pageTotal;
            this.paginate();
            this.messageMesas = '';
            this.backup.mesas = [];
             this.backup.mesasIndex = true;
          }else{
          this.snackbarMutation({value: true, text: 'Ocurrio un error al crear la mesa', color: 'error'});
          }
        }
      } catch (error) {
        this.closeModal();
        this.snackbarMutation({value: true, text: 'Ocurrio un error al crear la mesa', color: 'error'});
      }finally {
        this.isLoadBtn = false;
        this.disabled = false;
      }
    },

    // EDITAR MESA
    async editarMesa(){
      try {
        if (this.$refs.form.validate()) {
          if(this.descripcion == '' || this.descripcion == null){
            this.descripcion = 'Mesa sin descripción';
          }

          let numeroBup = parseInt(this.numero);
          let capacidadBup = parseInt(this.capacidad);
          let descripcionBup = this.descripcion;

          this.closeModal();

          this.mesas[this.index].numero = numeroBup;
          this.mesas[this.index].capacidad = capacidadBup;
          this.mesas[this.index].descripcion = descripcionBup;

          let response = await axios.post(this.url + 'mesa/actualizar/' + this.id, {
            numero: numeroBup,
            capacidad: capacidadBup,
            descripcion: descripcionBup
          }, this.config);
          this.snackbarMutation({value: true, text: 'Mesa editada correctamente', color: 'success'});
        }
      } catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un error al editar la mesa', color: 'error'});
      }
    },

    // ACCION DEL FORMULARIO
    async formAction(){
      if(this.create){
        await this.crearMesa();
      }else {
        await this.editarMesa();
      }
    },

    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation', 'searchPlaceholderMutation', 'searchQueryMutation', 'searchDisabledMutation', 'searchItemsMutation']),
    ...mapActions(['allMesasAction'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'createModalState', 'searchQuery', 'allMesasState']),
    unique(){
        var counter = 0;
        var self = this;
        for(let i of this.allMesas){
          if(this.numero == i.numero){
            counter++;
            if(!this.create && this.numero == this.mesas[this.index].numero){
              counter = 0;
            }
          }
        }
        return !counter || 'Este número ya esta en uso'
      }
  },
  watch: {
    searchQuery(){
        this.searchMesas(this.searchQuery);
    }
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
    this.headerActionsMutation({create: false, report: false});
    this.searchDisabledMutation(true);
    await this.getMesas();
    this.loadingFishMutation(false);
  },
  beforeMount(){
    this.breadcrumbMutation('Mesas');
    this.searchPlaceholderMutation('Número de mesa...');
  }
}
</script>

<style lang="scss" scoped>
.mesa {
  transform: translateY(25px);
}
.title-modal {
  background-image: $primary-gradient;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>