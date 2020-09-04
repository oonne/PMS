import fetch from '@system.fetch'
import storage from '@system.storage'
import config from '../config/config'

const getToken = () => {
  return new Promise(resolve, reject) => {
    storage.get({
      key: 'token',
      default: '',
      success: (data) => {
        resolve(data)
      },
      fail: (e) => {
        reject(e)
      },
    })
  })
}

const request = (params) => {
  return getToken().then(token=>{
    return fetch.fetch({
      url: `${config.baseUrl}${params.url}`,
      method: params.method,
      header: {
        'x-auth-token': token,
      },
      responseType: 'json',
      data: params.data,
    })
  })
})

export default request