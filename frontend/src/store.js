import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    drawerState: true,
    miniState: true,
    // Data para la conexión a la API
    url: 'http://192.168.1.8:8000/api/',
    config: {
      headers: {
        Authorization: '$2y$10$atNNB9MLMCVmT1O9nG4PkugiTsDwtPoFe2uLwC0Lsrf.q0GUFCXgK'
      }
    }
  },
  mutations: {
    drawerMutation(state, value){
      state.drawerState = value
    },
    miniMutation(state, value){
      state.miniState = value
    }
  },
  actions: {

  }
})
