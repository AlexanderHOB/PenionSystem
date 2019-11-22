<template>
  <div>
    <header-layout />

    <navbar-layout>
      <v-spacer />
      <v-menu offset-y>
        <template v-slot:activator="{ on }">
          <v-btn
            text
            class="Navbar-link-orden"
            v-on="on"
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
      >
        Menú
      </v-btn>
      <v-spacer />
      <v-menu offset-y>
        <template v-slot:activator="{ on }">
          <v-btn
            text
            class="Navbar-link-reservaciones"
            v-on="on"
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
            class="Navbar-link-eventos"
            v-on="on"
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
            class="Navbar-link-cajero"
            v-on="on"
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
    </navbar-layout>

    <v-content>
      <router-view />
    </v-content>
  </div>
</template>

<script>
import HeaderLayout from '@/components/Layout/Header'
import NavbarLayout from '@/components/Layout/Navbar'

export default {
  components: {
    HeaderLayout,
    NavbarLayout
  }
}
</script>
