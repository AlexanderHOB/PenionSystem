import { auth } from './service'

const authService = {
  // Login
  login: credentials => {
    return auth.post('/login', credentials)
  }
}

export default authService
