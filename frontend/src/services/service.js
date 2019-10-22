import axios from 'axios'
import store from '@/store'

export const service = axios.create({
  baseURL: store.getters.getBaseUrl,
  headers: {
    'Content-Type': 'application/json'
  }
})

// service.interceptors.request.use(
//   function (config) {
//     const token = store.getters.getToken
//     config.headers.Authorization = `Bearer ${token}`
//     return config
//   }
// )

export const auth = axios.create({
  baseURL: `${store.getters.getBaseUrl}auth`,
  headers: {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
})

export const authWithToken = axios.create({
  baseURL: `${store.getters.getBaseUrl}auth`,
  headers: {
    'Content-Type': 'application/json'
  }
})

// authWithToken.interceptors.request.use(
//   function (config) {
//     const token = store.getters.getToken
//     config.headers.Authorization = `Bearer ${token}`
//     return config
//   }
// )
