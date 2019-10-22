<template>
  <div>
    <sidenav />
    <v-content>
      <header-admin />
       <router-view />
    </v-content>
  </div>
</template>

<script>
import Sidenav from '@/components/Sidenav'
import HeaderAdmin from '@/components/Header'

export default {
  components: {
    Sidenav,
    HeaderAdmin
  }
}
</script>

