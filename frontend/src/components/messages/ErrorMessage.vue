<template>
  <v-flex xs12>
        <p class="text-center display-1">{{ errorMessage }} - Actualizar<v-btn class="blue ml-3" dark fab @click="refresh"><v-icon>replay</v-icon></v-btn></p>
      </v-flex>
</template>

<script>
export default {
  name: 'errorMessage',
  props: [
    'errorMessage',
    'refresh'
  ]
}
</script>
