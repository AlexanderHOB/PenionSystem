import { service } from './service'

const adminService = {
  // Mesa
  getMesas: () => {
    return service.get('/mesas')
  },
  createMesa: data => {
    // const response = await service.post('/mesa/registrar', data)
    return service.post('/mesa/registrar', data)
    // return response
  },
  updateMesa: (id, data) => {
    return service.post(`/mesa/actualizar/${id}`, data)
  },
  getCategorias: () => {
    return service.get('/categoria/platillos')
  },
  getPlatillos: () => {
    return service.get('/platillos')
  },
  getPersonal: () => {
    return service.get('/empleados')
  },
  getAdelantos: () => {
    return service.get('/historial/adelanto')
  },
  getDescuentos: () => {
    return service.get('/historial/descuento')
  },
  getUsuarios: () => {
    return service.get('/users')
  },
  getRoles: () => {
    return service.get('/roles')
  },
}

export default adminService