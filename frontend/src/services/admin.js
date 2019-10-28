import { service } from './service'

const adminService = {
  // Mesa
  getMesas: () => {
    return service.get('/mesas')
  },
  createMesa: data => {
    return service.post('/mesa/registrar', data)
  },
  updateMesa: (id, data) => {
    return service.post(`/mesa/actualizar/${id}`, data)
  },
  // Categorias
  getCategorias: () => {
    return service.get('/categoria/platillos')
  },
  createCategoria: data => {
    return service.post('/categoria/platillo/registrar', data)
  },
  updateCategoria: (id, data) => {
    return service.post(`/categoria/platillo/actualizar/${id}`, data)
  },
  disabledCategoria: id => {
    return service.put(`/categoria/platillo/desactivar/${id}`, {})
  },
  enabledCategoria: id => {
    return service.put(`/categoria/platillo/activar/${id}`, {})
  },
  // Platillos
  getPlatillos: () => {
    return service.get('/platillos')
  },
  createPlatillo: data => {
    return service.post('/platillo/registrar', data)
  },
  updatePlatillo: (id, data) => {
    return service.post(`/platillo/actualizar/${id}`, data)
  },
  disabledPlatillo: id => {
    return service.put(`/platillo/desactivar/${id}`, {})
  },
  enabledPlatillo: id => {
    return service.put(`/platillo/activar/${id}`, {})
  },
  // Personal
  getPersonal: () => {
    return service.get('/empleados')
  },
  createPersonal: data => {
    return service.post('/empleado/registrar', data)
  },
  updatePersonal: (id, data) => {
    return service.post(`/empleado/actualizar/${id}`, data)
  },
  disabledPersonal: id => {
    return service.put(`/empleado/desactivar/${id}`, {})
  },
  enabledPersonal: id => {
    return service.put(`/empleado/activar/${id}`, {})
  },
  getHistorialOfPersonal: params => {
    return service.get('/historial', {
      params
    })
  },
  // Adelantos
  getAdelantos: () => {
    return service.get('/historial/adelanto')
  },
  // Descuentos
  getDescuentos: () => {
    return service.get('/historial/descuento')
  },
  // Usuarios
  getUsuarios: () => {
    return service.get('/users')
  },
  // Roles
  getRoles: () => {
    return service.get('/roles')
  },
}

export default adminService