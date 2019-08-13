import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import store from './store'

Vue.use(Router)

function requireAuth (to, from, next) {
  if (store.getters.getToken !== 'token') {
    next({ name: 'login' })
  } else {
    if(to.matched[0].name === 'mozo' && store.getters.getRol !== 'mozo'){
      next({ name: 'home' })
    }

    if(store.getters.getRol === 'mozo' && to.matched[0].name !== 'mozo'){
      next({ name: 'mozo' })
    }

    next()
  }
}

function Auth (to, from, next) {
  if (store.getters.getToken === 'token') {
    if(store.getters.getRol === 'admin') {
      next({ name: 'home' })
    }

    if(store.getters.getRol === 'mozo') {
      next({ name: 'mozo' })
    }
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
