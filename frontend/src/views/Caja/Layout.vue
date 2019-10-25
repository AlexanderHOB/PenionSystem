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
          <v-menu offset-y>
            <template v-slot:activator="{ on }">
              <v-btn icon small v-on="on">
                <v-icon>keyboard_arrow_down</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item
                @click="logoutUI"
              >
                <v-list-item-title>Logout</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </div>   
      </v-container>   
    </header>

    <v-toolbar
      class="elevation-0"
      dark
      color="cyan darken-2"
    >
        <v-spacer />
        <v-menu offset-y>
          <template v-slot:activator="{ on }">
            <v-btn
              text
              v-on="on"
              class="Navbar-link-orden"
            >
              Orden
            </v-btn>
          </template>
          <v-list nav>
            <v-list-item :to="{name: 'buscarOrden'}">
              <v-list-item-title>Bucar Orden</v-list-item-title>
            </v-list-item>
            <v-list-item :to="{name: 'nuevaOrden'}">
              <v-list-item-title>Nueva Orden</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
        <v-spacer />
        <v-btn
        text
        :to="{name: 'caja-menu'}"
        active-class="yellow--text"
        >Menú</v-btn>
        <v-spacer />
        <v-menu offset-y>
          <template v-slot:activator="{ on }">
            <v-btn
              text
              v-on="on"
              class="Navbar-link-reservaciones"
            >
              Reservaciones
            </v-btn>
          </template>
          <v-list nav>
            <v-list-item :to="{name: 'listaReservaciones'}">
              <v-list-item-title>Lista de Reservaciones</v-list-item-title>
            </v-list-item>
            <v-list-item :to="{name: 'reservacion'}">
              <v-list-item-title>Registrar Reservacion</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
        <v-spacer />
        <v-menu offset-y>
          <template v-slot:activator="{ on }">
            <v-btn
              text
              v-on="on"
              class="Navbar-link-eventos"
            >
              Eventos
            </v-btn>
          </template>
          <v-list nav>
            <v-list-item :to="{name: 'listaEventos'}">
              <v-list-item-title>Lista de eventos</v-list-item-title>
            </v-list-item>
            <v-list-item :to="{name: 'evento'}">
              <v-list-item-title>Crear Evento</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
        <v-spacer />
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
          </v-list>
        </v-menu>
        <v-spacer />
      <!-- </v-toolbar-items> -->
    </v-toolbar>

    <main>
      <router-view />
    </main>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
  methods: {
    async logoutUI () {
      this.logout()
      this.$router.push({name: 'login'})
    },
    ...mapActions(['logout'])
  },
  computed: {
    ...mapState(['cajaBreadcrumb'])
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