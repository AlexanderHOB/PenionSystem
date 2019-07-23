<template>

  <LoadingFish v-if="loadingFish" />

  <v-container v-else grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 v-if="messageCategorias">
        <ErrorMessage :errorMessage="messageCategorias" :refresh="getCategorias" />
      </v-flex>
      
      <template v-else>
        <v-flex xs12 class="d-flex align-center">
          <h1 class="display-1">{{ title }}</h1>
          <div class="text-xs-right">
            <v-btn class="blue" dark fab small @click="refreshCategorias"><v-icon>replay</v-icon></v-btn>
          </div>
        </v-flex>
        <v-flex xs4 v-for="(categoria, i) of categorias" :key="categoria.id" class="mb-4">
          <div class="categoria-box">
            <h2 class="categoria-title text-xs-center">{{ categoria.nombre }}</h2>
            <PrimaryBox class="categoria-bg" :active="categoria.condicion" :activeInteraction="activarModal" :bgBox="bgBox" :bgBackBox="bgBackBox" :index="i" />
            <div class="categoria-textBox text-xs-center">
              <p class="mb-0 categoria-text" v-show="categoria.condicion">Activo</p> 
              <p class="mb-0 categoria-text" v-show="!categoria.condicion">Inactivo</p> 
            </div>
            <img src="../assets/iconos/edit.svg" alt="edit" class="categoria-edit" @click="editarCategoriaModal(i)">
            <img src="../assets/iconos/detail.svg" alt="detail" class="categoria-detail" @click="detailCategoriaModal(i)">
          </div>
        </v-flex>
      </template>
    </v-layout>

    <v-dialog v-model="createModalState" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 v-show="create" class="title-modal headline title-modal">Crear Categoria</h3>
          <h3 v-show="!create" class="title-modal headline title-modal">Editar Categoria</h3>
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
                <v-flex xs12>
                  <v-text-field 
                    color="blue"
                    label="Nombre de la categoria"
                    v-model="nombre"
                    :rules="[rules.required, rules.counterNombre]"
                    counter="30"
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
                    :rules="[rules.counterDescripcion]"
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
            <v-btn :disabled="!valid" v-show="create" color="green darken-1" flat @click="crearCategoria" :loading="isLoadBtn">Crear</v-btn>
            <v-btn :disabled="!valid" v-show="!create" color="green darken-1" flat @click="editarCategoria" :loading="isLoadBtn">Editar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="categoriasDetail" max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 class="title-modal headline">{{ nombreDetail }}</h3>
          <v-spacer></v-spacer>
          <v-btn icon color="blue--text" @click="closeDetailModal">
            <v-icon>close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text class="pb-0">
          <p>{{ descripcionDetail }}</p>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click="closeDetailModal">Cerrar</v-btn>
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
        <v-card-text>Confirma que quieres <strong>{{ activarText }}</strong> la categoria</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" flat @click="activeDialog = false">Cancelar</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click="activarCategoria(index)">Aceptar</v-btn>
          <v-spacer></v-spacer>
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
import PrimaryBox from '../components/box/PrimaryBox';
import ErrorMessage from '../components/messages/ErrorMessage';
import AlertNotifications from '../components/messages/AlertNotifications';

import { mapState, mapMutations, mapActions } from 'vuex';

