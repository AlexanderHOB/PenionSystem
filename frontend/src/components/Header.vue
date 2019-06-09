<template>
  <header class="header-bg white--text">
    <v-container fluid class="header">
      <v-layout row wrap class="header-layout">
        <v-flex xs4 class="hidden-md-and-down">
          <v-img 
            :src="require('../assets/iconos/icono-14.svg')"
            contain
            width="50"
            height="50"
            class="testeando"
            :class="{'testeando-active': !mini}"
            @click.stop="miniInteractive"
          ></v-img>
        </v-flex>
        <v-flex xs4 class="hidden-lg-and-up">
          <v-img 
            :src="require('../assets/iconos/icono-14.svg')"
            contain
            width="50"
            height="50"
            @click="drawerMutation(!drawer)"
          ></v-img>
        </v-flex>
        <v-flex xs4 class="text-xs-center">
          <p>El Peñon \ Inicio</p>
        </v-flex>
        <v-flex xs4 class="text-xs-right">
          <p>Admin</p>
        </v-flex>
      </v-layout>
      <v-layout row wrap class="header-layout">
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
      <v-layout class="header-waves">
        <v-flex xs12>
          <v-img
            :src="require('../assets/img/header/wave.png')"
            contain
            height="80"
            class="header-waves-img"
          ></v-img>
        </v-flex>
      </v-layout>
    </v-container>
  </header>
</template>

<script>
import { mapState, mapMutations } from 'vuex';

export default {
  name: 'Header',
  data() {
    return {
      
    }
  },
  methods: {
    ...mapMutations(['drawerMutation', 'miniMutation']),
    miniInteractive(){
      this.miniMutation(!this.mini);
    }
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
        this.miniMutation(value);
      }
    }
  }
}
</script>

<style lang="scss" scoped>
  .header {
    position: relative;
    padding: 20px 85px;
    &-bg {
      @extend %bg-primary;
    }
    &-layout {
      position: relative;
      z-index: 2;
    }
    &-waves {
      position: absolute;
      bottom: 0;
      left: 0;
      z-index: 1;
      height: 80px;
      width: 100%;
      opacity: .75  ;
    }
  }

  .testeando {
    transform: rotateY(0);
    transition: 1s;
    &:hover {
      // transform: rotateY(180deg);
    }
    &-active {
      transform: rotateY(180deg);
    }
  }
</style>

