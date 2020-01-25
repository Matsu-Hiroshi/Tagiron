
var socket = new WebSocket('ws://192.168.0.4:8080');

var key;
var tiles = ["r0", "r1", "r2", "r3", "r4"];

var aArray = new Array();
var qValue;
var myturn;
var timerStop;

$(window).on('load', function(){
  console.log("window_onload");
  $('#modalQs').prepend('<div class="modalTurnS">対戦相手をまっています</div>');
  $('.js-modal').fadeIn();
});

// 画像クリック時の処理
$(function () {

  

  // チェックボックスのクリック無効化
  $('.a_box .disabled_checkbox').click(function () {
    return false;
  });

  // 画像クリックの処理(回答)
  $('img.aNum').on('click', function () {
    var $count = $("img.aNum.checked").length;
    if ($(this).is('.checked')) {// チェックが入っている画像をクリックしたとき、チェックを外す     
      $(this).removeClass('checked');
      aArray.splice(aArray.indexOf($(this)[0].alt), 1);
      return;
    }
    if ($count < 5) { // チェックが５未満の時
      if (!$(this).is('.checked')) {// チェックが入っていない画像をクリックしたとき、チェックを入れる
        $(this).addClass('checked');
        aArray.push($(this)[0].alt);
      }
    }
  });

  $('.q_box .disabled_checkbox').click(function () {
    return false;
  });

  // 画像クリックの処理(質問)
  $(document).on('click', 'img.qimg', function() {
    var $imageList = $('.q_list');

    // 現在の選択解除
    $imageList.find('img.qimg.checked').removeClass('checked');

    // チェックを入れる
    $(this).addClass('checked');
    var imageURL = $(this)[0].src;
    qValue = imageURL.split("/").pop().split(".")[0];
  });

  // 質問ボタンの動作

  document.getElementById("qb").onclick = function () {
    // timer(false);
    
    if (myturn) {
      if (qValue != null) {
        var qnum = new Array();
        var qsValue;
        switch (qValue) {
          case "q1":
            qsValue = 0;
            break;
          case "q2":
            qnum = [1, 2];
            break;
          case "q3":
            qnum = [3, 4];
            break;
          case "q4":
            qsValue = 5;
            break;
          case "q5":
            qnum = [6, 7];
            break;
          case "q6":
            qnum = [8, 9];
            break;
          default:
            qnum = -1;
            qsValue = null;
            break;
        }
        if (qnum.length == 2) {
          // モーダルウィンドウの要素を追加
          $('#modalQs').prepend('<div class="modalQsbox"><img class="modalQaimg" src="./Timg/' + qValue + '.jpg" alt="' + qValue + '" /><div class="modalBBox"></div></div>');
          qnum.forEach(function (qsbValue) {
            $('.modalBBox').append('<button type="button" class="modalQsb" value="' + qsbValue + '">' + qsbValue + '</button>');
          })
          $('.modalBBox').append('<button type="button" class="modalCancelb">キャンセル</button>');
          // モーダルウィンドウを表示
          $('.js-modal').fadeIn();
          // モーダルウィンドウでボタンが押されたときの処理
          $("button.modalQsb").click(function (event) {
            qsValue = event.toElement.value;
            questionSend();
            $('.js-modal').fadeOut();
            $('.modalQsbox').remove();
          });
  
          $("button.modalCancelb").click(function (event) {
            $('.js-modal').fadeOut();
            $('.modalQsbox').remove();
          });
        }else{
          // 選択しない質問
          questionSend();
        }
  
        function questionSend() {
          // データを送信
          var data = {
            "type" : "question",
            "roomId": key,
            "id": qValue,
            "qsValue": qsValue
          };
          send(data);
          // timer(false);
          // 送信した質問の選択解除
          var $imageList = $('.q_list');
          $imageList.find('img.qimg.checked').removeClass('checked');
          // 質問の変数をクリア
          qValue = null;
        }
      }
    }

  }

  // 回答ボタンの動作
  document.getElementById("ab").onclick = function () {
    if(myturn){
      aArray.sort();
      // timer(false);
      
      if (aArray.length == 5) {
        var data = {
          "type" : "answer",
          "roomId": key,
          "aArray": aArray
        };
        send(data);
        // 送信した回答の選択解除
        $("img.aNum.checked").removeClass('checked');
        // 回答の配列をクリア
        aArray.splice(0, aArray.length);
      }
      // var tmp = ["q1","q2","q4","q5","q7","q9"];
      // questionSet(tmp);
    }
  }
});