export default {
  components: {
    LoadingDialog,
    LoadingFish,
    PrimaryBox,
    ErrorMessage,
    AlertNotifications
  },
  data() {
    return {
      title: 'Categorias',
      bgBox: '#fbb03b',
      bgBackBox: '#dc772d',
      //Datos para las categorias
      messageCategorias: '',
      categorias: [],
      allCategorias: [],
      isLoadBtn: false,
      disabled: false,
      refresh: false,
      // Backup
      backup: {
        categorias: [],
        cateogriasIndex: true,
        pageTotal: 0
      },
      // Datos para la paginación
      pagination: 10,
      pageTotal: 0,
      page: 1,
      // Datos para valdiar formulario
      valid: true,
      rules: {
        required: value => !!value || 'Required.',
        counterNombre: value => value.length <= 30 || '30 caracteres como maximo',
        counterDescripcion: value => value.length <= 50 || '50 caracteres como maximo'
      },
      // Data para editar Categoria
      nombre: '',
      descripcion: '',
      create: true,
      index: 0,
      id: 0,
      // Detail
      nombreDetail: '',
      descripcionDetail: '',
      // Datos para detalles de la categoria
      categoriasDetail: false,
      // Datos para activar/desactivar el modal
      activeDialog: false,
      activarText: ''
    }
  },
  methods: {
    // OBTENER CATEOGRIAS
    async getCategorias(){
      try {
        if(this.backup.categorias.length != 0){
          this.searchQueryMutation('');
          return;
        }
        this.loadingTitleMutation('Actualizando información');
        this.loadingDialogMutation(true);

        // let response = await axios.get(this.url + 'categoria/platillos', this.config);
        if(this.allCategoriasState.length == 0 || this.refresh){
          await this.allCategoriasAction();
          this.refresh = false;
        }
        if(this.allCategoriasState.data){
          let categorias = this.allCategoriasState.data;
          if(categorias.length > 0){
            this.allCategorias = categorias;
            this.page = 1;
            this.paginate();
            this.messageCategorias = '';
          }else {
            this.messageCategorias = 'No se encontraron categorias';
            this.pageTotal = 0;
          }
          this.searchDisabledMutation(false);
          this.headerActionsMutation({create: true, report: false});
        }else {
          this.headerActionsMutation({create: false, report: false});
          this.messageCategorias = response.data.message;
          this.categorias = [];
          this.pageTotal = 0;
          this.searchDisabledMutation(true);
        }
      } catch (error) {
        this.headerActionsMutation({create: false, report: false});
        this.searchDisabledMutation(true);
        this.pageTotal = 0;
        this.messageCategorias = 'Error al conctar con el servidor';
      }finally {
        this.loadingDialogMutation(false);
      }
    },

    refreshCategorias(){
      this.refresh = true;
      this.getCategorias();
    },

    // PAGINAR CATEGORIAS
    paginate(){
      if(this.allCategorias.length > this.pagination){
        this.categorias = this.allCategorias.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page));
        this.pageTotal = Math.ceil(this.allCategorias.length / this.pagination);
      }else {
         this.categorias = this.allCategorias;
          this.pageTotal = 0;
      }
    },


    // SEARCH CATEGORIAS
    searchCategorias(query){
      if(query != '' && query != null){
        if(this.categorias.length != 0 &&  this.backup.cateogriasIndex){
          this.backup.categorias = this.categorias;
          if(this.pageTotal != 0){
            this.backup.pageTotal = this.pageTotal;
          }
          this.backup.cateogriasIndex = false;
        }

          if(this.messageCategorias.length != 0){
            this.messageCategorias = '';
          }

        this.categorias = this.allCategorias.filter(function(e){
          return e.nombre.toLowerCase().search(query.toLowerCase()) != -1;
        });

        if(this.categorias.length == 0){
          this.messageCategorias = 'No se encontraron categorias con el nombre ' + query;
        }
        this.pageTotal = 0;   
      }else {
        if(this.backup.categorias.length != 0){
          this.categorias =this.backup.categorias;
          this.backup.categorias = [];
          this.messageCategorias = '';
          if(this.backup.pageTotal != 0){
            this.pageTotal = this.backup.pageTotal;
            this.backup.pageTotal = 0;
          }
          this.backup.cateogriasIndex = true;
        }
      }
    },

    // MODAL DE DETALLE
    detailCategoriaModal(index){
      this.nombreDetail = this.categorias[index].nombre;
      this.descripcionDetail = this.categorias[index].descripcion;
      this.categoriasDetail = true;
    },

    // CERRAR MODAL DE DETALLE
    closeDetailModal(){
      this.categoriasDetail = false;
      let self = this;
      setTimeout(function(){
        self.nombreDetail = '';
        self.descripcionDetail = '';
      }, 100);
    },

    // MODAL PARA EDITAR CATEGORIA
    editarCategoriaModal(index){
      this.nombre = this.categorias[index].nombre;
      this.descripcion = this.categorias[index].descripcion;
      this.index = index;
      this.id = this.categorias[index].id;
      this.create = false;
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
      this.nombre = '';
      this.descripcion = '';
      this.create = true;
      this.$refs.form.resetValidation();
    },

    // CREAR CATEGORIA
    async crearCategoria() {
      try {
        if (this.$refs.form.validate()) {
          this.isLoadBtn = true;
          this.disabled = true;
          if(this.descripcion == '' || this.descripcion == null){
            this.descripcion = 'Categoria sin descripción';
          }

          let response = await axios.post(this.url + 'categoria/platillo/registrar', {
            nombre: this.nombre,
            descripcion: this.descripcion
          }, this.config);

          this.closeModal();

          if(this.pageTotal == 0 && this.backup.pageTotal != 0){
              this.pageTotal = this.backup.pageTotal;
          }

          if(response.data){
            this.allCategorias.reverse();
            this.allCategorias.push(response.data);
            this.allCategorias.reverse();
            this.snackbarMutation({value: true, text: 'Categoria creada correctamente', color: 'success'});
            this.paginate();
            this.page = 1;
            this.backup.categorias = [];
            this.messageCategorias = '';
            this.backup.cateogriasIndex = true;
          }else{
            this.snackbarMutation({value: true, text: 'Ocurrio un error al crear la categoria', color: 'error'});
          }
        }
      }catch (error) {
        this.closeModal();
        this.snackbarMutation({value: true, text: 'Ocurrio un error en el servidor', color: 'error'});
      }finally {
        this.isLoadBtn = false;
        this.disabled = false;
      }
    },

    // EDITAR CATEGORIA
    async editarCategoria(){
      try {
        if (this.$refs.form.validate()) {
          if(this.descripcion == '' || this.descripcion == null){
            this.descripcion = 'Cateogria sin descripción';
          }

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
        this.snackbarMutation({value: true, text: 'Ocurrio un error al editar la categoria', color: 'error'});
      }
    },

    // MODAL PARA ACTIVAR
    activarModal(index){
        this.index = index;
        if(this.categorias[index].condicion){
          this.activarText = 'desactivar';
        }else{
          this.activarText = 'activar';
        }
        this.activeDialog = true;
    },

    //  ACTIVAR 
    async activarCategoria(index){
      try {
        this.id = this.categorias[index].id;
        this.activeDialog = false;
        if(this.categorias[index].condicion){
          this.categorias[index].condicion = 0;

          let response = await axios.put(this.url + 'categoria/platillo/desactivar/' + this.id, {},this.config);
          this.snackbarMutation({value: true, text: 'Categoria desactivada correctamente', color: 'success'});
        }else {
          this.categorias[index].condicion = 1;

          let response = await axios.put(this.url + 'categoria/platillo/activar/' + this.id, {}, this.config);
          this.snackbarMutation({value: true, text: 'Categoria activada correctamente', color: 'success'});
        }
      } catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un error', color: 'error'});
      }
    },

    // ACCION DEL FORMULARIO
    async formAction(){
      if(this.create){
        await this.crearCategoria();
      }else {
        await this.editarCategoria();
      }
    },
    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation', 'searchQueryMutation', 'searchPlaceholderMutation', 'searchDisabledMutation']),
    ...mapActions(['allCategoriasAction'])

  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'createModalState', 'searchQuery', 'allCategoriasState'])
  },
   watch: {
    searchQuery(){
      this.searchCategorias(this.searchQuery);
    }
  },
  async created(){
    this.loadingFishMutation(true);
    this.headerActionsMutation({create: false, report: false});
    this.searchDisabledMutation(true);
    await this.getCategorias();
    this.loadingFishMutation(false);
  },
  beforeMount(){
    this.breadcrumbMutation('Categorias');
    this.searchPlaceholderMutation('Nombre de la categoria...');
  }
}
</script>

<style lang="scss" scoped>
.categoria {
  &-box {
    position: relative;
    font-family: 'Concert One', cursive;
  }
  &-bg {
    width: 100%;
  }
  &-title {
    position: absolute;
    top: 45%;
    left: 65%;
    transform: translate(-50%, -50%);
    z-index: 2;
    font-size: 24px;
    line-height: 100%;
  }
  &-textBox {
    position: absolute;
    top: 50%;
    left: 2%;
    transform: translateY(-50%);
    pointer-events: none;
    width: 25%;
  }
  &-text {
    color: #fff; 
    font-size: 18px;
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
    left: 40%;
  }
  &-detail {
    right: 15%;
  }
}
.title-modal {
  background-image: $primary-gradient;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>
