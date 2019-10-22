import { auth } from './service'

const authService = {
  // Login
  login: credentials => {
    return service.post('/login', credentials)
  }
}

export default authService