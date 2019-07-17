import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    token: 'token',
    // Sidenav
    drawerState: true,
    miniState: true,
    // Data para la conexión a la API
    // url: 'http://192.168.1.2:8000/api/',
    url: 'http://127.0.0.1:8000/api/',
    config: {
      headers: {
        Authorizations: '$2y$10$atNNB9MLMCVmT1O9nG4PkugiTsDwtPoFe2uLwC0Lsrf.q0GUFCXgK',
        'Content-Type': 'application/json'
      }
    },
    // Data del Loading
    loadingDialog: {
      state: false,
      title: '' 
    },
    loadingFish: true,
    // Data  Header
    headerActionCreate: false,
    headerActionReport: false,
    createModalState: false,
    breadcrumb: '',
    // Data de SNackbar
    snackbarState: {
      state: false,
      text: '',
      color: ''
    },
    // Data de search
    searchPlaceholder: 'Texto a buscar...',
    searchQuery: '',
    searchDisabled: true
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
    headerActionsMutation(state, object){
      state.headerActionCreate = object.create;
      state.headerActionReport = object.report;
    },
    createModalMutation(state, value){
      state.createModalState = value;
    },
    breadcrumbMutation(state, value){
      state.breadcrumb = value;
    },
    snackbarMutation(state, object){
      state.snackbarState.state = object.value;
      state.snackbarState.text = object.text;
      state.snackbarState.color = object.color;
    },
    searchQueryMutation(state, value){
      state.searchQuery = value;
    },
    searchPlaceholderMutation(state, value){
      state.searchPlaceholder = value;
    },
    searchDisabledMutation(state, value){
      state.searchDisabled = value;
    },
  },
  actions: {

  },
  getters: {
    getToken(state){
      return state.token;
    }
  }
})
