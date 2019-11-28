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
  },
  updatePedido: (detalles_pedido, id) => {
    return service.put(`/pedido/aumentar/${id}`, { detalles_pedido })
  }
}

export default mozoService
