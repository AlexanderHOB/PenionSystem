<template>
  <v-snackbar
    v-model="snackbarState.state"
    right
    top
    :color="snackbarState.color"
    :timeout="timeout"
  >
    <v-icon class="mr-3 white--text">add_alert</v-icon>
    {{ snackbarState.text }}
    <v-btn
      icon
      @click="snackbarState.state = false"
    >
      <v-icon>close</v-icon>
    </v-btn>
  </v-snackbar>
</template>

<script>
import { mapState } from 'vuex';

export default {
  name: 'AlertNotifications',
  data() {
    return {
      timeout: 3000
    }
  },
  computed: {
    ...mapState(['snackbarState'])
  }
}

</script>

<style lang="scss" scoped>

</style>
