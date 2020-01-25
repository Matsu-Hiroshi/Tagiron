$(function () {
  var aArray = new Array();
  var qValue;
  // チェックボックスのクリック無効化
  $('.a_box .disabled_checkbox').click(function () {
    return false;
  });

  // 画像クリックの処理(回答)
  $('img.aNum').on('click', function () {
    var $count = $("img.aNum.checked").length;
    // console.log("count="+$count);
    if ($(this).is('.checked')) {// チェックが入っている画像をクリックしたとき、チェックを外す     
      $(this).removeClass('checked');
      aArray.splice(aArray.indexOf($(this)[0].alt),1);
      return;
    }
    if ($count < 5) { // チェックが５未満の時
      if (!$(this).is('.checked')) {// チェックが入っていない画像をクリックしたとき、チェックを入れる
        $(this).addClass('checked');
        aArray.push($(this)[0].alt);
      }
    }

    console.log();
  });

  $('.q_box .disabled_checkbox').click(function () {
    return false;
  });

  // 画像クリックの処理(質問)
  $('img.qimg').click(function () {
    var $imageList = $('.q_list');

    // 現在の選択解除
    $imageList.find('img.qimg.checked').removeClass('checked');
    
    // チェックを入れる
    $(this).addClass('checked');
    qValue = $(this)[0].alt;
  });

  //socket
  document.getElementById("qb").onclick = function () {
    if(qValue != null){
      console.log(qValue);

      logGene(qValue,"p1:3 p2:1");
    }
  }

  document.getElementById("ab").onclick = function () {
    aArray.sort();
    if(aArray.length == 5){
      console.log(aArray);
    }
  } 

  function logGene(qnum,atxt){
    $('#logBox').prepend('<div class="qa"><img class="qaimg" src="./Timg/'+ qnum +'.jpg" alt="'+ qnum +'" /><p class="atxt">'+ atxt +'</p></div> ');

    var num =["r1","r2","r3","r4","y5"];
    var q =[1,2];
    var qsArray=[];
    // 該当するタイルを探す
    for(var i=0;i<=q.length;i++ ){
      num.forEach(function(value){
        if(q[i] == value.substr(1,1)){
          qsArray.push(value);
        }
      })
    }
    console.log(qsArray);

    // モーダルウィンドウの要素を追加
    $('#modalQs').prepend('<div class="modalQsbox"><img class="qaimg" src="./Timg/'+ qnum +'.jpg" alt="'+ qnum +'" /></div>');
    qsArray.forEach(function(qsBValue){
      $('.modalQsbox').append('<button type="button" class="qsb" value="'+ qsBValue +'">'+ qsBValue +'</button>');
    })
    // モーダルウィンドウを表示
    $('.js-modal').fadeIn();
    // モーダルウィンドウでボタンが押されたときの処理
    var qsValue = document.getElementsByClassName('qsb');
    for(let i = 0; i < qsValue.length; i++){
      qsValue[i].addEventListener('click', function (event) {
        console.log(event.target.value);
        $('.js-modal').fadeOut();
        $('.modalQsbox').remove();
      }, false)
    }
    
    return false;
  }
});
