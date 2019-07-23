<template>
  <v-container>
    <div class="text-xs-center pt-5 mt-5">
      <h1 class="blue--text notFound-title">404 Error</h1>
      <p class="notFound-text">No se pudo encontrar la pagina que buscas</p>
      <v-btn round large class="blue white--text" :to="{name: 'home'}">Volver</v-btn>
    </div>
  </v-container>
</template>

<script>

export default {
  name: 'NotFound'
}
</script>

<style lang="scss" scoped>
 .notFound {
   &-title {
     font-size: 48px;
     font-weight: normal;
   }
   &-text {
     font-size: 20px;
   }
}
</style>


