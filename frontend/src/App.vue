<template>
  <v-app>
    <template v-if="token !== 'token'">
      <router-view />
    </template>

    <template  v-else>
      <Sidenav />
      <v-content>
        <Header />
        <router-view />
      </v-content>
    </template>
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
      ...mapState(['token', 'drawerState', 'miniState']),

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

// SIDENAV
.sidenav {
  // Scroll
  &::-webkit-scrollbar {
    display: none;
  }
  &-items-active {
    @extend %bg-primary;
    box-shadow: $primary-shadow;
  }
  &-subitems-active {
    ::before {
      background-color: #212121;
    }
  }
}

// Sidenav Mini
.v-navigation-drawer--mini-variant {
  // Mover subitems en mini
  .v-list__group__items--no-action .v-list__tile {
      ::before {
        transform: translateX(-60px);
      }
  }

  // Ocultar flecha de los groups en mini
  .v-list__group__header__append-icon {
    display: none;
  }
}

// Subitems de un group
.v-list__group__items--no-action .v-list__tile {
  // Desactivando overflow hidden
  .v-list__tile__title {
    overflow: initial;
  }
  // Icons de los subitems de un group
  ::before {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    border: 1px solid #212121;
    border-radius: 50%;
    transform: translateX(-55px);
  }
}

// Hover para item activado
.theme--light.v-list .sidenav-items-active:hover {
  @extend %bg-primary;
}

// Ocultando dividers
.v-list__group.v-list__group--active::before {
  display: none;
}

// Group Active
.theme--light.v-list .group-active .v-list__group__header {
  color: #fff;
  @extend %bg-primary;
  box-shadow: $primary-shadow;
  .v-icon.material-icons.theme--light {
    color: #fff;
  }
}

// HEADER
.header-waves .v-image__image {
  background-repeat: repeat-x;
}

// SNACKBAR
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

.v-dialog.v-dialog--active {
    // Scroll
  &::-webkit-scrollbar {
    width: 12px;
    height: 12px;
  }
  &::-webkit-scrollbar-track {
    background-color: #fff;
  }
  &::-webkit-scrollbar-thumb {
    border-radius: 50px;
    background-color: rgba(0, 0, 0, .25);
  }
}
</style>

