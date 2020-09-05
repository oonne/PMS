import request from './request'

export default {
  // 登录
  login(data){
    return request({
      method: 'POST',
      url: '/user/login',
      data: data
    })
  },
  // 刷新最后登录时间
  safetyRefresh(data){
    return request({
      method: 'POST',
      url: '/safety/refresh',
      data: data
    })
  },
  // 更新步数信息
  pedometerUpdate(data){
    return request({
      method: 'POST',
      url: '/pedometer/update',
      data: data
    })
  },
}