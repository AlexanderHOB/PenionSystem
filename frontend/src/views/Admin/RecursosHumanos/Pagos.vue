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
          <div>
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
            <img src="@/assets/iconos/icono_pagar.svg" alt="detail" class="personal-pagar" @click="pagoModal(i)">
            <img src="@/assets/iconos/icono_imprimir.svg" alt="detail" class="personal-imprimir" @click="pagoModal(i)">
            <PersonalBox class="personal-bg" :active="empleado.condicion" :activeInteraction="activarModal" :index="i" />
          </div>
        </v-flex>
      </template>
    </v-layout>

    <v-dialog v-model="createModalState" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 class="headline title-modal">Pago del personal</h3>
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
                  <p><strong>Sueldo</strong></p>
                </v-flex>
                <v-flex xs3>
                  <p>${{ sueldo }}</p>
                </v-flex>
                <!-- <v-flex xs6>
                  <v-text-field 
                    color="blue"
                    label="Monto"
                    v-model="monto"
                    :rules="[rules.required]"
                    type="number"
                    prefix="$"
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex> -->
                <v-flex x12 class="d-none">
                  <v-btn type="submit"></v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
            <v-btn color="blue" text @click="closeModal">Cerrar</v-btn>
            <v-btn :disabled="!valid" v-show="create" color="green darken-1" text @click="registrarPago"  :loading="isLoadBtn">Crear</v-btn>
            <!-- <v-btn :disabled="!valid" v-show="!create" color="green darken-1" text @click="editarAdelanto"  :loading="isLoadBtn">Editar</v-btn> -->
        </v-card-actions>
      </v-card>
    </v-dialog>
    
    <template v-if="pageTotal">
      <div class="text-center mt-5">
        <v-pagination
          v-model="page"
          :length="pageTotal"
          circle
          @input="paginatePersonal"
          @next="paginatePersonal"
          @previous="paginatePersonal"
        ></v-pagination>
      </div>
    </template>

    <LoadingDialog />

    <AlertNotifications />

  </v-container>
</template>

<script>
import axios from 'axios';
import LoadingDialog from '@/components/loading/LoadingDialog';
import LoadingFish from '@/components/loading/LoadingFish';
import PersonalBox from '@/components/box/PersonalBox';
import ErrorMessage from '@/components/messages/ErrorMessage';
import AlertNotifications from '@/components/messages/AlertNotifications';

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
      title: 'Personal',
      //Datos para el personal
      messagePersonal: '',
      personal: [],
      allPersonal: [],
      refresh: false,
      // Backup
      backup: {
        personal: [],
        personalIndex: true,
        pageTotal: 0
      },
      //Datos de personal
      apellidos: '',
      nombres: '',
      n_documento: '',
      celular: '',
      sueldo: '',
      // Datos para la paginación
      pagination: 5,
      pageTotal: 0,
      page: 1
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
            this.allPersonal = personal.filter(function(e){
              return e.condicion
            });
            this.paginatePersonal();
            this.messagePersonal = '';
            this.searchDisabledMutation(false);
          }else {
            this.messagePersonal = 'No se encontro ningún personal';
            this.pageTotal = 0;
          }
        }else {
          this.messagePersonal = response.data.message;
          this.personal = [];
          this.pageTotal = 0;
          this.searchDisabledMutation(true);
        }
      } catch (error) {
        this.pageTotal = 0;
        this.messagePersonal = 'Error al conctar con el servidor';
        this.searchDisabledMutation(true);
      }finally {
        this.loadingDialogMutation(false);
      }
    },

    refreshPersonal(){
      this.refresh = true;
      this.getPersonal();
    },

    // PAGINAR PERSONAL
    paginatePersonal(){
      if(this.allPersonal.length > this.pagination){
        this.personal = this.allPersonal.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page));
        this.pageTotal = Math.ceil(this.allPersonal.length / this.pagination);
      }else {
         this.personal = this.allPersonal;
          this.pageTotal = 0;
      }
    },

    // BUSCAR PERSONAL
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

    pagoModal(index){
      this.apellidos = this.personal[index].apellidos;
      this.nombres = this.personal[index].nombres;
      this.n_documento = this.personal[index].documento;
      this.celular = this.personal[index].celular;
      this.sueldo = this.personal[index].sueldo;
      this.createModalMutation(true);
    },

    closeModal(){
      this.createModalMutation(false);
      setTimeout(this.resetForm, 100);
    },

    resetForm(){
      this.apellidos = '';
      this.nombres = '';
      this.n_documento = '';
      this.celular = '';
      this.sueldo = '';
    },

    activarModal(i){

    },

    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation', 'searchQueryMutation', 'searchPlaceholderMutation', 'searchDisabledMutation']),
    ...mapActions(['allPersonalAction'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'createModalState', 'searchQuery', 'allPersonalState'])
  },
    watch: {
    searchQuery(){
      this.searchPersonal(this.searchQuery);
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
    this.breadcrumbMutation('Recursos Humanos \\ Pagos');
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
  &-pagar,
  &-imprimir {
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
  &-pagar {
    left: 50%;
  }
  &-imprimir {
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