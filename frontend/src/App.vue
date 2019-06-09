<template>
  <v-app>
    <Sidenav />
    <v-content>
      <Header />
      <router-view />
    </v-content>
  </v-app>
</template>

<script>
  import Sidenav from './components/Sidenav';
  import Header from './components/Header';
  import { mapState, mapMutations } from 'vuex';

  export default {
    name: 'App',
    components: {
      Sidenav,
      Header
    },
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

<style lang="scss">
  // General
  body {	
    // Scroll
    &::-webkit-scrollbar {
      width: 12px;
      height: 12px;
    }
    &::-webkit-scrollbar-track {
      border-radius: 50px;
      background-color: rgba(0, 0, 0, .05);
    }
    &::-webkit-scrollbar-thumb {
      border-radius: 50px;
      background-color: rgba(0, 0, 0, .25);
    }
    &::-webkit-scrollbar-thumb:hover {
      background-color: rgba(0, 0, 0, .45);
    }
  }
  button:focus {
    outline: none;
  }
  button,
  a {
    @extend %tap-highlight;
  }

  // Sidenav
  .sidenav {
    &-items-active {
      @extend %bg-primary;
      box-shadow: $primary-shadow;
    }
  }
  .theme--light.v-list .sidenav-items-active:hover {
    background-image: $primary-gradient;
  }

  // Header
  .header-waves .v-image__image {
    background-repeat: repeat-x;
  }
</style>

