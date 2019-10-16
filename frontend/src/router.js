import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import store from './store'

Vue.use(Router)

function requireAuth (to, from, next) {

  checkLocal();

  if (!store.getters.getToken) {
    next({ name: 'login' })
  } else {
    if(to.matched[0].name === 'mozo' && store.getters.getRol !== 'Mozo'){
      next({ name: 'home' })
    }

    if(store.getters.getRol === 'Mozo' && to.matched[0].name !== 'mozo'){
      next({ name: 'mozo' })
    }

    next()
  }
}

function Auth (to, from, next) {

  checkLocal();

  if (store.getters.getToken) {
    if(store.getters.getRol === 'Administrador') {
      next({ name: 'home' })
    }

    if(store.getters.getRol === 'Mozo') {
      next({ name: 'mozo' })
    }
  } else {
    next()
  }
}

function checkLocal() {
  const user = typeof localStorage.getItem('auth') === 'string' ?
  JSON.parse(localStorage.getItem('auth')) :
  ''
  const token = typeof localStorage.getItem('token') === 'string' ?
  localStorage.getItem('token') :
  ''

  if(user && token){
     store.commit('tokenMutation', token)
     store.commit('authMutation', user)
  }
}

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      name: 'login',
      beforeEnter: Auth,
      component: () => import( './views/Login.vue')
    },
    {
      path: '/dashboard',
      name: 'home',
      beforeEnter: requireAuth,
      component: Home
    },
    {
      path: '/mesas',
      name: 'mesas',
      beforeEnter: requireAuth,
      component: () => import( './views/Mesas.vue')
    },
    {
      path: '/categorias',
      name: 'categorias',
      beforeEnter: requireAuth,
      component: () => import('./views/Categorias.vue')
    },
    {
      path: '/platillos',
      name: 'platillos',
      beforeEnter: requireAuth,
      component: () => import('./views/Platillos.vue')
    },
    {
      path: '/personal',
      name: 'personal',
      beforeEnter: requireAuth,
      component: () => import('./views/RecursosHumanos/Personal.vue')
    },
    {
      path: '/usuarios',
      name: 'usuarios',
      beforeEnter: requireAuth,
      component: () => import('./views/RecursosHumanos/Usuarios.vue')
    },
    {
      path: '/adelantos',
      name: 'adelantos',
      beforeEnter: requireAuth,
      component: () => import('./views/RecursosHumanos/Adelantos.vue')
    },
    {
      path: '/descuentos',
      name: 'descuentos',
      beforeEnter: requireAuth,
      component: () => import('./views/RecursosHumanos/Descuentos.vue')
    },
    {
      path: '/pagos',
      name: 'pagos',
      beforeEnter: requireAuth,
      component: () => import('./views/RecursosHumanos/Pagos.vue')
    },
    {
      path: '/mozo',
      name: 'mozo',
      beforeEnter: requireAuth,
      component: () => import('./views/Mozo/Mozo.vue'),
      redirect: { name: 'mozo-mesas' },
      children: [
        {
          path: 'mesas',
          name: 'mozo-mesas',
          redirect: { name: 'mozo-mesas-libres' },
          component: () => import('./views/Mozo/Mesas.vue'),
          children: [
            {
              path: 'mesas-libres',
              name: 'mozo-mesas-libres',
              component: () => import('./views/Mozo/Mesas-libres.vue')
            },
            {
              path: 'mesas-ocupadas',
              name: 'mozo-mesas-ocupadas',
              component: () => import('./views/Mozo/Mesas-ocupadas.vue')
            },
            {
              path: 'mis-mesas',
              name: 'mozo-mis-mesas',
              component: () => import('./views/Mozo/Mis-mesas.vue')
            }
          ]
        },
        {
          path: 'menu',
          name: 'mozo-menu',
          component: () => import('./views/Mozo/Menu.vue')
        }
      ]
    },
    {
      path: '*',
      component: () => import('./views/NotFound.vue')
    }
  ]
})
