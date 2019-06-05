<template>
    <v-navigation-drawer
      v-model="drawer"
      :mini-variant.sync="mini"
      app
    >

    <v-list>
        <v-list-tile>
          <v-img 
            :src="require('../assets/img/adminView/logo.png')"
            contain
            height="55"
            >
          </v-img>
        </v-list-tile>
    </v-list>

      <v-list class="pt-0">
        <v-list-tile
          :to="{name: 'home'}"
          active-class="primary-gradient active-shadow"
          exact
          ripple
        >
        <v-list-tile-action>
          <v-img 
            :src="require('../assets/img/adminView/iconos/icono-05.svg')"
            contain
            height="32"
            >
          </v-img>
        </v-list-tile-action>
        <v-list-tile-content class="pl-3">
          <v-list-tile-title>Inicio</v-list-tile-title>
        </v-list-tile-content>
      </v-list-tile>

        <v-list-tile
          @click=""
          :to="{name: 'mesas'}"
          active-class="primary-gradient active-shadow"
          ripple
        >
          <v-list-tile-action>
            <v-img 
              :src="require('../assets/img/adminView/iconos/icono-06.svg')"
              contain
              height="32"
              >
            </v-img>
          </v-list-tile-action>
          <v-list-tile-content class="pl-3">
            <v-list-tile-title>Mesas</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        
        <v-list-tile
          @click=""
          :to="{name: 'about'}"
          active-class="primary-gradient active-shadow"
          ripple
        >
          <v-list-tile-action>
            <v-img 
              :src="require('../assets/img/adminView/iconos/icono-07.svg')"
              contain
              height="32"
              >
            </v-img>
          </v-list-tile-action>
          <v-list-tile-content class="pl-3">
            <v-list-tile-title>Almacen</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile
          @click=""
          active-class="primary-gradient"
          ripple
        >
          <v-list-tile-action>
            <v-img 
              :src="require('../assets/img/adminView/iconos/icono-08.svg')"
              contain
              height="32"
              >
            </v-img>
          </v-list-tile-action>
          <v-list-tile-content class="pl-3">
            <v-list-tile-title>Compras</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile
          @click=""
          active-class="primary-gradient"
          ripple
        >
          <v-list-tile-action>
            <v-img 
              :src="require('../assets/img/adminView/iconos/icono-09.svg')"
              contain
              height="32"
              >
            </v-img>
          </v-list-tile-action>
          <v-list-tile-content class="pl-3">
            <v-list-tile-title>Platillos</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile
          @click=""
          active-class="primary-gradient"
          ripple
        >
          <v-list-tile-action>
            <v-img 
              :src="require('../assets/img/adminView/iconos/icono-10.svg')"
              contain
              height="32"
              >
            </v-img>
          </v-list-tile-action>
          <v-list-tile-content class="pl-3">
            <v-list-tile-title>Ventas</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile
          @click=""
          active-class="primary-gradient"
          ripple
        >
          <v-list-tile-action>
            <v-img 
              :src="require('../assets/img/adminView/iconos/icono-11.svg')"
              contain
              height="32"
              >
            </v-img>
          </v-list-tile-action>
          <v-list-tile-content class="pl-3">
            <v-list-tile-title>Acceso</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile
          @click=""
          active-class="primary-gradient"
          ripple
        >
          <v-list-tile-action>
            <v-img 
              :src="require('../assets/img/adminView/iconos/icono-12.svg')"
              contain
              height="32"
              >
            </v-img>
          </v-list-tile-action>
          <v-list-tile-content class="pl-3">
            <v-list-tile-title>Reportes</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-list-tile
          @click=""
          active-class="primary-gradient"
          ripple
        >
          <v-list-tile-action>
            <v-img 
              :src="require('../assets/img/adminView/iconos/icono-13.svg')"
              contain
              height="32"
              >
            </v-img>
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
  name: 'AdminView',
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
