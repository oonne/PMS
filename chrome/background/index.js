// 上传整个书签表
const updateBookmarks = () => {
  chrome.bookmarks.getTree((bookmarks)=>{
    chrome.storage.sync.get((items) => {
      const { token } = items;
      if (!token) {
        chrome.notifications.create(null, {
          type: 'basic',
          title: '未设置 PMS token',
          iconUrl: '../icon.png',
          message: 'Chrome extensions',
        });
        return;
      }

      let api = 'https://api.pms.oonne.com';
      let value = JSON.stringify(bookmarks)
      let configValue = JSON.stringify({
        sConfigKey: 'BOOKMARKS',
        tConfigValue: value
      });

      let reqOpitons = {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-Auth-Token': token
        },
        body: configValue
      }
      fetch(`${api}/config/update`, reqOpitons).then(res=>{
        return res.json();
      }).then(data => {
        if (data.Ret==0) {
          notice(`<font color=\"info\">同步成功</font>`);
        } else {
          notice(data.Data.errors[0]);
        }
      });
    });
  });
};

// 通知企业微信
const notice = (content) => {
  let postData = JSON.stringify({
    "msgtype": "markdown",
    "markdown": {
      "content": content
    }
  });
  let reqOpitons = {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    },
    body: postData
  };
  fetch('https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=42511f33-f073-45ed-87bc-86292ffd0b58', reqOpitons);
}


// 新增、修改、删除书签都会触发
chrome.bookmarks.onCreated.addListener(()=>{
  updateBookmarks();
});
chrome.bookmarks.onChanged.addListener(()=>{
  updateBookmarks();
});
chrome.bookmarks.onRemoved.addListener(()=>{
  updateBookmarks();
});
chrome.bookmarks.onMoved.addListener(()=>{
  updateBookmarks();
});
chrome.bookmarks.onChildrenReordered.addListener(()=>{
  updateBookmarks();
});