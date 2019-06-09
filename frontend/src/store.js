import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    // Sidenav
    drawerState: true,
    miniState: true,
    // Data para la conexión a la API
    // url: 'http://192.168.1.4:8000/api/',
    url: 'http://127.0.0.1:8000/api/',
    config: {
      headers: {
        Authorization: '$2y$10$atNNB9MLMCVmT1O9nG4PkugiTsDwtPoFe2uLwC0Lsrf.q0GUFCXgK'
      }
    },
    // Data del Loading
    loadingDialog: {
      state: false,
      title: '' 
    },
    loadingFish: true,
    // Data  Header
    headerActions: false,
    createModalState: false,
    breadcrumb: ''
  },
  mutations: {
    drawerMutation(state, value){
      state.drawerState = value
    },
    miniMutation(state, value){
      state.miniState = value
    },
    loadingDialogMutation(state, value){
      state.loadingDialog.state = value;
    },
    loadingTitleMutation(state, value){
      state.loadingDialog.title = value;
    },
    loadingFishMutation(state, value){
      state.loadingFish = value;
    },
    headerActionsMutation(state, value){
      state.headerActions = value;
    },
    createModalMutation(state, value){
      state.createModalState = value;
    },
    breadcrumbMutation(state, value){
      state.breadcrumb = value;
    }
  },
  actions: {

  }
})
