import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import store from './store'

Vue.use(Router)

function requireAuth (to, from, next) {
  if (store.getters.getToken !== 'token') {
    next({ name: 'login' })
  } else {
    next()
  }
}

function Auth (to, from, next) {
  if (store.getters.getToken === 'token') {
    next({ name: 'home' })
  } else {
    next()
  }
}

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/login',
      name: 'login',
      beforeEnter: Auth,
      component: () => import( './views/Login.vue')
    },
    {
      path: '/',
      name: 'home',
      beforeEnter: requireAuth,
      component: Home
    },
    {
      path: '/mesas',
      name: 'mesas',
      component: () => import( './views/Mesas.vue')
    },
    {
      path: '/categorias',
      name: 'categorias',
      component: () => import('./views/Categorias.vue')
    },
    {
      path: '/platillos',
      name: 'platillos',
      component: () => import('./views/Platillos.vue')
    },
    {
      path: '/personal',
      name: 'personal',
      component: () => import('./views/RecursosHumanos/Personal.vue')
    },
    {
      path: '/usuarios',
      name: 'usuarios',
      component: () => import('./views/RecursosHumanos/Usuarios.vue')
    },
    {
      path: '/adelantos',
      name: 'adelantos',
      component: () => import('./views/RecursosHumanos/Adelantos.vue')
    },
    {
      path: '/descuentos',
      name: 'descuentos',
      component: () => import('./views/RecursosHumanos/Descuentos.vue')
    },
    {
      path: '/pagos',
      name: 'pagos',
      component: () => import('./views/RecursosHumanos/Pagos.vue')
    },
    {
      path: '*',
      component: () => import('./views/NotFound.vue')
    }
  ]
})
