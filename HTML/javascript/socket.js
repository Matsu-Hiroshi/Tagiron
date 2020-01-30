

var socket = new WebSocket('ws://121.85.185.21:50500');

var key;

//サーバーに繋がった時の処理
socket.onopen = function (event) {
  console.log("connect");
};

//サーバーからのメッセージ受信
socket.onmessage = function (event) {

  var data = JSON.parse(event.data);
  console.log(data);
  switch(data.type) {
    case "connect":
      key = data.roomId;
      break;
    case "question":
      // console.log(data.anser);
      break;
    case "gameStart":
      gameStart(data);
      break;
    // case "message":
    //   console.log(data.msg);
    //   var text = document.getElementById("text").value;
    //   document.getElementById("text").innerHTML = data.msg;
    //   break;
    case "close":
      console.log("close");
      // document.getElementById("print").innerHTML = data.msg;
      key = "";
      socket.close();
      location.href = "http://localhost:8888/project/HTML/Title.html";
      break;
    default:
      break;
  }

};

function gameStart(fieldData) {

  console.log("gameStart_in");
  var tiles = fieldData.tile;

  setQuestions(fieldData.question);

  var count = 1;
  for (var key in tiles){
    var id = "tile" + count;
    // console.log(id);
    count++;

    var imgfile = "./Timg/" + tiles[key] + ".jpg";
    var img = document.getElementById(id);

    // console.log(img);
    // console.log(imgfile);
    img.src = imgfile;
  }

}

function setQuestions(questions) {

  var count = 1;
  for (var val in questions) {

    var id = "qimg" + count;
    count++;

    var imgfile = "./Timg/" + questions[val] + ".jpg";
    var img = document.getElementById(id);

    img.src = imgfile;

  }

}

//buttonAction

function enter() {

  if(window.event.keyCode == 13){
    send();
  }

}

function send() {

  if(socket.readyState == WebSocket.OPEN){
    // var text = document.getElementById("text").value;

    var data = {
      "type" : "question",
      "roomId" : key,
      "id" : "q1"
    };

    var json = JSON.stringify(data);
    socket.send(json);
  }

}