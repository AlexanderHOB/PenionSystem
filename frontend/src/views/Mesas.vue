<template>
  <v-container grid-list-lg>
    <v-btn dark @click="getMesas" class="ml-0">Actualizar mesas</v-btn>
    <v-btn color="green white--text" @click="crearMesaModal = true">Crear mesa</v-btn>
    <v-layout row wrap  mt-3>
      <v-flex xs12 v-if="messageMesas">
        <p>{{ messageMesas }}</p>
      </v-flex>
      <template v-else>
        <v-flex v-for="(mesa, index) of mesas" :key="mesa.id" xs12 sm6 md4 lg3>
          <v-card>
            <v-img
            :src="require('../assets/img/mesas/mesaOcupada.svg')"
            contain
            height="100"
            class="mesa"
            ></v-img>
            <v-card-title class="pt-0">
                <h2>
                  {{ mesa.id }}
                </h2>
            </v-card-title>
            <v-card-text class="py-0">
              <p><strong>Capacidad: {{ mesa.capacidad }} {{ mesa.capacidad === 1 ? 'persona' : 'personas' }}</strong></p>
              <p>{{ mesa.descripcion }}</p>
            </v-card-text>
            <v-card-actions>
              <!-- <v-btn color="blue" class="ml-0" flat>Ver Mesa</v-btn> -->
              <v-btn color="green" flat @click="editarMesaModal(index)">Editar Mesa</v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </template>
    </v-layout>

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

   <v-dialog v-model="crearMesaModal" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Crear Mesa</span>
        </v-card-title>
        <v-card-text>
          <v-form>
            <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-text-field color="blue" label="Número de mesa" type="number" autofocus required v-model="numero"></v-text-field>
              </v-flex> 
              <v-flex xs12>
                <v-text-field color="blue" label="Capacidad" type="number" required v-model="capacidad"></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-textarea
                  color="blue"
                  label="Descripción"
                  auto-grow
                  required
                  clearable
                  v-model="descripcion"
                ></v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click="crearMesaModal = false">Close</v-btn>
          <v-btn v-if="create" color="blue darken-1" flat @click="crearMesa">Save</v-btn>
          <v-btn v-else color="blue darken-1" flat @click="editarMesa">Edit</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <Loading :loading="loading" />

  </v-container>
</template>

<script>
import axios from 'axios';
import Loading from '../components/Loading';

export default {
  components: {
    Loading
  },
  data:() => ({
    // Data para la conexión a la API
    // url: 'http://127.0.0.1:8000/api/',
    // url: 'http://192.168.1.13:8000/api/',
    url: 'http://192.168.1.8:8000/api/',

config: {
        headers: {
          Authorization: '$2y$10$atNNB9MLMCVmT1O9nG4PkugiTsDwtPoFe2uLwC0Lsrf.q0GUFCXgK'
        }
    },
    // Data para las mesas
    messageMesas: '',
    mesas: [],
    // Data para la paginación
    pageTotal: 0,
    page: 1,
    paginateDisabled: false,
    // Data para crear mesa
    crearMesaModal: false,
    // Data del Loading
    loading: {
      state: false,
      title: 'Accediendo a la información' 
    },
    // Data para editar Mesa
    numero: null,
    capacidad: null,
    descripcion: '',
    create: true,
  }),
  methods: {
    // OBTENER MESAS
    async getMesas(){
      try {
        this.loading.state = true;

        let response = await axios.get(this.url + 'mesa', this.config);

        console.log(response);
        
        // if(response.data.data){
        //   let data = response.data;
        //   let mesas = data.data;
        //   if(mesas.length > 0){
        //     this.mesas = mesas;
        //     this.messageMesas = '';
        //     if(data.last_page){
        //       this.pageTotal = data.last_page;
        //     }
        //   }else {
        //     this.messageMesas = 'No existen mesas creadas';
        //   }
        // }else {
        //   this.messageMesas = response.data.message;
        //   this.mesas = [];
        // }
      } catch (error) {
        console.log('error');

        this.messageMesas = 'Error al conctar con el servidor';
      }finally {
        this.loading.state = false;
      }
    },

    // ACTUALIZANDO MESAS
    async refreshMesas(){
      try {
        this.paginateDisabled = true;
        this.loading.state = true;
        let response = await axios.get(this.url + 'mesa', {
        params: {
          page: this.page
        },
        headers: {
          Authorization: this.config.headers.Authorization
        }
      });
        let mesas = response.data.data;
        this.messageMesas = '';
        this.mesas = mesas;
      } catch (error) {
        this.messageMesas = 'Error al conctar con el servidor';
      }finally {
        this.loading.state = false;
        this.paginateDisabled = false;
      }
    },
    // CREAR MESAS
    async crearMesa(){
      try {
        let response = await axios.post(this.url + 'mesa/registrar', {
          numero: this.numero,
          capacidad: this.capacidad,
          descripcion: this.descripcion
        }, this.config);
        // console.log(response.data);

        await this.getMesas();

      } catch (error) {
          console.log(error);
      }finally {
        this.crearMesaModal = false;
        this.numero = null;
        this.capacidad = null;
        this.descripcion = '';
      }
    },

    // Modal para editar mesa
    editarMesaModal(index){
      this.numero = this.mesas[index].numero;
      this.capacidad = this.mesas[index].capacidad;
      this.descripcion = this.mesas[index].descripcion;
      this.index = index;
      this.id = this.mesas[index].id;
      this.create = false;
      this.crearMesaModal = true;
    },

    // Editar mesa
    async editarMesa(){
      try {
        let response = await axios.post(this.url + 'mesa/actualizar/' + this.id, {
          numero: this.numero,
          capacidad: this.capacidad,
          descripcion: this.descripcion
        }, this.config);
        // console.log(response.data);

        this.mesas[this.index].numero = this.numero;
        this.mesas[this.index].capacidad = this.capacidad;
        this.mesas[this.index].descripcion = this.descripcion;
      } catch (error) {
          console.log(error);
      }finally {
        this.crearMesaModal = false;
        this.numero = null;
        this.capacidad = null;
        this.descripcion = '';
      }
    }
  },

  // AL crear la instancia de vue
  mounted(){
    this.getMesas();
  }
}
</script>

<style lang="scss" scoped>
  .mesa {
    transform: translateY(25px);
  }
</style>