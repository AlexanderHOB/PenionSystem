<template>
    <v-container fluid grid-list-xl>
      <v-layout row wrap class="text-center py-3">
        <v-flex xs1></v-flex>
        <v-flex xs2>
          <router-link class="mesas-link" :to="{name: 'mozo-mesas-libres'}">Mesas Libres</router-link>
        </v-flex>
        <v-flex xs2>
          <router-link class="mesas-link" :to="{name: 'mozo-mesas-ocupadas'}">Mesas ocupadas</router-link>
        </v-flex>
        <v-flex xs2>
          <router-link class="mesas-link" :to="{name: 'mozo-mis-mesas'}">Mis mesas</router-link>
        </v-flex>
      </v-layout>
      <router-view></router-view>
    </v-container>
</template>

<script>
import { mapState, mapMutations } from 'vuex';

export default {
  methods: {
    ...mapMutations(['breadcrumbMutation'])
  },
  mounted(){
    this.breadcrumbMutation('Mozo \\ Mesas');
  }
}
</script>

<style <style lang="scss" scoped>
.mesas {
  &-link {
    color: #000;
    display: block;
    font-size: 20px;
    border-radius: 8px;
    padding: 8px 0;
    box-shadow: 0 0 8px 2px rgba(0, 0, 0, .5); 
    text-decoration: none;
  }
}
</style>
