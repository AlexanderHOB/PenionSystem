<template>
  <LoadingFish v-if="loadingFish" />

  <v-container fluid grid-list-xl v-else>
    <v-layout row wrap class="pt-3">
      <v-flex xs12 v-show="messagePlatillos">
        <ErrorMessage :errorMessage="messagePlatillos" :refresh="refreshPlatillos" />
      </v-flex>
      <v-flex xs8 v-show="!messagePlatillos">
        <v-layout row wrap>
          <v-flex xs12>
            <v-layout justify-space-around>
              <v-flex xs4>
                <v-select
                  v-model="categoria"
                  :items="categorias"
                  item-text="nombre"
                  item-value="id"
                  label="Categorias"
                  :disabled="disabledCategories"
                  return-object
                ></v-select>
              </v-flex>
              <v-flex xs5>
                <v-text-field
                  v-model="searchQuery"
                  solo
                  label="Buscar Platillo..."
                  color="blue"
                  append-icon="search"
                  :disabled="searchDisabled"
                ></v-text-field>
              </v-flex>
            </v-layout>
          </v-flex>
          <v-flex xs12 v-show="searchMessage">
            <p class="text-center">{{ searchMessage }}</p>
          </v-flex>
          <v-flex v-for="(platillo, i) of platillos" :key="platillo.id" class="platillo" xs4>
            <mozo-platillo-box class="platillo-bg" :addPlatillo="addPlatillo" :index="i" />
            <p class="platillo-text text-center">{{ platillo.nombre }}</p>
            <span class="platillo-add">+</span>
          </v-flex>
        </v-layout>
      </v-flex>
      <v-flex xs4 v-show="!messagePlatillos">
        <v-card>
          <v-card-title class="pb-5">
            <v-spacer></v-spacer>
            <h2>PEDIDO</h2>
            <v-spacer></v-spacer>
          </v-card-title>
          <v-card-text class="black--text">
            <v-layout row wrap>
              <v-flex xs4><strong>N° pedido</strong></v-flex>
              <v-flex xs8>12345</v-flex>
              <v-flex xs4><strong>Mozo</strong></v-flex>
              <v-flex xs8>luisMozo</v-flex>
              <v-flex xs4><strong>Mesa</strong></v-flex>
              <v-flex xs8>1</v-flex>
              <v-flex xs12>
                <v-data-table
                  :headers="headers"
                  :items="ordenes"
                  class="elevation-1"
                >
                  <template v-slot:item.action="{ item }">
                    <div class="text-center px-0">
                      <img src="../../assets/img/mozo/eliminar.svg" alt="aumentar" class="actions" @click="removePlatillo(item.id)">
                      <img src="../../assets/img/mozo/aumentar.svg" alt="aumentar" class="actions" @click="increasePlatillo(item.id)">
                      <img src="../../assets/img/mozo/disminuir.svg" alt="aumentar" class="actions" @click="decreasePlatillo(item.id)">
                    </div>
                  </template>
                </v-data-table>
              </v-flex>
            </v-layout>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn class="yellow" rounded>Comanda</v-btn>
            <v-spacer></v-spacer>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>

    <div class="text-center" v-show="filtered">
      <v-btn dark color="blue" @click="showAll">Mostrar Todos</v-btn>
    </div>

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
import axios from 'axios';
import LoadingDialog from '../../components/loading/LoadingDialog';
import LoadingFish from '../../components/loading/LoadingFish';
import ErrorMessage from '../../components/messages/ErrorMessage';
import AlertNotifications from '../../components/messages/AlertNotifications';
import MozoPlatilloBox from '../../components/box/MozoPlatilloBox';
import { mapState, mapMutations, mapActions } from 'vuex';

