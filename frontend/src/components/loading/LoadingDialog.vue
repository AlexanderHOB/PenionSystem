<template>
  <v-dialog
    v-model="loadingDialog.state"
    hide-overlay
    persistent
    width="300"
  >
    <v-card dark>
      <v-card-text>
      {{ loadingDialog.title }}
        <v-progress-linear
          indeterminate
          color="white"
          class="mb-0"
          >
        </v-progress-linear>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
  import { mapState } from 'vuex';

  export default {
    name: 'LoadingDialog',
    data() {
      return {
        
      }
    },
    computed: {
      ...mapState(['loadingDialog'])
    },
    
  }
</script>

