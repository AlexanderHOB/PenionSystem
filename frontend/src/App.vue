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
  // text-shadow: 0 0 2px #068FD2;
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

// Sanckbar
.v-snack__wrapper {
  border-radius: 4px !important;
  &.success {
    box-shadow: 0 12px 20px -10px rgba(76,175,80,.28),
                        0 4px 20px 0 rgba(0,0,0,.12),
                        0 7px 8px -5px rgba(76,175,80,.2) !important;
  }
  &.error {
    box-shadow: 0 12px 20px -10px rgba(244,67,54,.28),
                        0 4px 20px 0 rgba(0,0,0,.12),
                        0 7px 8px -5px rgba(244,67,54,.2)!important;
  }
}
</style>

