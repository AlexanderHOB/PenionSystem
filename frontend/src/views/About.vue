<template>
  <div>
    <h1>{{ title }}</h1>
  </div>
</template>

<script>
import axios from 'axios';
import { mapState } from 'vuex';

export default {
  data() {
    return {
      title: 'About'
    }
  },
  methods: {
    async get(){
      let response = await axios.get(this.url + 'categoria/platillo', this.config);

      console.log(response)
      
    }
  },
  computed: {
    ...mapState(['url', 'config'])
  },

  mounted(){
    this.get();
  }
}
</script>
