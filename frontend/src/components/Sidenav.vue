<template>
  <v-navigation-drawer
    v-model="drawer"
    :mini-variant.sync="mini"
    app
    class="elevation-8 sidenav"
  >

    <v-list class="elevation-4 sidenav-logo">
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
        @click="closeGroups"
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
        @click="closeGroups"
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
        @click="closeGroups"
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
      @click="closeGroups"
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

      <v-list-group
        no-action
        v-model="group1"
        :class="{'group-active': group1active}"
      >
        <template v-slot:activator>
          <v-list-tile>
            <v-list-tile-action>
              <v-img 
                :src="require('../assets/iconos/acceso.svg')"
                contain
                height="32"
              ></v-img>
            </v-list-tile-action>
            <v-list-tile-content class="pl-3">
              <v-list-tile-title>Recursos Humanos</v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </template>

        <v-list-tile
          @click="group1Active('.group1')"
          active-class="sidenav-subitems-active"
          ripple
          :to="{name: 'personal'}"
        >
          <v-list-tile-title class="pl-3">Personal</v-list-tile-title>
        </v-list-tile>

        <v-list-tile
          @click=""
        >
          <v-list-tile-title class="pl-3">Adelantos</v-list-tile-title>
        </v-list-tile>

        <v-list-tile
          @click=""
        >
          <v-list-tile-title class="pl-3">Descuentos</v-list-tile-title>
        </v-list-tile>
            
        <v-list-tile
          @click=""
        >
          <v-list-tile-title class="pl-3">Pagos</v-list-tile-title>
        </v-list-tile>
      </v-list-group>

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
        group1: false,
        group1active: false
      }
    },
    methods: {
      closeGroups(){
        this.group1 = false;
        this.group1active = false;
      },
      group1Active(name){
        // document.querySelector(name).classList.add('group-active')
        this.group1active = true;
      },
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
  .sidenav {
    &-logo {
      position: relative;
    }
  }
</style>
