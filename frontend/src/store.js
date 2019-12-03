import Vue from 'vue'
import Vuex from 'vuex'

import authService from '@/services/auth'
import cajaService from '@/services/caja'
import adminService from '@/services/admin'
import mozoService from '@/services/mozo'
Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    auth: {},
    // Sidenav
    drawerState: true,
    miniState: true,
    // Data para la conexión a la API
    // url: 'http://192.168.1.63:8000/api',
    url: process.env.VUE_APP_API_URL,
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
    // Data Admin
    allMesasState: [],
    allCategoriasState: [],
    allPlatillosState: [],
    allPersonalState: [],
    allAdelantosState: [],
    allDescuentosState: [],
    allUsuariosState: [],
    allRolsState: [],
    allPedidosState: [],
    //
    headerBreadcrumb: ''
  },
  mutations: {
    drawerMutation (state, value) {
      state.drawerState = value
    },
    miniMutation (state, value) {
      state.miniState = value
    },
    loadingDialogMutation (state, value) {
      state.loadingDialog.state = value
    },
    loadingTitleMutation (state, value) {
      state.loadingDialog.title = value
    },
    loadingFishMutation (state, value) {
      state.loadingFish = value
    },
    headerActionsMutation (state, object) {
      state.headerActionCreate = object.create
      state.headerActionReport = object.report
    },
    createModalMutation (state, value) {
      state.createModalState = value
    },
    breadcrumbMutation (state, value) {
      state.breadcrumb = value
    },
    snackbarMutation (state, object) {
      state.snackbarState.state = object.value
      state.snackbarState.text = object.text
      state.snackbarState.color = object.color
    },
    searchQueryMutation (state, value) {
      state.searchQuery = value
    },
    searchPlaceholderMutation (state, value) {
      state.searchPlaceholder = value
    },
    searchDisabledMutation (state, value) {
      state.searchDisabled = value
    },
    authMutation (state, value) {
      state.auth = { ...value }
    },
    allMesasMutation (state, object) {
      state.allMesasState = object
    },
    allMesasSplice (state, id) {
      const index = state.allMesasState.data.findIndex(e => e.id === id)
      state.allMesasState.data[index].estado = 'Ocupado'
    },
    allCategoriasMutation (state, action) {
      state.allCategoriasState = action
    },
    allPlatillosMutation (state, action) {
      state.allPlatillosState = action
    },
    allPersonalMutation (state, action) {
      state.allPersonalState = action
    },
    allAdelantosMutation (state, action) {
      state.allAdelantosState = action
    },
    allDescuentosMutation (state, action) {
      state.allDescuentosState = action
    },
    allUsuariosMutation (state, action) {
      state.allUsuariosState = action
    },
    allRolsMutation (state, action) {
      state.allRolsState = action
    },
    allPedidosMutation (state, action) {
      state.allPedidosState = action
    },
    headerBreadcrumbMutation (state, value) {
      state.headerBreadcrumb = value
    }
  },
  actions: {
    async login ({ commit }, credentials) {
      const { data } = await authService.login(credentials)
      window.localStorage.setItem('auth', JSON.stringify(data))
      commit('authMutation', data)
    },
    async logout ({ commit }) {
      window.localStorage.removeItem('auth')
      commit('authMutation', {})
    },
    allMesasAction: async function ({ commit }) {
      const response = await adminService.getMesas()
      commit('allMesasMutation', response)
    },
    allCategoriasAction: async function ({ commit }) {
      const response = await adminService.getCategorias()
      commit('allCategoriasMutation', response)
    },
    allPlatillosAction: async function ({ commit }) {
      const response = await adminService.getPlatillos()
      commit('allPlatillosMutation', response)
    },
    allPersonalAction: async function ({ commit }) {
      const response = await adminService.getPersonal()
      commit('allPersonalMutation', response)
    },
    allAdelantosAction: async function ({ commit }) {
      const response = await adminService.getAdelantos()
      commit('allAdelantosMutation', response)
    },
    allDescuentosAction: async function ({ commit }) {
      const response = await adminService.getDescuentos()
      commit('allDescuentosMutation', response)
    },
    allUsuariosAction: async function ({ commit }) {
      const response = await adminService.getUsuarios()
      commit('allUsuariosMutation', response)
    },
    allRolsAction: async function ({ commit }) {
      const response = await adminService.getRoles()
      commit('allRolsMutation', response)
    },
    async getMesasAction ({ commit }) {
      const { data } = await cajaService.getMesas()
      commit('allMesasMutation', data)
    },
    async getPedidosAction ({ commit }) {
      const { data } = await mozoService.getPedidos()
      commit('allPedidosMutation', data)
    }
  },
  getters: {
    getBaseUrl (state) {
      return state.url
    },
    getAuth (state) {
      return state.auth.user
    },
    getToken (state) {
      return state.auth.access_token
    },
    getRol (state) {
      return state.auth.user.rol
    }
  }
})
