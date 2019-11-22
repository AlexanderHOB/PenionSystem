import { service } from './service'

const mozoService = {
  getPedidos: () => {
    return service.get('/pedidos')
  }
}

export default mozoService
