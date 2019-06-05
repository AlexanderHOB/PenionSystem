<template>
  <v-app>
    <AdminView />
    <v-content>
      <Header />
      <router-view />
    </v-content>
  </v-app>
</template>

<script>
import AdminView from './components/AdminView';
import Header from './components/Header';
import { mapState, mapMutations } from 'vuex';

export default {
  name: 'App',
  components: {
    AdminView,
    Header
  },
  data () {
    return {
      
    }
  },
  methods: {
    ...mapMutations(['drawerMutation', 'miniMutation'])
  },
  computed: {
    ...mapState(['drawerState', 'miniState']),
    drawer: {
      get() {
        return this.drawerState
      },
      set(value) {
        this.drawerMutation(value)
      }
    },
    mini: {
      get() {
        return this.miniState
      },
      set(value) {
        this.miniMutation(value)
      }
    }
  }
    
}
</script>

<style lang="scss">
body {	
	// Scroll
	&::-webkit-scrollbar {
		width: 12px;
		height: 12px;
	}
	&::-webkit-scrollbar-track {
		border-radius: 50px;
		background-color: rgba(0, 0, 0, .05);
	}
	&::-webkit-scrollbar-thumb {
		border-radius: 50px;
		background-color: rgba(0, 0, 0, .25);
	}
	&::-webkit-scrollbar-thumb:hover {
		background-color: rgba(0, 0, 0, .45);
	}
}
  button:focus {
    outline: none;
  }
  button,
  a {
    @extend %tap-highlight;
  }
  .theme--light.v-list .primary-gradient:hover {
      background: $primary-gradient;
  }
  .primary-gradient {
    background-image: $primary-gradient;
    color: $light;
  }
  .active-shadow {
    box-shadow: $primary-shadow;
  }
  .adminView-waves .v-image__image {
    background-repeat: repeat-x;
  }

   .testeando {
    transform: rotateY(0);
    transition: .5s;
    &:hover {
      // transform: rotateY(180deg);
    }
  }

  .adminView {
    position: relative;
    padding: 20px 85px;
    &-layout {
      position: relative;
      z-index: 2;
    }
    &-waves {
      position: absolute;
      bottom: 0;
      left: 0;
      z-index: 1;
      height: 80px;
      width: 100%;
      opacity: .75  ;
    }
  }
</style>

