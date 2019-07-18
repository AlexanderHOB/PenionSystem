<template>

  <LoadingFish v-if="loadingFish" />

  <v-container v-else grid-list-xl>
    <v-layout row wrap>
      <v-flex xs12 v-if="messagePlatillos">
        <ErrorMessage :errorMessage="messagePlatillos" :refresh="getPlatillos" />
      </v-flex>

      <template v-else>
        <v-flex xs12 class="d-flex align-center">
          <h1 class="display-1">{{ title }}</h1>
          <div class="text-xs-right">
            <v-btn class="blue" dark fab small @click="getPlatillos"><v-icon>replay</v-icon></v-btn>
          </div>
        </v-flex>
        <v-flex xs4 v-for="(platillo, i) of platillos" :key="platillo.id" class="mb-4">
          <div class="platillo-box">
           <div class="platillo-img-box">
             <img src="../assets/img/platillos/Festival-De-Trucha.jpg" alt="" class="platillo-img">
           </div>
           <svg class="clipPath-svg">
            <clipPath id="clipPath-img">
                <path d="M227.69,40.43l-14.41-27.81C209.16,4.67,202.77,0,195.99,0H24.51C10.45,4.99,0.05,22.71,0.05,43.77v54.66
        c0,0.53-0.01,1.05-0.05,1.57h196.16c4.89,0,9.58-2.43,13.36-6.77c1.38-1.6,2.66-3.45,3.76-5.53l14.32-26.97
        C230.76,54.78,230.8,46.44,227.69,40.43z"/>
            </clipPath>
            </svg>
            <h2 class="platillo-title text-xs-center">{{ platillo.nombre }}</h2>
            <PrimaryBox class="platillo-bg" :active="platillo.condicion" :activeInteraction="activarModal" :bgBox="bgBox" :bgBackBox="bgBackBox" :index="i" />
            <div class="platillo-textBox text-xs-center">
              <p class="mb-0 platillo-text" v-show="platillo.condicion">Activo</p> 
              <p class="mb-0 platillo-text" v-show="!platillo.condicion">Inactivo</p>           
            </div>
            <img src="../assets/iconos/edit.svg" alt="edit" class="platillo-edit" @click="editarPlatilloModal(i)">
            <img src="../assets/iconos/detail.svg" alt="detail" class="platillo-detail" @click="detailPlatilloModal(i)">
          </div>
        </v-flex>
      </template>
    </v-layout>

    <v-dialog v-model="createModalState" persistent max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 v-show="create" class="headline title-modal">Crear Platillo</h3>
          <h3 v-show="!create" class="headline title-modal">Editar Platillo</h3>
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
                    label="Nombre del platillo"
                    v-model="nombre"
                    :rules="[rules.required, rules.counterNombre]"
                    counter="30"
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6>
                  <v-text-field 
                    color="blue"
                    label="Codigo del platillo"
                    v-model="codigo"
                    :rules="[rules.required, rules.counterCodigo]"
                    counter="5"
                    :disabled="disabled"
                  ></v-text-field>
                </v-flex>
                <v-flex xs12 sm6>
                  <v-select
                    color="blue"
                    :rules="[rules.required]"
                    v-model="area"
                    :items="areas"
                    label="área"
                    :disabled="disabled"
                  ></v-select>
                </v-flex>
                <v-flex xs12 sm6>
                  <v-select
                    color="blue"
                    v-model="categoria"
                    :rules="[rules.required]"
                    :items="categorias"
                    item-text="nombre"
                    item-value="id"
                    label="Categoria"
                    :disabled="disabled"
                    return-object
                  ></v-select>
                </v-flex>
                <v-flex xs12 sm6>
                  <v-text-field
                    color="blue"
                    label="Precio del platillo"
                    v-model="precio"
                    :rules="[rules.required, rules.positive]"
                    prefix="$"
                    type="number"
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
                    counter="70"
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
            <v-btn :disabled="!valid" v-show="create" color="green darken-1" flat @click="crearPlatillo" :loading="isLoadBtn">Crear</v-btn>
            <v-btn :disabled="!valid" v-show="!create" color="green darken-1" flat @click="editarPlatillo" :loading="isLoadBtn">Editar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="platillosDetail" max-width="600px">
      <v-card>
        <v-card-title>
          <v-spacer></v-spacer>
          <h3 class="headline">{{ nombreDetail }}</h3>
          <v-spacer></v-spacer>
          <v-btn icon color="blue--text" @click="closeDetailModal">
            <v-icon>close</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text class="pb-0">
          <p> <strong>Codigo:</strong> {{ codigoDetail }}</p>
          <p> <strong>Área:</strong> {{ areaDetail }}</p>
          <p> <strong>Precio:</strong> $ {{ precioDetail }}</p>
          <p> <strong>Categoria:</strong> {{ nombreCategoriaDetail }}</p>
          <p> <strong>Descripción:</strong> {{ descripcionDetail }}</p>
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
          <h3 class="headline title-modal ">Confirmación</h3>
          <v-spacer></v-spacer>
        </v-card-title>
        <v-card-text>Confirma que quieres <strong>{{ activarText }}</strong> el platillo</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" flat @click="activeDialog = false">Cancelar</v-btn>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" flat @click="activarPlatillo(index)">Aceptar</v-btn>
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

