<template>
  <router-view />
</template>

<script>
import { mapState, mapMutations } from 'vuex'

export default {
  methods: {
    activeClass () {
      const active = document.querySelector('.Navbar-link-active')
      if (active) {
        active.classList.remove('Navbar-link-active')
      }

      const link = document.querySelector('.Navbar-link-reservaciones')
      link.classList.add('Navbar-link-active')
    },
    ...mapMutations(['cajaBreadcrumbMutation'])
  },
  computed: {
    ...mapState(['cajaBreadcrumb'])
  },
  created () {
    this.cajaBreadcrumbMutation('Reservaciones')
  },
  mounted () {
    this.activeClass()
  }
}
</script>
