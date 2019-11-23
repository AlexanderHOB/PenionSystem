import { service } from './service'

const mozoService = {
  getPedidos: () => {
    return service.get('/pedidos')
  },
  getPedido: id => {
    return service.get(`/pedido/${id}`)
  },
  createPedido: data => {
    return service.post('/pedido/registrar', data)
  }
}

export default mozoService
