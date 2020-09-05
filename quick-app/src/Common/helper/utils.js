
import prompt from '@system.prompt'
import shortcut from '@system.shortcut'
import health from '@service.health'
import geolocation from '@system.geolocation'

const Utils = {
  /* 创建桌面图标 */ 
  createShortcut(){
    shortcut.hasInstalled({
      success: (ret) => {
        if (!ret) {
          shortcut.install({
            success: () => {
              prompt.showToast({
                message: '已创建桌面图标'
              })
            },
            fail: (errmsg, errcode) => {
              prompt.showToast({
                message: `${errcode}: ${errmsg}`
              })
            }
          })
        }
      }
    })
  },
  /* 获取计步器数据 */
  getSteps(){
    return new Promise((resolve, reject) => {
      health.getLastWeekSteps({
        success: (data) => {
          resolve(data)
        },
        fail: (errmsg, errcode) => {
          prompt.showToast({
            message: `${errcode}: ${errmsg}`
          })
          reject(errmsg)
        }
      })
    })
  },
  /* 获取定位 */
  getLoacl(){
    return new Promise((resolve, reject) => {
      geolocation.getLocation({
        success: (data) => {
          resolve(data)
        },
        fail: (errmsg, errcode) => {
          prompt.showToast({
            message: `${errcode}: ${errmsg}`
          })
          reject(errmsg)
        }
      })
    })
  }
}

export default Utils