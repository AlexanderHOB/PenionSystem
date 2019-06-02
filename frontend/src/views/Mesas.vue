<template>
  <v-container grid-list-lg>
    <v-btn dark @click="getMesas" class="ml-0">Actualizar mesas</v-btn>
    <v-btn color="green white--text" @click="crearMesaModal = true">Crear mesa</v-btn>
    <v-layout row wrap  mt-3>
      <v-flex xs12 v-if="empty">
        <p>No existen mesas creadas</p>
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
              <p>{{ mesa.descripcion }}</p>
            </v-card-text>
            <v-card-actions>
              <v-btn color="blue" class="ml-0" flat>Ver Mesa</v-btn>
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
          <v-btn v-if="form" color="blue darken-1" flat @click="crearMesa">Save</v-btn>
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
  data:()=>({
    url: 'http://192.168.1.8:5050/api/',
    empty: true,
    mesas: [],
    pageTotal: 0,
    page: 1,
    crearMesaModal: false,
    loading: {
      state: false,
      title: 'Accediendo a la información' 
    },
    numero: null,
    capacidad: null,
    descripcion: '',
    form: true,
  }),
  components: {
    Loading
  },
  methods: {
    async getMesas(){
      // this.loading.state = true;
      // let data = await axios.get(this.url + 'mesa');
      // let paginate = data.data.pagination;
      // let mesas = data.data.mesas.data;
      // if(mesas.length > 0){
      //   this.pageTotal = paginate.last_page;
      //   this.mesas = mesas;
      //   this.empty = false;
      // }else {
      //   this.empty = true;
      // }
      this.loading.state = false;
    },
    async refreshMesas(){
      this.loading.state = true;
      let data = await axios.get(this.url + 'mesa?page=' + this.page);
      let mesas = data.data.mesas.data;
      this.mesas = mesas;
      this.loading.state = false;
    },
    async crearMesa(){
      await axios.post(this.url + 'mesa/registrar', {
        numero: this.numero,
        capacidad: this.capacidad,
        descripcion: this.descripcion
      })
      .then(function (response) {
        console.log(response.data);
      })
      .catch(function (error) {
        console.log(error);
      });
      this.getMesas();
      this.crearMesaModal = false;
      this.numero = null;
      this.capacidad = null;
      this.descripcion = '';
    },
    editarMesaModal(index){
      this.numero = this.mesas[index].numero;
      this.capacidad = this.mesas[index].capacidad;
      this.descripcion = this.mesas[index].descripcion;
      this.index = index;
      this.id = this.mesas[index].id;
      this.form = false;
      this.crearMesaModal = true;
    },
    async editarMesa(){
      await axios.post(this.url + 'mesa/actualizar/' + this.id, {
        numero: this.numero,
        capacidad: this.capacidad,
        descripcion: this.descripcion
      })
      .then(function (response) {
        console.log(response.data);
      })
      .catch(function (error) {
        console.log(error);
      });
      this.mesas[this.index].numero = this.numero;
      this.mesas[this.index].capacidad = this.capacidad;
      this.mesas[this.index].descripcion = this.descripcion;
      this.crearMesaModal = false;
      this.numero = null;
      this.capacidad = null;
      this.descripcion = '';
    }
  },
  created(){
      this.getMesas();
  }
}
</script>

<style lang="scss" scoped>
  .mesa {
    transform: translateY(25px)
  }
</style>