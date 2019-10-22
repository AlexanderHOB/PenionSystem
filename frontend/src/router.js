import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Admin/Home.vue'
import store from './store'

Vue.use(Router)

function requireAuth () {
  const verify = checkLocal()
  if (!verify) {
    return false
  }
  return true
}

function requireAdminRol (to, from, next) {
  const verify = requireAuth()

  if (!verify) {
    next({ name: 'login' })
    return
  }

  if (store.getters.getRol !== 'Administrador') {
    redirect()
  }

  next()
}

function requireMozoRol (to, from, next) {
  const verify = requireAuth()

  if (!verify) {
    next({ name: 'login' })
    return
  }

  if (store.getters.getRol !== 'Mozo') {
    redirect()
  }

  next()
}

function requireCajeroRol (to, from, next) {
  const verify = requireAuth()

  if (!verify) {
    next({ name: 'login' })
    return
  }

  if (store.getters.getRol !== 'Cajero') {
    redirect()
  }

  next()
}

function Auth (to, from, next) {

  const verify = checkLocal()
  if (verify) {
    redirect()
  }
  next()
}

function checkLocal () {
  if (store.getters.getToken) {
    return true
  }

  if (localStorage.getItem('auth')) {
    const user = JSON.parse(localStorage.getItem('auth'))
    store.commit('authMutation', user)
    return true
  }
  return false
}

function redirect () {
  
    if (store.getters.getRol === 'Administrador') {
      next({ name: 'admin' })
    }
  
    if (store.getters.getRol === 'Mozo') {
      next({ name: 'mozo' })
    }

    if (store.getters.getRol === 'Cajero') {
      next({ name: 'caja' })
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
      path: '/admin',
      name: 'admin',
      beforeEnter: requireAdminRol,
      component: () => import('./views/Admin/Layout.vue'),
      redirect: { name: 'home' },
      children: [
        {
          path: 'dashboard',
          name: 'home',
          component: Home
        },
        {
          path: 'mesas',
          name: 'mesas',
          component: () => import( './views/Admin/Mesas.vue')
        },
        {
          path: 'categorias',
          name: 'categorias',
          component: () => import('./views/Admin/Categorias.vue')
        },
        {
          path: 'platillos',
          name: 'platillos',
          component: () => import('./views/Admin/Platillos.vue')
        },
        {
          path: 'personal',
          name: 'personal',
          component: () => import('./views/Admin/RecursosHumanos/Personal.vue')
        },
        {
          path: 'usuarios',
          name: 'usuarios',
          component: () => import('./views/Admin/RecursosHumanos/Usuarios.vue')
        },
        {
          path: 'adelantos',
          name: 'adelantos',
          component: () => import('./views/Admin/RecursosHumanos/Adelantos.vue')
        },
        {
          path: 'descuentos',
          name: 'descuentos',
          component: () => import('./views/Admin/RecursosHumanos/Descuentos.vue')
        },
        {
          path: 'pagos',
          name: 'pagos',
          component: () => import('./views/Admin/RecursosHumanos/Pagos.vue')
        }
      ]
    },
    {
      path: '/mozo',
      name: 'mozo',
      beforeEnter: requireMozoRol,
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
      path: '/caja',
      name: 'caja',
      // beforeEnter: requireCajeroRol,
      redirect: { name: 'cajero' },
      component: () => import('./views/Caja/Layout.vue'),
      children: [
        {
          path: 'cajero',
          name: 'cajero',
          redirect: { name: 'cajeroMesasLibres' },
          component: () => import('./views/Caja/cajero/Cajero.vue'),
          children: [
            {
              path: 'mesas-libres',
              name: 'cajeroMesasLibres',
              component: () => import('./views/Caja/cajero/MesasLibres.vue')
            },
            {
              path: 'mesas-ocupadas',
              name: 'cajeroMesasOcupadas',
              component: () => import('./views/Caja/cajero/MesasOcupadas.vue')
            },
          ]
        },
        {
          path: 'eventos',
          name: 'eventos',
          redirect: { name: 'listaEventos' },
          component: () => import('./views/Caja/eventos/Eventos.vue'),
          children: [
            {
              path: 'lista-eventos',
              name: 'listaEventos',
              component: () => import('./views/Caja/eventos/ListaEventos.vue')
            },
            {
              path: 'evento',
              name: 'evento',
              component: () => import('./views/Caja/eventos/CrearEvento.vue')
            },
          ]
        },
        {
          path: 'reservaciones',
          name: 'reservaciones',
          redirect: { name: 'listaReservaciones' },
          component: () => import('./views/Caja/reservaciones/Reservaciones.vue'),
          children: [
            {
              path: 'lista-reservaciones',
              name: 'listaReservaciones',
              component: () => import('./views/Caja/reservaciones/ListaReservaciones.vue')
            },
            {
              path: 'reservacion',
              name: 'reservacion',
              component: () => import('./views/Caja/reservaciones/CrearReservacion.vue')
            },
          ]
        },
        {
          path: 'orden',
          name: 'orden',
          redirect: { name: 'buscarOrden' },
          component: () => import('./views/Caja/orden/Orden.vue'),
          children: [
            {
              path: 'buscar-orden',
              name: 'buscarOrden',
              component: () => import('./views/Caja/orden/BuscarOrden.vue')
            },
            {
              path: 'nueva-orden',
              name: 'nuevaOrden',
              component: () => import('./views/Caja/orden/NuevaOrden.vue')
            },
          ]
        },
        {
          path: 'menu',
          name: 'caja-menu',
          component: () => import('./views/Caja/menu/Menu.vue')
        }
      ]
    },
    {
      path: '*',
      name: 'notFound',
      component: () => import('./views/NotFound.vue')
    }
  ]
})
