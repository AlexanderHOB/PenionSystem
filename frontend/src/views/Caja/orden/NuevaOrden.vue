<template>
    <loading-fish v-if="isLoading" />

  <v-container v-else fiuid>
    <header class="d-flex justify-space-between align-center my-3">
      <h3 class="title">Nueva Orden</h3>
      <v-btn
        small
        fab
        dark
        color="blue"
        @click="refresh"
      >
        <v-icon>refresh</v-icon>
      </v-btn>
    </header>

    <alert-notifications />

    <loading-dialog />

  </v-container>
</template>

<script>
import { mapState, mapMutations } from 'vuex'

import LoadingFish from '@/components/loading/LoadingFish'
import AlertNotifications from '@/components/messages/AlertNotifications'
import LoadingDialog from '@/components/loading/LoadingDialog'

export default {
  components: {
    LoadingDialog,
    LoadingFish,
    AlertNotifications
  },
  methods: {
    // get All Platillos
    async getAllPlatillos () {
      try {
        
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Ocurrio un error en el servidor :c',
          color: 'error'
        })
      } finally {
        this.isLoading = false
      }
    },
    async refresh () {
      try {
        this.loadingTitleMutation('Actualizando información');
        this.loadingDialogMutation(true)
      } catch (error) {
        this.snackbarMutation({
          value: true,
          text: 'Ocurrio un error en el servidor :C',
          color: 'error'
        })
      } finally {
        this.loadingDialogMutation(false)
      }
    },
    ...mapMutations(['snackbarMutation', 'loadingTitleMutation', 'loadingDialogMutation'])
  },
  computed: {
    // ...mapState([''])
  },
  created () {
    this.getAllPlatillos()
  }
}
</script>