//サーバーに繋がった時の処理
socket.onopen = function (event) {

};

//サーバーからのメッセージ受信
socket.onmessage = function (event) {

  var data = JSON.parse(event.data);
  switch (data.type) {
    case "connect":
      key = data.roomId;
      break;
    case "gameStart":
      gameStart(data);
      break;
    case "questionData":
      var qIDs = date.qIDs;
      questionSet(qIDs);
      break;
    case "message":

      var text = document.getElementById("text").value;
      document.getElementById("text").innerHTML = data.msg;
      break;
    case "close":
      // document.getElementById("print").innerHTML = data.msg;
      key = "";
      socket.close();
      break;
    case "question":
      timerStop = true;
      var qID = data.qID;
      var atxt = data.anser;
      qLogGene(qID, atxt);
      setQuestions(data.field);
      checkTurn(data.turn);
      break;
    case "newField":
      timerStop = true;
      setQuestions(data.field);
      checkTurn(data.turn);
      // setTimeout(function(){
        
      // }, 3000);
      break;
    case "answer":
      timerStop = true;
      if(data.tof == "true"){
        aLogGene(data.player, true, data.turn);
      }else {
        aLogGene(data.player, false, data.turn);
      }
      break;
    default:
      break;
  }
};

function gameStart(fieldData) {

  $('.js-modal').fadeOut();
  $('.modalQa').remove();

  var tiles = fieldData.tile;

  setQuestions(fieldData.question);

  if(tiles.length > 4){
    var deleat = document.getElementById("tile5");
    deleat.parentNode.removeChild(deleat);
  }
  

  var count = 1;
  for (var key in tiles){
    var id = "tile" + count;
    count++;

    var imgfile = "./Timg/" + tiles[key] + ".jpg";
    var img = document.getElementById(id);

    img.src = imgfile;
  }

  checkTurn(fieldData.turn);

}

function setQuestions(questions) {

  var count = 1;
  for (var val in questions) {

    var id = "qimg" + count;
    count++;
    if(val == null){
      var img = document.getElementById(id);
      img.src = "./Timg/feald.jpg";
      img.id = null;
    }else {
      var imgfile = "./Timg/" + questions[val] + ".jpg";
      var img = document.getElementById(id);

      img.src = imgfile;
    }

  }
}

//buttonAction

function enter() {

  if (window.event.keyCode == 13) {
    send();
  }

}

function checkTurn(str){
  // timerStop = false;
  if(str == "true"){
    myturn = true;
    timeCounter();
  }else {
    myturn = false;
    timeCounter();
  }

}

function send(data) {
  if (socket.readyState == WebSocket.OPEN) {
    // timeCounter(false);
    timerstop = true;
    var json = JSON.stringify(data);
    socket.send(json);
  }
}

// 質問のログ生成
function qLogGene(qID, aMsg) {
  // モーダルウィンドウの要素を追加
  $('#modalQs').prepend('<div class="modalQa"><img class="modalQImg" src="./Timg/' + qID + '.jpg" alt="' + qID + '" /><p class="modalAMsg">' + aMsg + '</p></div>');
  // モーダルウィンドウを表示
  $('.js-modal').fadeIn();
  // 3秒後にモーダルウィンドウを削除
  setTimeout(function () {
    $('.js-modal').fadeOut();
    $('.modalQa').remove();
  }, 3000);
  $('#logBox').prepend('<div class="qLog"><img class="qLogImg" src="./Timg/' + qID + '.jpg" alt="' + qID + '" /><p class="logAMsg">' + aMsg + '</p></div>');
}

