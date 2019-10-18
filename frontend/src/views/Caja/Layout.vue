<template>
  <div>
    <header class="Header elevation-3">
      <v-container class="d-flex align-center justify-space-between">
        <div class="Header-logo-box">
          <img src="@/assets/logo.svg" alt="logo">
        </div>

        <div class="Header-breadcrumb py-2 px-4">
          <h1 class="Header-breadcrumb-title title"><span class="Header-breadcrumb-base">EL peñon \</span> {{ cajaBreadcrumb }}</h1>
        </div>

        <div class="Header-avatar">
          LuisCajero
          <!-- <v-avatar class="mx-2" color="grey lighten-3"> -->
          <v-avatar class="mx-2" color="blue lighten-1">
            <v-icon dark>person</v-icon>
          </v-avatar>
          <v-btn icon small>
            <v-icon>keyboard_arrow_down</v-icon>
          </v-btn>
        </div>   
      </v-container>   
    </header>

    <v-toolbar
      class="elevation-0"
      dark
      color="cyan darken-2"
    >
      <v-spacer></v-spacer>
      <v-toolbar-items>
        <v-btn text>Orden</v-btn>
        <v-btn text>Menú</v-btn>
        <v-btn text>Reservaciones</v-btn>
        <v-btn text>Eventos</v-btn>
        <v-menu offset-y>
          <template v-slot:activator="{ on }">
            <v-btn
              text
              v-on="on"
              class="Navbar-link-cajero"
            >
              Caja
            </v-btn>
          </template>
          <v-list nav>
            <v-list-item :to="{name: 'cajeroMesasLibres'}">
              <v-list-item-title>Mesas Libres</v-list-item-title>
            </v-list-item>
            <v-list-item :to="{name: 'cajeroMesasOcupadas'}">
              <v-list-item-title>Mesas Ocupadas</v-list-item-title>
            </v-list-item>
            <v-list-item>
              <v-list-item-title>Mesas Globales</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-toolbar-items>
      <v-spacer></v-spacer>
    </v-toolbar>

    <main>
      <router-view />
    </main>
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'

export default {
  methods: {
    activeClass () {
      const path = this.$route.path
      const verifyCajero = path.search('cajero', path)

      if (verifyCajero !== -1) {
        const cajero = document.querySelector('.Navbar-link-cajero')
        cajero.classList.add('Navbar-link-active')
      }
    },
    ...mapMutations(['cajaBreadcrumbMutation'])
  },
  computed: {
    ...mapState(['cajaBreadcrumb'])
  },
  created () {
    this.cajaBreadcrumbMutation('Cajero')
  },
  mounted () {
    this.activeClass()
  }
}
</script>

<style lang="scss" scoped>
.Header {
  position: relative;
  z-index: 99;
  &-logo {
    &-box {
      width: 80px;
    }
  }
  &-breadcrumb {
    background-color: #f5f5f5;
    border-radius: 80px;
    min-width: 30%;
    &-title {
      font-weight: normal;
    }
    &-base {
      color: #ccc;
    }
  }
}
.Navbar {
  &-aside {
    background-color: #006274;
    color: #fff;
  }
  &-link-active {
    color: #FFEB3B;
  }
}
</style>