const Utils = {
  /* 
   * 格式化星期几
   * @param {date/string/int} time 时间戳或日期
   * @return {string} 格式化后的星期几 
   */
  getDay: (time) => {
    let date = new Date(time)
    let day = ''
    switch (date.getDay()) {
      case 0:
        day = 'Sun'
        break
      case 1:
        day = 'Mon'
        break
      case 2:
        day = 'Tues'
        break
      case 3:
        day = 'Wed'
        break
      case 4:
        day = 'Thur'
        break
      case 5:
        day = 'Fri'
        break
      case 6:
        day = 'Sat'
        break
    }
    return day
  },
  /* 
   * 格式化日期
   * @param {date/string/int} date 时间戳或日期，不填则默认当前
   * @param {string} 格式，默认yyyy-MM-dd
   * @return {string} 格式化后的日期
   */
  formatDate: (date, fmt) => {
    date = date ? new Date(date) : new Date()
    fmt = fmt || 'yyyy-MM-dd'
    let o = {
      'M+': date.getMonth() + 1, // 月份
      'd+': date.getDate(), // 日
      'h+': date.getHours(), // 小时
      'm+': date.getMinutes(), // 分
      's+': date.getSeconds(), // 秒
      'q+': Math.floor((date.getMonth() + 3) / 3), // 季度
      'S': date.getMilliseconds() // 毫秒
    }
    if (/(y+)/.test(fmt)) {
      fmt = fmt.replace(RegExp.$1, (date.getFullYear() + '').substr(4 - RegExp.$1.length))
    }
    for (let k in o) {
      if (new RegExp('(' + k + ')').test(fmt)) {
        fmt = fmt.replace(RegExp.$1, (RegExp.$1.length === 1) ? (o[k]) : (('00' + o[k]).substr(('' + o[k]).length)))
      }
    }
    return fmt
  },
}

export default Utils