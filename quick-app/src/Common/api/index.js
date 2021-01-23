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
  pedometerLatest(){
    return request({
      method: 'GET',
      url: '/pedometer/latest',
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
  consumptionIndex(data){
    return request({
      method: 'GET',
      url: '/consumption/index',
      data: data
    })
  },
  consumptionAdd(data){
    return request({
      method: 'POST',
      url: '/consumption/add',
      data: data
    })
  },
  consumptionUpdate(data){
    return request({
      method: 'POST',
      url: '/consumption/update',
      data: data
    })
  },
  consumptionDelete(data){
    return request({
      method: 'POST',
      url: '/consumption/delete',
      data: data
    })
  },
  /* 收入 */
  incomeIndex(data){
    return request({
      method: 'GET',
      url: '/income/index',
      data: data
    })
  },
  incomeAdd(data){
    return request({
      method: 'POST',
      url: '/income/add',
      data: data
    })
  },
  incomeUpdate(data){
    return request({
      method: 'POST',
      url: '/income/update',
      data: data
    })
  },
  incomeDelete(data){
    return request({
      method: 'POST',
      url: '/income/delete',
      data: data
    })
  },
  /* 记事本 */
  noteIndex(data){
    return request({
      method: 'GET',
      url: '/note/index',
      data: data
    })
  },
  noteAdd(data){
    return request({
      method: 'POST',
      url: '/note/add',
      data: data
    })
  },
  noteUpdate(data){
    return request({
      method: 'POST',
      url: '/note/update',
      data: data
    })
  },
  noteDelete(data){
    return request({
      method: 'POST',
      url: '/note/delete',
      data: data
    })
  },
  /* 日记 */
  diaryIndex(data){
    return request({
      method: 'GET',
      url: '/diary/index',
      data: data
    })
  },
  diaryAdd(data){
    return request({
      method: 'POST',
      url: '/diary/add',
      data: data
    })
  },
  diaryUpdate(data){
    return request({
      method: 'POST',
      url: '/diary/update',
      data: data
    })
  },
  diaryDelete(data){
    return request({
      method: 'POST',
      url: '/diary/delete',
      data: data
    })
  },
  /* 密码 */
  passwordIndex(data){
    return request({
      method: 'GET',
      url: '/password/index',
      data: data
    })
  },
  passwordAdd(data){
    return request({
      method: 'POST',
      url: '/password/add',
      data: data
    })
  },
  passwordUpdate(data){
    return request({
      method: 'POST',
      url: '/password/update',
      data: data
    })
  },
  passwordDelete(data){
    return request({
      method: 'POST',
      url: '/password/delete',
      data: data
    })
  },
}