chrome.storage.sync.get((items) => {
  const { token } = items;
  if (token) {
    $('#token_input').val(token);
  }
});

$('#save_token').click(()=>{
  let token = $('#token_input').val();
  if (token) {
    chrome.storage.sync.set({
      token: token,
    }, () => {
      alert('保存成功');
    });
  }
});