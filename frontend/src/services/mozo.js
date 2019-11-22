import { service } from './service'

const mozoService = {
  getPedidos: () => {
    return service.get('/pedidos')
  },
  getPedido: id => {
    return service.get(`/pedido/${id}`)
  }
}

export default mozoService
