<template>
    <v-container>
    <v-layout
      text-xs-center
      wrap
    >
      <v-flex xs12>
        <v-img
          :src="require('../assets/logo.svg')"
          class="my-3"
          contain
          height="200"
          @click="title = 'Title Change'"
        ></v-img>
      </v-flex>

      <v-flex mb-4>
        <h1 class="display-2 font-weight-bold mb-3">
          {{ title }}
        </h1>
        <p class="subheading font-weight-regular">
          For help and collaboration with other developers,
          <br>please join our online
          <a href="https://community.vuetifyjs.com" target="_blank">Discord Community</a>
        </p>
      </v-flex>

    </v-layout>
  </v-container>
</template>

<script>
  import { mapMutations } from 'vuex';

  export default {
    data(){
      return {
        title: 'El Peñon'
      }
    },
    methods: {
      ...mapMutations(['headerActionsMutation', 'breadcrumbMutation'])
    },
    created(){
      this.headerActionsMutation({create: false, report: false});
    },
    mounted(){
      this.breadcrumbMutation('Inicio');
    }
  }
</script>
