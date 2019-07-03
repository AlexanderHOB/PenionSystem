<template>

  <LoadingFish v-if="loadingFish" />

  <v-container grid-list-lg v-else>
    <v-layout row wrap>
      <v-flex xs12 v-if="messageMesas">
        <ErrorMessage :errorMessage="messageMesas" :refresh="getMesas" />
      </v-flex>
      <template v-else>
        <v-flex xs12 class="d-flex align-center">
          <h1 class="display-1">{{ title }}</h1>
          <div class="text-xs-right">
            <v-btn class="blue" dark fab small @click="refreshMesas(page, 'Actualizando información')"><v-icon>replay</v-icon></v-btn>
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
                <h2>{{ mesa.id }}</h2>
            </v-card-title>
            <v-card-text class="py-0">
              <p><strong>Capacidad: {{ mesa.capacidad }} {{ mesa.capacidad == 1 ? 'persona' : 'personas' }}</strong></p>
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
                    :rules="[rules.required, rules.zero]"
                    counter="2"
                    mask="##"
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
            <v-btn color="red darken-1" flat @click="closeModal">Cerrar</v-btn>
            <v-btn :disabled="!valid" v-show="create" color="green darken-1" flat @click="crearMesa">Crear</v-btn>
            <v-btn :disabled="!valid" v-show="!create" color="green darken-1" flat @click="editarMesa">Editar</v-btn>
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
          :disabled="paginateDisabled"
          @input="refreshMesas"
          @next="refreshMesas"
          @previous="refreshMesas"
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

  import { mapState, mapMutations } from 'vuex';

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
      mesasTotal: 0,
      backup: {
        mesas: [],
        mesasIndex: true,
        pageTotal: 0
      },
      // Data para la paginación
      pageTotal: 0,
      page: 1,
      paginateDisabled: false,
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
            this.messageMesas = '';
            this.mesas =this.backup.mesas;
            this.backup.mesas = [];
            if(this.backup.pageTotal != 0){
              this.pageTotal = this.backup.pageTotal;
              this.backup.pageTotal = 0;
            }
          }else {
            this.loadingTitleMutation('Accediendo a la información');
            this.loadingDialogMutation(true);

            let response = await axios.get(this.url + 'mesa', this.config);

            if(response.data.data){
              let data = response.data;
              let mesas = data.data;
              if(mesas.length > 0){
                this.mesas = mesas;
                this.messageMesas = '';
                this.mesasTotal = data.total;

                if(data.last_page && data.last_page > 1){
                  this.pageTotal = data.last_page;
                }
                this.page = 1;
              }else {
                this.messageMesas = 'No se encontraron mesas';
                this.pageTotal = 0;
              }
              this.headerActionsMutation({create: true, report: true});
              this.searchDisabledMutation(false);
            }else {
              this.headerActionsMutation({create: false, report: false});
              this.searchDisabledMutation(true);
              this.messageMesas = response.data.message;
              this.mesas = [];
              this.pageTotal = 0;
            }
          }
        } catch (error) {
          this.headerActionsMutation({create: false, report: false});
          this.searchDisabledMutation(true);
          this.pageTotal = 0;
          this.messageMesas = 'Error al conctar con el servidor';
        }finally {
          this.loadingDialogMutation(false);
        }
      },
      
      // OBTENER TODAS LAS MESAS
      async getAllMesas(){
        try {
          let response = await axios.get(this.url + 'mesas', this.config);
          if(response.data){
            let mesas = response.data;
            if(mesas.length > 0){
              this.allMesas = mesas;
            }
          }
        } catch (error) {
          console.log(error);
        }
      },

      // ACTUALIZANDO MESAS
      async refreshMesas(page = this.page, loadingTitle ='Accediendo a la información', create = false){
        try {
          if(this.backup.mesas.length != 0){
            this.mesas =this.backup.mesas;
            this.backup.mesas = [];
            if(this.backup.pageTotal != 0){
              this.pageTotal = this.backup.pageTotal;
              this.backup.pageTotal = 0;
            }
          }else {
            this.paginateDisabled = true;
            this.loadingTitleMutation(loadingTitle);
            if(!create){
              this.loadingDialogMutation(true);
            }

            let response = await axios.get(this.url + 'mesa', {
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
              let mesas = data.data;
              if(mesas.length > 0){
                this.mesas = mesas;
                this.messageMesas = '';
                if(data.total != this.mesasTotal){
                  this.mesasTotal = data.total;
                }
                if(data.last_page != this.pageTotal){
                  this.pageTotal = data.last_page;
                }
              }else {
                this.messageMesas = 'No se encontraron mesas';
                this.pageTotal = 0;
              }
              this.headerActionsMutation({create: true, report: true});
              this.searchDisabledMutation(false);
            }else {
              this.headerActionsMutation({create: false, report: false});
              this.searchDisabledMutation(true);
              this.messageMesas = response.data.message;
              this.mesas = [];
              this.pageTotal = 0;
            }
          }
        }catch(error) {
          this.headerActionsMutation({create: false, report: false});
              this.searchDisabledMutation(true);
          this.pageTotal = 0;
          this.messageMesas = 'Error al conctar con el servidor';
        }finally {
          this.loadingDialogMutation(false);
          this.paginateDisabled = false;
        }
      },

      // SEARCH MESAS
      async searchMesas(query){
        if(this.mesas.length != 0 && this.backup.mesasIndex){
          this.backup.mesas = this.mesas;
          if(this.pageTotal != 0){
            this.backup.pageTotal = this.pageTotal;
          }
          this.backup.mesasIndex = false;
        }

        this.mesas = this.allMesas.filter(function(e){
          return  e.id == query;
        });
        if(this.mesas.length == 0){
          this.messageMesas = 'No se encontraron mesas con ese número';
        }
        this.pageTotal = 0;
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
            this.loadingTitleMutation('Subiendo informacón de la mesa');
            this.createModalMutation(false);
            this.loadingDialogMutation(true);

            let response = await axios.post(this.url + 'mesa/registrar', {
              numero: parseInt(this.numero),
              capacidad: parseInt(this.capacidad),
              descripcion: this.descripcion
            }, this.config);

            this.snackbarMutation({value: true, text: 'Mesa creada correctamente', color: 'success'});

            this.resetForm();    
            
            if(this.mesasTotal % 10 == 0){
              this.pageTotal++;
            }
            this.page = this.pageTotal;
            await this.refreshMesas(null, 'Creando Mesa', true);
          }
        } catch (error) {
          this.snackbarMutation({value: true, text: 'Ocurrio un error al crear la mesa', color: 'error'});
          this.resetForm();
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
        this.$refs.form.resetValidation();
      },

      ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation', 'searchPlaceholderMutation', 'searchDisabledMutation']),
    },
    computed: {
      ...mapState(['url', 'config', 'loadingFish', 'createModalState', 'searchQuery'])
    },
    watch: {
      searchQuery(){
        this.searchMesas(this.searchQuery);
      }
    },
    created() {
      this.headerActionsMutation({create: false, report: false});
      this.loadingFishMutation(true);
    },
    // AL CREAR LA INSTANCIA DE VUE
    async mounted(){
      this.getAllMesas();
      this.breadcrumbMutation('Mesas');
      this.searchPlaceholderMutation('Número de mesa...');
      await this.getMesas();
      this.loadingFishMutation(false);
    }
  }
</script>

<style lang="scss" scoped>
  .mesa {
    transform: translateY(25px);
  }
</style>