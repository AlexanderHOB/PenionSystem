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
  *,
  *:before,
  *:after {
    box-sizing: border-box;
    scroll-behavior: smooth;
  }
  ::selection {
    // background-color: transparent;
    // text-shadow: 0 0 2px #ff0;
  }
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
  img {
    width: 100%;
    display: block;
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
  .v-navigation-drawer[data-booted="true"]{
        // transition: .2s cubic-bezier(0.17, .67, 0.64, .18);
  }
</style>

