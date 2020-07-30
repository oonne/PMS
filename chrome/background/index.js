// 上传整个书签表
const updateBookmarks = () => {
  chrome.bookmarks.getTree((bookmarks)=>{
    chrome.storage.sync.get((items) => {
      const { token } = items;
      if (!token) return;

      let api = 'https://api.pms.oonne.com';
      let configValue = JSON.stringify({
        sConfigKey: 'bookmarks',
        tConfigValue: bookmarks
      });
      let reqOpitons = {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-Auth-Token': token
        },
        mode: 'no-cors',
        body: configValue
      }
      fetch(`${api}/config/update`, reqOpitons);
    });
  });
};


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