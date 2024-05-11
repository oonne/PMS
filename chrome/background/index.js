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

      const api = 'https://api.pms.oonne.com';
      const value = JSON.stringify(bookmarks)
      const configValue = JSON.stringify({
        sConfigKey: 'BOOKMARKS',
        tConfigValue: value
      });

      const reqOpitons = {
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
          notice('同步成功');
        } else {
          notice(data.Data.errors[0]);
        }
      });
    });
  });
};

// 通知企业微信
const notice = (content) => {
  const postData = JSON.stringify({
    "msg_type": "text",
    "content": {
      "text": content
    }
  });
  const reqOpitons = {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    },
    body: postData
  };
  fetch('https://open.feishu.cn/open-apis/bot/v2/hook/d431393e-fa50-442b-955c-880a417f97d9', reqOpitons);
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