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
            <v-btn class="blue" dark fab small @click="refreshCategorias(page, 'Actualizando información')"><v-icon>replay</v-icon></v-btn>
          </div>
        </v-flex>
        <v-flex xs4 v-for="(categoria, i) of categorias" :key="categoria.id" class="mb-4">
          <div class="categoria-box">
            <h2 class="categoria-title text-xs-center">{{ categoria.nombre }}</h2>
            <PrimaryBox class="categoria-bg" :active="categoria.condicion" :activeInteraction="activarCategoria" :bgBox="bgBox" :bgBackBox="bgBackBox" :index="i" />
            <p class="mb-0 categoria-text" v-if="categoria.condicion">Activo</p> 
            <p class="mb-0 categoria-text" v-else>Inactivo</p> 
            <img src="../assets/iconos/edit.svg" alt="" class="categoria-edit" @click="editarCategoriaModal(i)">
            <img src="../assets/iconos/detail.svg" alt="" class="categoria-detail" @click="detailCategoriaModal(i)">
          </div>
        </v-flex>
      </template>
    </v-layout>

    <v-dialog v-model="createModalState" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 v-if="create" class="headline">Crear Categoria</h3>
          <h3 v-else class="headline">Editar Categoria</h3>
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
                <v-flex xs12>
                  <v-text-field 
                    color="blue"
                    label="Nombre de la categoria"
                    v-model="nombre"
                    :rules="[rules.required, rules.counterNombre]"
                    counter="30"
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
            <v-btn :disabled="!valid" v-if="create" color="green darken-1" flat @click="crearCategoria">Crear</v-btn>
            <v-btn :disabled="!valid" v-else color="green darken-1" flat @click="editarCategoria">Editar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="categoriasDetail" max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 class="headline">{{ nombreDetail }}</h3>
          <v-spacer></v-spacer>
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

    <template v-if="pageTotal">
      <div class="text-xs-center mt-4">
        <v-pagination
          v-model="page"
          :length="pageTotal"
          color="blue"
          circle
          :disabled="paginateDisabled"
          @input="refreshCategorias"
          @next="refreshCategorias"
          @previous="refreshCategorias"
        ></v-pagination>
      </div>
    </template>
    
    <LoadingDialog />

    <AlertNotifications />

  </v-container>
</template>

<script>
  import axios from 'axios';
  import LoadingDialog from '../components/LoadingDialog';
  import LoadingFish from '../components/LoadingFish';
  import PrimaryBox from '../components/PrimaryBox';
  import ErrorMessage from '../components/ErrorMessage';
  import AlertNotifications from '../components/AlertNotifications';

  import { mapState, mapMutations } from 'vuex';

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
        categoriasTotal: 0,
        // Datos para la paginación
        pageTotal: 0,
        page: 1,
        paginateDisabled: false,
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
        // Detail
        nombreDetail: '',
        descripcionDetail: '',
        create: true,
        index: 0,
        id: 0,
        // Datos para detalles de la categoria
        categoriasDetail: false
      }
    },
    methods: {
      // OBTENER CATEOGRIAS
      async getCategorias(){
        try {
          this.loadingTitleMutation('Accediendo a la información');
          this.loadingDialogMutation(true);

          let response = await axios.get(this.url + 'categoria/platillo', this.config);
          if(response.data.data){
            let data = response.data;
            let categorias = data.data;
            if(categorias.length > 0){
              this.categorias = categorias;
              this.messageCategorias = '';
              this.categoriasTotal = data.total;

              if(data.last_page && data.last_page > 1){
                this.pageTotal = data.last_page;
              }
              this.page = 1;
            }else {
              this.messageCategorias = 'No se encontraron categorias';
              this.pageTotal = 0;
            }
            this.headerActionsMutation(true);
          }else {
            this.headerActionsMutation(false);
            this.messageCategorias = response.data.message;
            this.categorias = [];
            this.pageTotal = 0;
          }
         
        } catch (error) {
          this.headerActionsMutation(false);
          this.pageTotal = 0;
          this.messageCategorias = 'Error al conctar con el servidor';
        }finally {
          this.loadingDialogMutation(false);
        }
      },

      // ACTUALIZAR CATEGORIAS
      async refreshCategorias(page = this.page, loadingTitle ='Accediendo a la información', create = false){
        try {
          this.paginateDisabled = true;
          this.loadingTitleMutation(loadingTitle);
          if(!create){
            this.loadingDialogMutation(true);
          }

          let response = await axios.get(this.url + 'categoria/platillo', {
            params: {
              page: this.page
            },
            headers: {
              Authorization: this.config.headers.Authorization,
              'Content-Type': 'application/json'
            }
          });

          let data = response.data;
          let categorias = data.data;
          this.messageCategorias = '';
          this.categorias = categorias;
          if(data.total != this.categoriasTotal){
            this.categoriasTotal = data.total;
          }
          
          if(data.last_page && data.last_page > 1){
            if(data.last_page != this.pageTotal){
              this.pageTotal = data.last_page;
            }
          }
          
          this.headerActionsMutation(true);
        } catch (error) {
          this.headerActionsMutation(false);
          this.pageTotal = 0;
          this.messageCategorias = 'Error al conctar con el servidor';
        }finally {
          this.loadingDialogMutation(false);
          this.paginateDisabled = false;
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
            this.loadingTitleMutation('Subiendo información de la categoria');
            this.createModalMutation(false);
            this.loadingDialogMutation(true);

            let response = await axios.post(this.url + 'categoria/platillo/registrar', {
              nombre: this.nombre,
              descripcion: this.descripcion
            }, this.config);

            this.snackbarMutation({value: true, text: 'Categoria creada correctamente', color: 'success'});

            this.resetForm();

            if(this.categoriasTotal % 10 == 0){
              this.pageTotal++;
            }
            this.page = this.pageTotal;
            await this.refreshCategorias(null, 'Creando Categoria', true);
          }  
        }catch (error) {
          this.loadingDialogMutation(false);
          this.snackbarMutation({value: true, text: 'Ocurrio un error al crear la mesa', color: 'error'});
          this.resetForm();
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
          this.snackbarMutation({value: true, text: 'Ocurrio un erro al editar la categoria', color: 'error'});
        }
      },

      //  ACTIVAR 
      async activarCategoria(index){
        try {
          this.index = index;
          this.id = this.categorias[index].id;
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
        this.$refs.form.resetValidation();
      },
      ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation'])
    },
    computed: {
      ...mapState(['url', 'config', 'loadingFish', 'createModalState'])
    },
    created(){
      this.headerActionsMutation(false);
      this.loadingFishMutation(true);
    },
    async mounted(){
      this.breadcrumbMutation('Categorias');
      await this.getCategorias();
      this.loadingFishMutation(false);
    }
  }
</script>

<style lang="scss" scoped>
  .categoria {
    &-box {
      position: relative;
    }
    &-title {
      position: absolute;
      top: 45%;
      left: 65%;
      transform: translate(-50%, -50%);
      z-index: 2;
      font-size: 24px;
      line-height: 100%;
      font-family: 'Concert One', cursive;
    }
    &-text {
      position: absolute;
      top: 50%;
      left: 4%;
      transform: translateY(-50%);
      color: #fff; 
      font-size: 18px;
      font-family: 'Concert One', cursive;
      pointer-events: none;
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
</style>
