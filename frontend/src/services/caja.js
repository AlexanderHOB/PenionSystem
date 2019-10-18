import { service } from './service'

const cajaService = {
  getMesas: () => {
    return service.get(`/mesas`)
  },
}



export default cajaService