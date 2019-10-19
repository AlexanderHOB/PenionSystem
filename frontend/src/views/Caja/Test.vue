<template>
  <v-container>
    <v-row class="text-center">
      <v-col cols="12">
        <v-card dark color="blue">
          <v-card-text>
            12
          </v-card-text>
        </v-card>
      </v-col>

      <v-col v-for="n in xl" :key="n" cols="6">
        <v-card dark color="blue lighten-3">
          <v-card-text>
            6
          </v-card-text>
        </v-card>
      </v-col>

      <v-col v-for="n in lg" :key="n" cols="4">
        <v-card dark color="blue">
          <v-card-text>
            4
          </v-card-text>
        </v-card>
      </v-col>

      <v-col v-for="n in md" :key="n" cols="3">
        <v-card dark color="blue lighten-3">
          <v-card-text>
            3
          </v-card-text>
        </v-card>
      </v-col>

      <v-col v-for="n in sm" :key="n" cols="2">
        <v-card dark color="blue">
          <v-card-text>
            2
          </v-card-text>
        </v-card>
      </v-col>

      <v-col v-for="n in xs" :key="n" cols="1">
        <v-card dark color="blue lighten-3">
          <v-card-text>
            1
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data () {
    return {
      xl: [1, 2],
      lg: [3, 4, 5],
      md: [6, 7, 8, 9],
      sm: [10, 11, 12, 13, 14, 15],
      xs: [16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27]
    }
  }
}
</script>