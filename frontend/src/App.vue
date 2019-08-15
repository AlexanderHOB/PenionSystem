<template>
  <v-app>
    <template v-if="!token || (token && auth.rol !== 'Administrador')">
      <router-view />
    </template>

    <template  v-if="token && auth.rol === 'Administrador'">
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
      ...mapState(['token', 'auth', 'drawerState', 'miniState']),

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

// Ocultar scrollbar
.v-navigation-drawer__content {
  &::-webkit-scrollbar {
    display: none !important;
  }
}

// Subitems de un group
.v-list-group__items {
  .sidenav-subitems-active {
    color: rgba(0, 0, 0, .87) !important;
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

// Group Active
.theme--light.v-list .group-active .v-list-group__header {
  color: #fff;
  @extend %bg-primary;
  box-shadow: $primary-shadow;
  .v-icon.material-icons.theme--light {
    color: #fff;
  }
}

.v-application .v-list-group--active.primary--text {
  color: rgba(0, 0, 0, .87) !important;
}

// Group Arrow 
.sidenav-group .v-icon.material-icons {
    margin-top: 4px;
}

.theme--light.v-application .sidenav .group-active .v-list-group__header {
  color: #fff !important;
}

// HEADER
.header-waves .v-image__image,
.mozo-header-waves .v-image__image {
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

// MOZO
.mesas-link.router-link-exact-active.router-link-active {
	color: #fff;
  @extend %bg-primary;
  box-shadow: $primary-shadow;
}
</style>

