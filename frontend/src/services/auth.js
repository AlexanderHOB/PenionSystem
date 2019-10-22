import { auth } from './service'

const authService = {
  // Login
  login: credentials => {
    console.log(credentials)
    return auth.post('/login', credentials)
  }
}

export default authService