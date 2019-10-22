<template>
    <v-container>
    <v-layout
      wrap
    >
      <v-flex xs12>
        <v-img
          :src="require('@/assets/logo.svg')"
          class="my-3"
          contain
          height="200"
          @click="title = 'Title Change'"
        ></v-img>
      </v-flex>

      <v-flex mb-4>
        <h1 class="display-2 font-weight-bold pt-3 text-center">{{ title }}</h1>
      </v-flex>

    </v-layout>
  </v-container>
</template>

<script>
  import { mapMutations, mapState } from 'vuex';

  export default {
    data(){
      return {
        title: 'El Peñon'
      }
    },
    methods: {
      ...mapMutations(['headerActionsMutation', 'breadcrumbMutation', 'searchPlaceholderMutation', 'searchDisabledMutation'])
    },
    mounted(){
      this.headerActionsMutation({create: false, report: false});
      this.searchPlaceholderMutation('Texto a buscar...');
      this.searchDisabledMutation(true);
      this.breadcrumbMutation('Inicio');
    }
  }
</script>
