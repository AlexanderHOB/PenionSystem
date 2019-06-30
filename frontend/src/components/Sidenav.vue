<template>
  <v-navigation-drawer
    v-model="drawer"
    :mini-variant.sync="mini"
    app
    class="elevation-8"
  >

    <v-list>
      <v-list-tile>
        <v-img 
          :src="require('../assets/logo.svg')"
          contain
          height="55"
        >
        </v-img>
      </v-list-tile>
    </v-list>

    <v-list class="pt-0">
      <v-list-tile
        :to="{name: 'home'}"
        active-class="white--text sidenav-items-active"
        exact
        ripple
      >
        <v-list-tile-action>
          <v-img 
            :src="require('../assets/iconos/home.svg')"
            contain
            height="32"
          ></v-img>
        </v-list-tile-action>
        <v-list-tile-content class="pl-3">
          <v-list-tile-title>Inicio</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile
        @click=""
        :to="{name: 'mesas'}"
        active-class="white--text sidenav-items-active"
        ripple
      >
        <v-list-tile-action>
          <v-img 
            :src="require('../assets/iconos/administrador.svg')"
            contain
            height="32"
          ></v-img>
        </v-list-tile-action>
        <v-list-tile-content class="pl-3">
          <v-list-tile-title>Mesas</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
        
      <v-list-tile
        @click=""
        :to="{name: 'categorias'}"
        active-class="white--text sidenav-items-active"
        ripple
      >
        <v-list-tile-action>
          <v-img 
            :src="require('../assets/iconos/almacen.svg')"
            contain
            height="32"
          ></v-img>
        </v-list-tile-action>
        <v-list-tile-content class="pl-3">
          <v-list-tile-title>Categorias</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile
        @click=""
        active-class="white--text sidenav-items-active"
        ripple
      >
        <v-list-tile-action>
          <v-img 
            :src="require('../assets/iconos/compras.svg')"
            contain
            height="32"
          ></v-img>
        </v-list-tile-action>
        <v-list-tile-content class="pl-3">
          <v-list-tile-title>Compras</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile
        active-class="white--text sidenav-items-active"
        :to="{name: 'platillos'}"
        ripple
      >
        <v-list-tile-action>
          <v-img 
            :src="require('../assets/iconos/platillos.svg')"
            contain
            height="32"
          ></v-img>
        </v-list-tile-action>
        <v-list-tile-content class="pl-3">
          <v-list-tile-title>Platillos</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile
        @click=""
        active-class="white--text sidenav-items-active"
        ripple
      >
        <v-list-tile-action>
          <v-img 
            :src="require('../assets/iconos/ventas.svg')"
            contain
            height="32"
          ></v-img>
        </v-list-tile-action>
        <v-list-tile-content class="pl-3">
          <v-list-tile-title>Ventas</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile
        @click=""
        active-class="white--text sidenav-items-active"
        ripple
        :to="{name: 'personal'}"
      >
        <v-list-tile-action>
          <v-img 
            :src="require('../assets/iconos/acceso.svg')"
            contain
            height="32"
          ></v-img>
        </v-list-tile-action>
        <v-list-tile-content class="pl-3">
          <v-list-tile-title>Personal</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile
        @click=""
        active-class="white--text sidenav-items-active"
        ripple
      >
        <v-list-tile-action>
          <v-img 
            :src="require('../assets/iconos/reportes.svg')"
            contain
            height="32"
          ></v-img>
        </v-list-tile-action>
        <v-list-tile-content class="pl-3">
          <v-list-tile-title>Reportes</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-list-tile
        @click=""
        active-class="white--text sidenav-items-active"
        ripple
      >
        <v-list-tile-action>
          <v-img 
            :src="require('../assets/iconos/soportes.svg')"
            contain
            height="32"
          ></v-img>
        </v-list-tile-action>
        <v-list-tile-content class="pl-3">
          <v-list-tile-title>Soporte</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>
    </v-list>
  </v-navigation-drawer> 
</template>

<script>
  import { mapState, mapMutations } from 'vuex';

  export default {
    name: 'Sidenav',
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

<style lang="scss" scoped>
  
</style>
