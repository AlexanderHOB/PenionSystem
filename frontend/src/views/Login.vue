<template>
  <div class="login">
    <v-container>
      <h1>Login</h1>
    </v-container>
  </div>
</template>

<script>
import { mapState } from 'vuex'

export default {
computed: {
  ...mapState(['token'])
},
}
</script>

<style lang="scss" scoped>
.login {
  position: fixed;
  z-index: 99999999999999;
  width: 100%;
  height: 100vh;
  background-color: #fff;
}
</style>