import { mapState, mapMutations } from 'vuex';

export default {
  components: {
    LoadingDialog,
    LoadingFish,
    PrimaryBox,
    ErrorMessage,
    AlertNotifications
  },
  data(){
    return {
      title: 'Platillos',
      bgBox: '#ebe03b',
      bgBackBox: '#ebd40a',
      // Datos para las platillos
      messagePlatillos: '',
      platillos: [],
      allPlatillos: [],
      isLoadBtn: false,
      disabled: false,
      // Backup
      backup: {
        platillos: [],
        platillosIndex: true,
        pageTotal: 0
      },
      // Datos para las categorias
      categorias: [],
      // Datos para la paginación
      pagination: 10,
      pageTotal: 0,
      page: 1,
      // Datos para valdiar formulario
      valid: true,
      rules: {
        required: value => !!value || 'Required.',
        counterNombre: value => value.length <= 30 || '30 caracteres como maximo',
        counterCodigo: value => value.length <= 5 || '5 caracteres como maximo',
        counterDescripcion: value => value.length <= 70 || '70 caracteres como maximo',
        positive: value => value >= 0 || 'Solo valores positivos',

      },
      // Data para editar Categoria
      codigo: '', 
      nombre: '',
      area: '',
      precio: null,
      categoria: null,
      descripcion: '',
      create: true,
      index: 0,
      id: 0,
      // Deatail
      codigoDetail: '', 
      nombreDetail: '',
      areaDetail: '',
      precioDetail: '',
      nombreCategoriaDetail: '',
      descripcionDetail: '',
      // Datos para detalles de la categoria
      platillosDetail: false,
      // Datos de area
      areas: ['Área Mixta', 'Área Fría', 'Área Caliente'],
      // Datos para activar/desactivar el modal
      activeDialog: false,
      activarText: ''
    }
  },
  methods: {
    // OBTENER PLATILLOS
    async getPlatillos(){
      try {
        if(this.backup.platillos.length != 0){
          this.searchQueryMutation('');
          return;
        }
        this.loadingTitleMutation('Actualizando información');
        this.loadingDialogMutation(true);

        let response = await axios.get(this.url + 'platillos', this.config);
        if(response.data){
          let platillos = response.data;
          if(platillos.length > 0){
            this.allPlatillos = platillos;
            this.page = 1;
            this.paginate();
            this.messagePlatillos = '';
          }else {
            this.messagePlatillos= 'No se encontraron platillos';
            this.pageTotal = 0;
          }
          this.searchDisabledMutation(false);
          this.headerActionsMutation({create: true, report: false});
        }else {
          this.headerActionsMutation({create: false, report: false});
          this.messagePlatillos = response.data.message;
          this.platillos = [];
          this.pageTotal = 0;
          this.searchDisabledMutation(true);
        }
        
      }catch (error) {
        this.headerActionsMutation({create: false, report: false});
        this.searchDisabledMutation(true);
        this.pageTotal = 0;
        this.messagePlatillos = 'Error al conctar con el servidor';
      }finally {
        this.loadingDialogMutation(false);
      }
    },

    // PAGINAR PLATILLOS
    paginate(){
      if(this.allPlatillos.length > this.pagination){
        this.platillos = this.allPlatillos.slice(((this.pagination * this.page) - this.pagination), (this.pagination * this.page));
        this.pageTotal = Math.ceil(this.allPlatillos.length / this.pagination);
      }else {
         this.platillos = this.allPlatillos;
          this.pageTotal = 0;
      }
    },

    // SEARCH PLATILLOS
    searchPlatillos(query){
      if(query != '' && query != null){
        if(this.platillos.length != 0 && this.backup.platillosIndex){
          this.backup.platillos = this.platillos;
          if(this.pageTotal != 0){
            this.backup.pageTotal = this.pageTotal;
          }
          this.backup.platillosIndex = false;
        }

        if(this.messagePlatillos.length != 0){
          this.messagePlatillos = '';
        }

        this.platillos = this.allPlatillos.filter(function(e){
          return  e.nombre.toLowerCase().search(query.toLowerCase()) != -1;
        });
        if(this.platillos.length == 0){
          this.messagePlatillos = 'No se encontraron platillos con el nombre ' + query;
        }
        this.pageTotal = 0;
      }else {
        if(this.backup.platillos.length != 0){
          this.platillos =this.backup.platillos;
          this.backup.platillos = [];
          this.messagePlatillos = '';
          if(this.backup.pageTotal != 0){
            this.pageTotal = this.backup.pageTotal;
            this.backup.pageTotal = 0;
          }
          this.backup.platillosIndex = true;
        }
      }
    },

    // OBTENER CATEGORIAS
    async getCategorias(){
        try {
          let response = await axios.get(this.url + 'categoria/platillo/selectCategoria', this.config);

          if(response.data){
            let categorias = response.data;
            if(categorias.length > 0){
              this.categorias = categorias;
            }
          }
        } catch (error) {
          this.snackbarMutation({value: true, text: 'Error al obtener las categorias', color: 'error'});
        }
      },

    // MODAL DE DETALLE
    detailPlatilloModal(index){
      this.codigoDetail = this.platillos[index].codigo;
      this.nombreDetail = this.platillos[index].nombre;
      this.areaDetail = this.platillos[index].area;
      this.precioDetail = this.platillos[index].precio;
      this.nombreCategoriaDetail = this.platillos[index].categoria.nombre;
      this.descripcionDetail = this.platillos[index].descripcion;
      this.platillosDetail = true;
    },

    // CERRAR MODAL DE DETALLE
    closeDetailModal(){
      this.platillosDetail = false;
      let self = this;
      setTimeout(function(){
        self.codigoDetail = '';
        self.nombreDetail = '';
        self.areaDetail = '';
        self.precioDetail = '';
        self.nombreCategoriaDetail = '';
        self.descripcionDetail = '';
      }, 100);
    },

    // MODAL PARA EDITAR PLATILLO
    editarPlatilloModal(index){
      console.log(this.platillos[index]);
      this.codigo = this.platillos[index].codigo;
      this.nombre = this.platillos[index].nombre;
      this.area = this.platillos[index].area;
      this.precio = this.platillos[index].precio;
      this.categoria = this.platillos[index].categoria;
      this.descripcion = this.platillos[index].descripcion;
      this.index = index;
      this.id = this.platillos[index].id;
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
      this.codigo = '';
      this.nombre = '';
      this.area = '';
      this.precio = null;
      this.categoria = null;
      this.descripcion = '';
      this.create = true;
      this.$refs.form.resetValidation();
    },

    // CREAR PLATILLO
    async crearPlatillo(){
      try {
        if (this.$refs.form.validate()) {
          this.isLoadBtn = true;
          this.disabled = true;
          if(this.descripcion == '' || this.descripcion == null){
            this.descripcion = 'Platillo sin descripción';
          }
          this.precio = parseFloat(parseFloat(this.precio).toFixed(2));
          console.log(this.categoria);
          let response = await axios.post(this.url + 'platillo/registrar', {
            categoria_id: this.categoria.id,
            codigo: this.codigo,
            nombre: this.nombre,
            area: this.area,
            precio: this.precio,
            descripcion: this.descripcion
          }, this.config);

          this.closeModal();

          if(this.pageTotal == 0 && this.backup.pageTotal != 0){
              this.pageTotal = this.backup.pageTotal;
          }

          if(this.allPlatillos.length % 10 === 0){
            this.pageTotal++;
          }

          if(response.data){
            this.allPlatillos.push(response.data);
            this.snackbarMutation({value: true, text: 'Platillo creado correctamente', color: 'success'});
            this.page = this.pageTotal;
            this.paginate();
            this.backup.platillos = [];
            this.messagePlatillos = '';
            this.backup.platillosIndex = true;
          }else{
            this.snackbarMutation({value: true, text: 'Ocurrio un error al crear el paltillo', color: 'error'});
          }
        }
      } catch (error) {
        this.closeModal();
        this.snackbarMutation({value: true, text: 'Ocurrio un error en el servidor', color: 'error'});
      }finally {
        this.isLoadBtn = false;
        this.disabled = false;
      }
    },

    async editarPlatillo(){
      try {
        if (this.$refs.form.validate()) {
          if(this.descripcion == '' || this.descripcion == null){
            this.descripcion = 'Platillo sin descripción';
          }

          var categoriaBup = this.categoria.id;
          let codigoBup = this.codigo;
          let nombreBup = this.nombre;
          let areaBup = this.area;
          let precioBup = parseFloat(parseFloat(this.precio).toFixed(2));
          let descripcionBup = this.descripcion;

          this.closeModal();

          var categoriaJsonBup = this.categorias.filter(e => e.id == categoriaBup);

          this.platillos[this.index].categoria = categoriaJsonBup[0];
          this.platillos[this.index].codigo = codigoBup;
          this.platillos[this.index].nombre = nombreBup;
          this.platillos[this.index].area = areaBup;
          this.platillos[this.index].precio = precioBup;
          this.platillos[this.index].descripcion = descripcionBup;

          let response = await axios.put(this.url + 'platillo/actualizar/' + this.id, {
            categoria_id: categoriaBup,
            codigo: codigoBup,
            nombre: nombreBup,
            area: areaBup,
            precio: precioBup,
            descripcion: descripcionBup
          }, this.config);
          this.snackbarMutation({value: true, text: 'Platillo editado correctamente', color: 'success'});
        }
      }catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un erro al editar la platillo', color: 'error'});
      }
    },

    // MODAL PARA ACTIVAR
    activarModal(index){
        this.index = index;
        if(this.platillos[index].condicion){
          this.activarText = 'desactivar';
        }else{
          this.activarText = 'activar';
        }
        this.activeDialog = true;
    },

    //  ACTIVAR 
    async activarPlatillo(index){
      try {
        this.id = this.platillos[index].id;
        this.activeDialog = false;
        if(this.platillos[index].condicion){
          this.platillos[index].condicion = 0;

          let response = await axios.put(this.url + 'platillo/desactivar/' + this.id, {},this.config);
          this.snackbarMutation({value: true, text: 'Platillo desactivada correctamente', color: 'success'});
        }else {
          this.platillos[index].condicion = 1;

          let response = await axios.put(this.url + 'platillo/activar/' + this.id, {}, this.config);
          this.snackbarMutation({value: true, text: 'Platillo activada correctamente', color: 'success'});
        }
      } catch (error) {
        this.snackbarMutation({value: true, text: 'Ocurrio un error', color: 'error'});
      }
    },

    // ACCION DEL FORMULARIO
    async formAction(){
      if(this.create){
        await this.crearPlatillo();
      }else {
        await this.editarPlatillo();
      }
    },
    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'createModalMutation', 'headerActionsMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation', 'searchQueryMutation', 'searchPlaceholderMutation', 'searchDisabledMutation'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'createModalState', 'searchQuery'])
  },
  watch: {
    searchQuery(){
      this.searchPlatillos(this.searchQuery);
    }
  },
  async created(){
    this.getCategorias();
    this.loadingFishMutation(true);
    await this.getPlatillos();
    this.loadingFishMutation(false);
  },
  beforeMount(){
    this.headerActionsMutation({create: false, report: false});
    this.breadcrumbMutation('Platillos');
    this.searchPlaceholderMutation('Nombre del platillo...');
    this.searchDisabledMutation(true);
  }
}
</script>

<style lang="scss" scoped>
.clipPath-svg {
  width: 0;
  height: 0;
}
.platillo {
  &-img {
    height: 100%;
    display: block;
    object-fit: cover;
  }
  &-img-box {
    position: absolute;
    top: 5%;
    left: 26%;
    width: 75%;
    height: 50%;
    z-index: 0;
    clip-path: url('#clipPath-img');
    overflow: hidden;
  }
  &-bg {
    width: 100%;
    position: relative;
  }
  &-box {
    position: relative;
    padding-top: 80px;
    font-family: 'Concert One', cursive;
  }
  &-title {
    position: absolute;
    top: 67.5%;
    left: 65%;
    transform: translate(-50%, -50%);
    z-index: 2;
    font-size: 24px;
    line-height: 100%;
  }
  &-textBox {
  position: absolute;
  top: 70%;
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
