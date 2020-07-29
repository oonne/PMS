// 上传整个书签表
const updateBookmarks = () => {
  chrome.bookmarks.getTree((bookmarks)=>{
    chrome.storage.sync.get((items) => {
      const { token } = items;
      if (!token) return;

      console.log(bookmarks);
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