const fs = require('fs');
const https = require('https');
let gulp = require('gulp');
let config = require('./config/config');

/* get TimeStep */
const getTime = () => {
  const paddedNum = (num) => {
    if(num<10){
      return `0${num}`;
    } else {
      return num;
    }
  }

  let now = new Date();
  let time = `${paddedNum(now.getHours())}:${paddedNum(now.getMinutes())}:${paddedNum(now.getSeconds())}`;
  let day = ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六',][now.getDay()];
  let date = `${now.getFullYear()}年${paddedNum(now.getMonth()+1)}月${paddedNum(now.getDate())}日`;

  return `${date} ${day} ${time}`;
}

/* download */ 
gulp.task('download', () => {
  return new Promise((resolve, reject) => {
    const options = {
      hostname: config.apiUrl,
      port: 443,
      path: '/note/all',
      method: 'GET',
      headers: {
        'X-Auth-Token': config.token,
        'Content-Type': 'application/json; charset=UTF-8',
      },
    };
    let resBuff = Buffer.alloc(0);
    let req = https.request(options, (res) => {
      res.on('data', (buff) => {
        resBuff = Buffer.concat([resBuff, buff]);
      });
      res.on('end', () => {
        const notes = JSON.parse(resBuff.toString()).Data;
        config.list.map((item)=>{
          const note = notes.find((content)=>content.uNoteID == item.id);
          if (note) {
            fs.writeFileSync(`./notes/${item.name}.txt`, note.tNoteContent); 
          }
        });
        resolve();
      });
    });
    req.on('error', (e) => {
      console.error(e);
      reject(e);
    });
    req.end();
  });
}); 

/* update */ 
const update = (note, path) => {
  return new Promise((resolve, reject) => {
    fs.readFile(path, 'UTF-8', (err, data) => {
      if (err) {
        return reject(err);
      } else {
        const uNoteID = note.id;
        const sNoteTitle = note.name;
        const tNoteContent = data;
        const postData = JSON.stringify({
            'uNoteID' : uNoteID,
            'sNoteTitle' : sNoteTitle,
            'tNoteContent' :tNoteContent
          });

        const options = {
          hostname: config.apiUrl,
          port: 443,
          path: '/note/update',
          method: 'POST',
          headers: {
            'X-Auth-Token': config.token,
            'Content-Type': 'application/json; charset=UTF-8',
            'Content-Length': Buffer.byteLength(postData)
          },
        };
        let req = https.request(options, (res) => {
          res.on('data', (chunk) => {
            if (JSON.parse(chunk).Ret == 0) {
              console.log(`${sNoteTitle} 同步成功 ${getTime()}`);
              // notice(`${sNoteTitle}<font color=\"info\">同步成功</font>`);
            };
            resolve();
          });
        });
        req.on('error', (e) => {
          console.log(`${sNoteTitle} 同步失败 ${getTime()}`);
          notice(`${sNoteTitle}<font color=\"warning\">同步接口报错</font>`);
          reject(e);
        });
        req.write(postData);
        req.end();
      }
    });


  }).catch(error => {
    console.error(error);
  });
}
/* notice */ 
const notice = (content) => {
  let postData = JSON.stringify({
    "msgtype": "markdown",
    "markdown": {
      "content": content
    }
  });
  let options = {
    hostname: 'qyapi.weixin.qq.com',
    port: 443,
    path: config.noticeApi,
    method: 'POST',
    headers: {
      'Content-Type': 'application/json; charset=UTF-8',
      'Content-Length': Buffer.byteLength(postData),
    },
  };
  let req = https.request(options);
  req.write(postData);
  req.end();
}

/* watch */ 
gulp.task('watch', () => {
  config.list.map((note)=>{
    let watcher = gulp.watch(`./notes/${note.name}.txt`);
    watcher.on('change', path=>{
      update(note, path);
    });
    console.log(`watching ${note.name}`);
  });
})

/* auto-sync */ 
gulp.task('auto-sync', gulp.series('download', 'watch'));