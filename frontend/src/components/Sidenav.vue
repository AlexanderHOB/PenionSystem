<template>
  <v-navigation-drawer
    v-model="drawer"
    :mini-variant.sync="mini"
    app
    class="elevation-8 sidenav"
  >

    <v-list class="elevation-4 sidenav-logo">
      <v-list-item>
        <v-img 
          :src="require('../assets/logo.svg')"
          contain
          height="55"
        >
        </v-img>
      </v-list-item>
    </v-list>

    <v-list dense class="pt-0">
      <v-list-item
        @click="closeGroups"
        :to="{name: 'home'}"
        active-class="white--text sidenav-items-active"
        ripple
        exact
      >
        <v-list-item-icon class="">
          <v-img 
            :src="require('../assets/iconos/home.svg')"
            contain
            height="32"
            width="32"
          ></v-img>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title class="sidenav-item-title">Inicio</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        @click="closeGroups"
        :to="{name: 'mesas'}"
        active-class="white--text sidenav-items-active"
        ripple
      >
        <v-list-item-icon class="my-2">
          <v-img 
            :src="require('../assets/iconos/administrador.svg')"
            contain
            height="32"
            width="32"
          ></v-img>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title class="sidenav-item-title">Mesas</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
        
      <v-list-item
        @click="closeGroups"
        :to="{name: 'categorias'}"
        active-class="white--text sidenav-items-active"
        ripple
      >
        <v-list-item-icon>
          <v-img 
            :src="require('../assets/iconos/almacen.svg')"
            contain
            height="32"
            width="32"
          ></v-img>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title class="sidenav-item-title">Categorias</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        @click=""
        active-class="white--text sidenav-items-active"
        ripple
      >
        <v-list-item-icon>
          <v-img 
            :src="require('../assets/iconos/compras.svg')"
            contain
            height="32"
            width="32"
          ></v-img>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title class="sidenav-item-title">Compras</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
      @click="closeGroups"
        active-class="white--text sidenav-items-active"
        :to="{name: 'platillos'}"
        ripple
      >
        <v-list-item-icon>
          <v-img 
            :src="require('../assets/iconos/platillos.svg')"
            contain
            height="32"
            width="32"
          ></v-img>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title class="sidenav-item-title">Platillos</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        @click=""
        active-class="white--text sidenav-items-active"
        ripple
      >
        <v-list-item-icon>
          <v-img 
            :src="require('../assets/iconos/ventas.svg')"
            contain
            height="32"
            width="32"
          ></v-img>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title class="sidenav-item-title">Ventas</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-group
        no-action
        v-model="group1"
        class="sidenav-group"
        :class="{'group-active': group1active}"
      >
        <template v-slot:activator>
            <v-list-item-icon>
              <v-img 
                :src="require('../assets/iconos/acceso.svg')"
                contain
                height="32"
                width="32"
              ></v-img>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title class="sidenav-item-title">Recursos Humanos</v-list-item-title>
            </v-list-item-content>
        </template>

        <v-list-item
          @click="group1Active('.group1')"
          active-class="sidenav-subitems-active"
          ripple
          :to="{name: 'personal'}"
        >
          <v-list-item-title class="pl-3">Personal</v-list-item-title>
        </v-list-item>

        <v-list-item
          @click="group1Active('.group1')"
          active-class="sidenav-subitems-active"
          ripple
          :to="{name: 'usuarios'}"
        >
          <v-list-item-title class="pl-3">Usuarios</v-list-item-title>
        </v-list-item>

        <v-list-item
          @click="group1Active('.group1')"
          active-class="sidenav-subitems-active"
          ripple
          :to="{name: 'adelantos'}"
        >
          <v-list-item-title class="pl-3">Adelantos</v-list-item-title>
        </v-list-item>

        <v-list-item
          @click="group1Active('.group1')"
          active-class="sidenav-subitems-active"
          ripple
          :to="{name: 'descuentos'}"
        >
          <v-list-item-title class="pl-3">Descuentos</v-list-item-title>
        </v-list-item>
            
        <v-list-item
          @click="group1Active('.group1')"
          active-class="sidenav-subitems-active"
          ripple
          :to="{name: 'pagos'}"
        >
          <v-list-item-title class="pl-3">Pagos</v-list-item-title>
        </v-list-item>
      </v-list-group>

      <v-list-item
        @click=""
        active-class="white--text sidenav-items-active"
        ripple
      >
        <v-list-item-icon>
          <v-img 
            :src="require('../assets/iconos/reportes.svg')"
            contain
            height="32"
            width="32"
          ></v-img>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title class="sidenav-item-title">Reportes</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        @click=""
        active-class="white--text sidenav-items-active"
        ripple
      >
        <v-list-item-icon>
          <v-img 
            :src="require('../assets/iconos/soportes.svg')"
            contain
            height="32"
            width="32"
          ></v-img>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title class="sidenav-item-title">Soporte</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
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
    &-item {
      &-title {
        font-size: 14px !important;
      }
    }
  }
</style>
