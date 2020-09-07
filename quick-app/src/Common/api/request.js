import fetch from '@system.fetch'
import storage from '@system.storage'
import config from '../config/config'

const getToken = () => {
  return new Promise((resolve, reject) => {
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
const deleteToken = () => {
  return new Promise((resolve, reject) => {
    storage.delete({
      key: 'token',
      success: () => {
        resolve()
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
        'content-type': 'application/json',
      },
      data: params.data,
    }).then(res=>{
      if (res.data.code == 200) {
        return JSON.parse(res.data.data)
      } else if (res.data.code == 401) {
        return deleteToken().then(Promise.reject)
      } else {
        return Promise.reject(res)
      }
    })
  })
}

export default request