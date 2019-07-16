import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'

Vue.use(Router)

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import( './views/Login.vue')
    },
    {
      path: '/',
      name: 'home',
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
