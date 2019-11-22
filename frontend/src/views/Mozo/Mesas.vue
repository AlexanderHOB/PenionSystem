<template>
  <v-container
    grid-list-xl
  >
    <router-view />
  </v-container>
</template>

<script>
import { mapMutations } from 'vuex'

export default {
  mounted () {
    this.activeClass()
    this.headerBreadcrumbMutation('Mozo \\ Mesas')
  },
  methods: {
    activeClass () {
      const active = document.querySelector('.Navbar-link-active')
      if (active) {
        active.classList.remove('Navbar-link-active')
      }

      const link = document.querySelector('.Navbar-link-mozo-mesas')
      link.classList.add('Navbar-link-active')
    },
    ...mapMutations(['headerBreadcrumbMutation'])
  }

}
</script>

<style lang="scss" scoped>
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
