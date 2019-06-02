<template>
  <v-app>
        <!-- <v-btn flat :to="{name: 'home'}" exact>Home</v-btn> -->
        <!-- <v-btn flat :to="{name: 'mesas'}">Mesas</v-btn> -->

    <v-content>
      <Sidenav />
      <router-view/>
    </v-content>
  </v-app>
</template>

<script>
import Sidenav from './components/Sidenav';

export default {
  name: 'App',
  data () {
    return {
      //
    }
  },
  components: {
    Sidenav
  }
}
</script>
