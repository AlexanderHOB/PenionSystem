<template>
  <HelloWorld />
</template>

<script>
  import HelloWorld from '../components/HelloWorld';
  import { mapMutations } from 'vuex';

  export default {
    components: {
      HelloWorld
    },
    data(){
      return {
        
      }
    },
    methods: {
      ...mapMutations(['headerActionsMutation', 'breadcrumbMutation'])
    },
    created(){
      this.headerActionsMutation(false);
    },
    mounted(){
      this.breadcrumbMutation('Inicio');
    }
  }
</script>
