import request from './request'

export default {
  /* 登录 */ 
  login(data){
    return request({
      method: 'POST',
      url: '/user/login',
      data: data
    })
  },
  /* 刷新最后登录时间 */
  safetyRefresh(){
    return request({
      method: 'POST',
      url: '/safety/refresh',
      data: null
    })
  },
  /* 计步器 */
  pedometerIndex(){
    return request({
      method: 'GET',
      url: '/pedometer/index',
      data: null
    })
  },
  pedometerUpdate(data){
    return request({
      method: 'POST',
      url: '/pedometer/update',
      data: data
    })
  },
  /* 消费 */
  /* 收入 */
  /* 记事本 */
  /* 密码 */
  /* 日记 */
}