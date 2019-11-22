<template>
  <header class="Header elevation-3">
    <v-container class="d-flex align-center justify-space-between">
      <div class="Header-logo-box">
        <img
          src="@/assets/logo2.svg"
          alt="logo"
        >
      </div>

      <div class="Header-breadcrumb py-2 px-4">
        <h1 class="Header-breadcrumb-title title">
          <span class="Header-breadcrumb-base">EL peñon \</span> {{ headerBreadcrumb }}
        </h1>
      </div>

      <div class="Header-avatar">
        {{ username }}
        <!-- <v-avatar class="mx-2" color="grey lighten-3"> -->
        <v-avatar
          class="mx-2"
          color="blue lighten-1"
        >
          <v-icon dark>
            person
          </v-icon>
        </v-avatar>
        <v-menu offset-y>
          <template v-slot:activator="{ on }">
            <v-btn
              icon
              small
              v-on="on"
            >
              <v-icon>keyboard_arrow_down</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item
              @click="logoutUI"
            >
              <v-list-item-title>Logout</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
    </v-container>
  </header>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
  data () {
    return {
      username: ''
    }
  },
  computed: {
    ...mapState(['headerBreadcrumb', 'auth'])
  },
  created () {
    const nombre = this.auth.user.nombres.split(' ')
    this.username = nombre[0] + this.auth.user.rol
  },
  methods: {
    async logoutUI () {
      this.logout()
      this.$router.push({ name: 'login' })
    },
    ...mapActions(['logout'])
  }
}
</script>

<style lang="scss" scoped>
.Header {
  position: relative;
  z-index: 99;
  &-logo {
    &-box {
      width: 80px;
    }
  }
  &-breadcrumb {
    background-color: #f5f5f5;
    border-radius: 80px;
    min-width: 30%;
    &-title {
      font-weight: normal;
    }
    &-base {
      color: #ccc;
    }
  }
}
</style>
