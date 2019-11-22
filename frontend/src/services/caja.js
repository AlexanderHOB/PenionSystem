import { service } from './service'

const cajaService = {
  getMesas: () => {
    return service.get('/caja/mesas')
  }
}

export default cajaService
