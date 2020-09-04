import request from './request'

export default {
  // 登录
  login(data) {
    return request({
      method: 'POST',
      url: '/user/login',
      data: data
    })
  },
}