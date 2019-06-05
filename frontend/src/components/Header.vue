<template>
  <div class="primary-gradient">
        <v-container fluid class="adminView">
          <v-layout row wrap class="adminView-layout">
            <v-flex xs4 class="hidden-md-and-down" @click.stop="miniMutation(!mini)">
              <v-img 
                :src="require('../assets/img/adminView/iconos/icono-14.svg')"
                contain
                width="50"
                height="50"
                class="testeando"
              ></v-img>
            </v-flex>
            <v-flex xs4 @click="drawerMutation(!drawer)" class="hidden-lg-and-up">
              <v-img 
                :src="require('../assets/img/adminView/iconos/icono-14.svg')"
                contain
                width="50"
                height="50"
              ></v-img>
            </v-flex>
            <v-flex xs4 class="text-xs-center">
              <p>El Peñon \ Inicio</p>
            </v-flex>
            <v-flex xs4 class="text-xs-right">
              <p>Admin</p>
            </v-flex>
          </v-layout>
          <v-layout row wrap class="adminView-layout">
            <v-flex xs4>
              <p>Buscar</p>
            </v-flex>
            <v-flex xs4>
              <p>Nuevo</p>
            </v-flex>
            <v-flex xs4>
              <p>Reporte</p>
            </v-flex>
          </v-layout>
          <v-layout class="adminView-waves">
            <v-flex xs12>
              <v-img
                :src="require('../assets/img/adminView/wave.png')"
                contain
                height="80"
                class="adminView-waves-img"
              ></v-img>
            </v-flex>
          </v-layout>
        </v-container>
      </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex';

export default {
  name: 'Header',
  data () {
    return {
      
    }
  },
  methods: {
    ...mapMutations(['drawerMutation', 'miniMutation'])
  },
  computed: {
    ...mapState(['drawerState', 'miniState']),
    drawer: {
      get() {
        return this.drawerState
      },
      set(value) {
        this.drawerMutation(value)
      }
    },
    mini: {
      get() {
        return this.miniState
      },
      set(value) {
        this.miniMutation(value)
      }
    }
  }
}
</script>
