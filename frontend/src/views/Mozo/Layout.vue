<template>
  <div>
    <header-layout />

    <navbar-layout>
      <v-spacer />
      <v-menu offset-y>
        <template v-slot:activator="{ on }">
          <v-btn
            text
            class="Navbar-link-mozo-mesas"
            v-on="on"
          >
            Mesas
          </v-btn>
        </template>
        <v-list nav>
          <v-list-item :to="{name: 'mozo-mesas-libres'}">
            <v-list-item-title>Mesas Libres</v-list-item-title>
          </v-list-item>
          <v-list-item :to="{name: 'mozo-mesas-ocupadas'}">
            <v-list-item-title>Mesas Ocupadas</v-list-item-title>
          </v-list-item>
          <v-list-item :to="{name: 'mozo-mis-mesas'}">
            <v-list-item-title>Mis mesas</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
      <v-btn
        text
        :to="{name: 'mozo-menu'}"
        active-class="yellow--text"
        class="ml-5"
      >
        Menú
      </v-btn>
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
