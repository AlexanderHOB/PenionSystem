<template>
  <router-view />
</template>

<script>
import { mapState, mapMutations } from 'vuex'

export default {
  created () {
    this.headerBreadcrumbMutation('Cajero')
  },
  mounted () {
    this.activeClass()
  },
  methods: {
    activeClass () {
      const active = document.querySelector('.Navbar-link-active')
      if (active) {
        active.classList.remove('Navbar-link-active')
      }

      const link = document.querySelector('.Navbar-link-cajero')
      link.classList.add('Navbar-link-active')
    },
    ...mapMutations(['headerBreadcrumbMutation'])
  }
}
</script>
