$(function () {
  let limit = $('.member-limit-toggle'); //member-limit-toggleのついたbuttonタグを取得し代入。
  let member_id; 
  limit.on('click', function () { //onはイベントハンドラー
    let $this = $(this); //this=イベントの発火した要素＝iタグを代入
    member_id = $this.data('member_id'); 
    console.log('on clickはできてる')
    console.log(member_id);
    //ajax処理スタート
    $.ajax({
      headers: { //HTTPヘッダ情報をヘッダ名と値のマップで記述
        'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
      },  //↑name属性がcsrf-tokenのmetaタグのcontent属性の値を取得
      url: 'member_limit', //通信先アドレスで、このURLをあとでルートで設定します
      method: 'POST', //HTTPメソッドの種別を指定します。1.9.0以前の場合はtype:を使用。
      data: { //サーバーに送信するデータ
        'member_id': member_id //いいねされた投稿のidを送る
      },
    })

    
    //通信成功した時の処理
    .done(function (data) {
      $this.toggleClass('limited'); //limitedクラスのON/OFF切り替え。
      //console.log(data.newStatus,'情報が伝達されているか');
      $this.html(data.newStatus);//ボタンの文字を変える。
    })
    //通信失敗した時の処理
    .fail(function () {
      console.log('fail'); 
    });
  });
  });