export default {
  components: {
    LoadingDialog,
    LoadingFish,
    ErrorMessage,
    AlertNotifications,
    MozoPlatilloBox
  },
  data(){
    return {
      // Datos para las platillos
      messagePlatillos: '',
      platillos: [],
      allPlatillos: [],
      isLoadBtn: false,
      refresh: false,
      // Backup
      backup: {
        platillos: [],
        platillosIndex: true,
        pageTotal: 0
      },
      // Search data
      searchQuery: '',
      searchDisabled: true,
      searchMessage: '',
      // Datos para las categorias
      categorias: [],
      categoria: {},
      disabledCategories: true,
      // Filtered
      filtered: false,
      // Datos para la paginación
      pagination: 10,
      pageTotal: 0,
      page: 1,
      // Datos para los pedidos
      headers: [
        { text: 'Actions', align: 'center', sortable: false, value: 'action' },
        { text: 'Pedido', align: 'center', width: 120, sortable: false, value: 'platillo' },
        { text: 'Cant', align: 'center', class: 'px-0', value: 'cantidad' },
        { text: 'Valor', align: 'center', class: 'px-0', value: 'valor' },
      ],
      ordenes: []
    }
  },
  methods: {
    // OBTENER PLATILLOS
    async getPlatillos(){
      try {
        if(this.backup.platillos.length != 0){
          this.searchQuery = ''
          return
        }
        this.loadingTitleMutation('Actualizando información');
        this.loadingDialogMutation(true);

        if(this.allPlatillosState.length == 0 || this.refresh){
          await this.allPlatillosAction();
          this.refresh = false;
        }
        
        if(this.allPlatillosState.data){
          let platillos = this.allPlatillosState.data;
          if(platillos.length > 0){
            this.allPlatillos = platillos.filter(function(e){
              return e.condicion
            });
            this.page = 1;
            this.paginate();
            this.searchDisabled = false;
            this.messagePlatillos = '';
          }else {
            this.messagePlatillos= 'No se encontraron platillos';
            this.pageTotal = 0;
          }
        }else {
          this.messagePlatillos = response.data.message;
          this.platillos = [];
          this.pageTotal = 0;
        }
        
      }catch (error) {
        this.pageTotal = 0;
        this.messagePlatillos = 'Error al conctar con el servidor';
      }finally {
        this.loadingDialogMutation(false);
      }
    },

    refreshPlatillos(){
      if(this.refreshUI) {
        this.refreshUIMutation(false);
      }
      this.refresh = true;
      this.getPlatillos();
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

        if(this.searchMessage.length != 0){
          this.searchMessage = '';
        }

        this.platillos = this.allPlatillos.filter(function(e){
          return  e.nombre.toLowerCase().includes(query.toLowerCase());
        });
        if(this.platillos.length == 0){
          this.searchMessage = `No se encontraron platillos con el nombre "${query}"`;
        }
        this.pageTotal = 0;
      }else {
        if(this.backup.platillos.length != 0){
          this.platillos =this.backup.platillos;
          this.backup.platillos = [];
          this.searchMessage = '';
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
        if(this.allCategoriasState.length == 0){
          await this.allCategoriasAction();
        }
        if(this.allCategoriasState.data){
          let categorias = this.allCategoriasState.data;
          if(categorias.length > 0){
              this.categorias = categorias.filter(function(e){
              return e.condicion
            });
          }
          this.disabledCategories = false;
        }
      } catch (error) {
        this.snackbarMutation({value: true, text: 'Error al obtener las categorias', color: 'error'});
      }
    },

    platillosFiltered(categoria) {
      this.filtered = true;
      this.pageTotal = 0;
      const platillos = this.allPlatillos.filter(e => {
        return categoria.id === e.categoria_id 
      })
      this.platillos = platillos
    },

    showAll() {
      this.categoria = null
      this.filtered = false
      this.page = 1
      this.paginate()
    },

    addPlatillo(index){
      const platillo = this.platillos[index]
      let pedido = null
      if(this.ordenes.length) {
        const pedidoIndex = this.ordenes.findIndex(e => e.id === platillo.id)
        if(pedidoIndex !== -1) {
          this.ordenes[pedidoIndex].cantidad = this.ordenes[pedidoIndex].cantidad + 1
          return
        }
      }

      pedido = {
        id: platillo.id,
        platillo: platillo.nombre,
        msg: 'poca cantidad de sal',
        cantidad: 1,
        valor: platillo.precio
      }

      this.ordenes.push(pedido)
    },

    increasePlatillo(id) {
      const pedidoIndex = this.getIndex(id, this.ordenes)
      this.ordenes[pedidoIndex].cantidad = this.ordenes[pedidoIndex].cantidad + 1
    },

    decreasePlatillo(id) {
      const pedidoIndex = this.getIndex(id, this.ordenes)
      if(this.ordenes[pedidoIndex].cantidad === 1) {
        this.removePlatillo(id, pedidoIndex)
        return
      }
      this.ordenes[pedidoIndex].cantidad = this.ordenes[pedidoIndex].cantidad - 1
    },

    removePlatillo(id, index) {
      let pedidoIndex = index
      if(!pedidoIndex) {
        pedidoIndex = this.getIndex(id, this.ordenes)
      }

      this.ordenes.splice(pedidoIndex, 1)
    },

    getIndex(id, arr) {
      return arr.findIndex(e => e.id === id)
    },

    ...mapMutations(['loadingDialogMutation', 'loadingFishMutation', 'loadingTitleMutation', 'breadcrumbMutation', 'snackbarMutation', 'refreshUIMutation']),
    ...mapActions(['allPlatillosAction', 'allCategoriasAction'])
  },
  computed: {
    ...mapState(['url', 'config', 'loadingFish', 'allPlatillosState', 'allCategoriasState', 'refreshUI'])
  },
  watch: {
    searchQuery(){
      if(this.searchQuery) {
        this.disabledCategories = true
      }else {
        this.disabledCategories = false
      }
      this.searchPlatillos(this.searchQuery)
    },
    categoria(){
      if(this.categoria) {
        this.platillosFiltered(this.categoria)
      }
    },
    refreshUI() {
      if(this.refreshUI) {
        this.refreshPlatillos()
      }
    }
  },
  async created(){
    this.loadingFishMutation(true);
    this.getCategorias();
    await this.getPlatillos();
    this.loadingFishMutation(false);
  },
  beforeMount(){
    this.breadcrumbMutation('Mozo \\ Menu');
  }
}
</script>

<style lang="scss" scoped>
.platillo {
  position: relative;
  font-family: 'Concert One', cursive;
  &-text {
     position: absolute;
    top: 45%;
    left: 40%;
    transform: translateY(-50%);
    font-size: 16px;
    width: 45%;
    line-height: 100%;
  }
  &-add {
    position: absolute;
    top: 45%;
    left: 11%;
    transform: translateY(-50%);
    color: #fff;
    font-size: 64px;
    pointer-events: none;
  }
}
.actions {
  cursor: pointer;
  display: inline-block;
  width: 32px;
}
</style>

