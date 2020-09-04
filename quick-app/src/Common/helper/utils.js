
import prompt from '@system.prompt'
import shortcut from '@system.shortcut'
import geolocation from '@system.geolocation'

const Utils = {
  /* 创建桌面图标 */ 
  createShortcut(){
    shortcut.hasInstalled({
      success: (ret) => {
        if (!ret) {
          shortcut.install({
            success: function () {
              prompt.showToast({
                message: '已创建桌面图标'
              })
            },
            fail: function (errmsg, errcode) {
              prompt.showToast({
                message: `${errcode}: ${errmsg}`
              })
            }
          })
        }
      }
    })
  },
  /* 获取定位 */
  getLoacl(){
    return new Promise((resolve, reject) => {
      geolocation.getLocation({
        success: function(data) {
          resolve(data)
        },
        fail: function(data, code) {
          reject(code)
        }
      })
    })
  }
}

export default Utils