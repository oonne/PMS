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
}

export default Utils