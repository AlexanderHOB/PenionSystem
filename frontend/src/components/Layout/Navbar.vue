<template>
  <v-toolbar
    class="elevation-0"
    dark
    color="cyan darken-2"
  >
    <slot />
  </v-toolbar>
</template>
<style lang="scss" scoped>
.Navbar {
  &-aside {
    background-color: #006274;
    color: #fff;
  }
  &-link-active {
    color: #FFEB3B;
  }
}
</style>
