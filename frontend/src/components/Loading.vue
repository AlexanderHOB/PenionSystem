<template>
  <div>
    <v-dialog
      v-model="loading.state"
      hide-overlay
      persistent
      width="300"
    >
    <v-card dark>
      <v-card-text>
        {{ loading.title }}
        <v-progress-linear
          indeterminate
          color="white"
          class="mb-0"
          >
        </v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
export default {
  props: [
    'loading'
  ],
  name: 'Loading',
  data() {
    return {
      
    }
  }
}
</script>

