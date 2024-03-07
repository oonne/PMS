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
  let day = ['星期一', '星期二', '星期三', '星期四', '星期五', '星期六', '星期日',][now.getDay()];
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