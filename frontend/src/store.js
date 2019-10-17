import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    token: '',
    testApiKey: process.env.VUE_APP_APIKEY,
    auth: {},
    // Sidenav
    drawerState: true,
    miniState: true,
    // Data para la conexión a la API
    // url: 'http://192.168.1.2:8000/api/',
    url: process.env.VUE_APP_API_URL,
    config: {
      headers: {
        Apikey: '$2y$10$atNNB9MLMCVmT1O9nG4PkugiTsDwtPoFe2uLwC0Lsrf.q0GUFCXgK',
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
    searchDisabled: true,
    // Data Layout
    allMesasState: [],
    allCategoriasState: [],
    allPlatillosState: [],
    allPersonalState: [],
    allAdelantosState: [],
    allDescuentosState: [],
    allUsuariosState: [],
    allRolsState: [],
    //
    refreshUI: false,
    // Caja
    asideTitle: '',
    cajaBreadcrumb: ''
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
    tokenMutation(state, value){
      state.token = value;
    },
    authMutation(state, value){
      state.auth = {...value};
    },
    allMesasMutation(state, action){
      state.allMesasState = action;
    },
    allCategoriasMutation(state, action){
      state.allCategoriasState = action;
    },
    allPlatillosMutation(state, action){
      state.allPlatillosState = action;
    },
    allPersonalMutation(state, action){
      state.allPersonalState = action;
    },
    allAdelantosMutation(state, action){
      state.allAdelantosState = action;
    },
    allDescuentosMutation(state, action){
      state.allDescuentosState = action;
    },
    allUsuariosMutation(state, action){
      state.allUsuariosState = action;
    },
    allUsuariosMutation(state, action){
      state.allUsuariosState = action;
    },
    allRolsMutation(state, action){
      state.allRolsState = action;
    },
    refreshUIMutation(state, value) {
      state.refreshUI = value
    },
    asideTitleMutation (state, value) {
      state.asideTitle = value
    },
    cajaBreadcrumbMutation (state, value) {
      state.cajaBreadcrumb = value
    }
  },
  actions: {
    allMesasAction: async function({ state, commit }){
      let response = await axios.get(state.url + 'mesas', state.config);
      commit('allMesasMutation', response)
    },
    allCategoriasAction: async function({ state, commit }){
      let response = await axios.get(state.url + 'categoria/platillos', state.config);
      commit('allCategoriasMutation', response)
    },
    allPlatillosAction: async function({ state, commit }){
      let response = await axios.get(state.url + 'platillos', state.config);
      commit('allPlatillosMutation', response)
    },
    allPersonalAction: async function({ state, commit }){
      let response = await axios.get(state.url + 'empleados', state.config);
      commit('allPersonalMutation', response)
    },
    allAdelantosAction: async function({ state, commit }){
      let response = await axios.get(state.url + 'historial/adelanto', state.config);
      commit('allAdelantosMutation', response)
    },
    allDescuentosAction: async function({ state, commit }){
      let response = await axios.get(state.url + 'historial/descuento', state.config);
      commit('allDescuentosMutation', response)
    },
    allUsuariosAction: async function({ state, commit }){
      let response = await axios.get(state.url + 'users', state.config);
      commit('allUsuariosMutation', response)
    },
    allRolsAction: async function({ state, commit }){
      let response = await axios.get(state.url + 'roles', state.config);
      commit('allRolsMutation', response)
    }
  },
  getters: {
    getToken(state){
      return state.token;
    },
    getRol(state){
      return state.auth.rol;
    }
  }
})
