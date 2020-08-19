/*
 * 刷新时间
 */ 
const paddedNum = (num) => {
  if(num<10){
    return `0${num}`;
  } else {
    return num;
  }
}
const refreshTime = () => {
  let now = new Date();
  let clock = `${paddedNum(now.getHours())}:${paddedNum(now.getMinutes())}:${paddedNum(now.getSeconds())}`;
  $('#clock').html(clock);
  let day = ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日',][now.getDay()];
  let date = `${now.getFullYear()}年${paddedNum(now.getMonth()+1)}月${paddedNum(now.getDate())}日`;
  $('#calendar').html(`${date} ${day}`);
}
refreshTime();
setInterval(refreshTime, 1000);

/*
 * 链接显示
 */ 
$('#work-icon-list').hide();
$('#work-icon').mouseover(()=>{
  $('#work-icon-list').slideDown(200);
})
$('#links').mouseleave(()=>{
  $('#work-icon-list').slideUp(200);
})