// 回答のログ生成
function aLogGene(player, tof, turn) {
  console.log(turn);
  var tofMsg;
  if (tof == true) {
    tofMsg = "正解です";
  } else {
    tofMsg = "不正解です";
  }
  // モーダルウィンドウの要素を追加
  $('#modalQs').prepend('<div class="modalTf">' + player + 'の回答：' + tofMsg + '</div>');
  // モーダルウィンドウを表示
  $('.js-modal').fadeIn();
  // 3秒後にモーダルウィンドウを削除
  setTimeout(function () {
    $('.js-modal').fadeOut();
    $('.modalTf').remove();

    if(tof == true){
      if(player.match("あなた")){
        resultDisplay("win");
      }else {
        resultDisplay("lose");
      }
    }else{
      checkTurn(turn);
    }

  }, 3000);
  $('#logBox').prepend('<div class="tf">' + player + 'の回答：' + tofMsg + '</div>');

  

}

// 質問カードをセット
function questionSet(qIDs) {
  for (var i = 0; i < 6; i++) {
    var qlist = document.getElementById("qlist" + i);
    qlist.innerHTML = '<img class="qimg" id="qlist0" src="./Timg/' + qIDs[i] + '.jpg" alt="' + qIDs[i] + '" />'
  }
}

// 結果表示
function resultDisplay(result) {
  var resultMsg;
  switch (result) {
    case "win":
      resultMsg = "You win!!\nゲーム終了";
      break;
    case "lose":
      resultMsg = "You lose...\nゲーム終了";
      break;
    case "draw":
      resultMsg = "Draw!"
      break;
    default:
      break;
  }
  // モーダルウィンドウの要素を追加
  $('#modalQs').prepend('<div class="modalResult">' + resultMsg + '</div>');
  // モーダルウィンドウを表示
  $('.js-modal').fadeIn();
  // 5秒後にモーダルウィンドウを削除
  setTimeout(function () {
    $('.js-modal').fadeOut();
    $('.modalResult').remove();
    location.href = 'title.html';
  }, 5000);
}

// 30秒カウント

function timeCounter() {

  var turnP = document.getElementById("turn");
  if (myturn) {
    turnP.innerHTML = 'あなたのターン';
  } else {
    turnP.innerHTML = '相手のターン';
  }

  if (myturn == true) {
    // 開始
    // モーダルウィンドウの要素を追加
    $('#modalQs').prepend('<div class="modalTurnS">あなたのターンです。</div>');
    // モーダルウィンドウを表示
    $('.js-modal').fadeIn();
    // 1秒後にモーダルウィンドウを削除
    setTimeout(function () {
      $('.js-modal').fadeOut();
      $('.modalTurnS').remove();
      // timer(true);
    }, 2000);

    
  }else {
    $('#modalQs').prepend('<div class="modalTurnS">相手のターンです。</div>');
    // モーダルウィンドウを表示
    $('.js-modal').fadeIn();
    // 1秒後にモーダルウィンドウを削除
    setTimeout(function () {
      $('.js-modal').fadeOut();
      $('.modalTurnS').remove();
      // timer(true);
    }, 2000);
  }

  var time = 60;
  var timer = setInterval(function () {
    time = time - 1;
    var timeP = document.getElementById("time");
    timeP.innerHTML = time + "秒";
    if (timerStop) {
      clearInterval(timer);
      timerStop = false;
    }

    if(time == 0){
      // モーダルウィンドウの要素を追加
      $('#modalQs').prepend('<div class="modalTurnF">ターン終了。</div>');
      // モーダルウィンドウを表示
      $('.js-modal').fadeIn();
      // 1秒後にモーダルウィンドウを削除
      setTimeout(function () {
        $('.js-modal').fadeOut();
        $('.modalTurnF').remove();
      }, 1000);

      clearInterval(timer);
      timerStop = false;
    }

  }, 1000);
  
}

