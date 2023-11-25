// ①送信先検索で非同期処理が開始
// 仕様：繰返し構文を使用してデータ数に応じた（例：５件ごとに）ページ番号を付与していく処理と初期に表示させるデータを取得ページ番号を取得していく処理
// ajax非同期通信データを取得するように設定する
$('.destination').on('click', ()=>{
  $.ajax({
    type: "GET",
    url:"../data_destination",//ルートのURLを入れる
    async: true, // 非同期通信フラグの指定
    dataType:'json',
    data: { page : 1 ,},//ここはサーバーに贈りたい情報。今回は検索ファームのバリューを送りたい。
    
  })
  .done((data)=>{
  // ページ数を取得
  let pageMax = data.pageMax;
  // .page-nateクラスの要素を取得
  let pageNateContainer = $('#page-nate').empty();
  // ページ数分のループを実行し、ボタンを追加
  for (let i = 1; i <= pageMax; i++) {
      // ボタンを作成
      let pageButton = $('<button>', {
          type: 'button',
          class: 'btn btn-outline-primary btn btn-link data-page-button',
          text: `${i}`
      });
      // ボタンにデータを関連付ける（ここではdata-pageというデータ属性を使用）
      pageButton.data('page', i);
      // ボタンをページに追加
      pageNateContainer.append(pageButton);
      // モーダルに対して必要な属性を設定
      pageNateContainer.attr({
        'data-backdrop': 'static',
        'data-bs-dismiss': 'null',         
});
};
  let tbody = $('#destination_body').empty()
   for(let i=0; i<data.users.length; i++){
  let user = data.users[i];
   tbody.append('<tr><td>'+user.id+'</td><td>'+user.name+'</td><td>'+user.department[0].department_name+'</td><td>'+user.email+'</td><td><button type="button" class="btn btn-outline-primary" onclick="choiceButton(\'' + user.email + '\')" data-bs-dismiss="modal">選択</button></td></tr>');
  };
  })
  .fail((error)=>{
    // データがダウンロードできなかったときの処理
    alert('通信失敗');
  })
  // .always(()=>{
  // });
  });

  
 // ③イベントハンドラにて生成されたボタンにデータを表示させるような作りをする
$(document).on('click', '.data-page-button', function() {
  let pageNumber = $(this).data('page'); 
   // AJAXリクエストを作成
   $.ajax({
    type: "GET",
    url: "../data_destination",  // サーバーサイドのスクリプトの実際のURL
    async: true, // 非同期通信フラグの指定
    data: { 'page' : pageNumber  },//ここはサーバーに贈りたい情報。
    datatype: 'json',//json形式で受け取る
   })
    .done((data) => {
      let tbody = $('#destination_body').empty()
      for(let i=0; i<data.users.length; i++){
     let user = data.users[i];
     tbody.append('<tr><td>'+user.id+'</td><td>'+user.name+'</td><td>'+user.department[0].department_name+'</td><td>'+user.email+'</td><td><button type="button" class="btn btn-outline-primary" onclick="choiceButton(\'' + user.email + '\')" data-bs-dismiss="modal">選択</button></td></tr>');
      };
    })

    .fail((error)=> {
        alert("リクエストの処理中にエラーが発生しました。");
    })
});



  // ②検索ボタンが押されたら非同期通信が開始
  // 仕様：検索ボタンが押されたら、非同期処理が開始されるs
  // ajax非同期通信データを取得するように設定する
  $('#search-destination').on('click',() =>{
    const userName = $("input[type='search']").val();
    // 検索ワードが空欄の場合は何もせずに処理を終了
    if (!userName.trim()) {
        return;
    }
    $.ajax({
        type: "GET",
        dataType: 'json',
        async: true,
        url: '../search_destination',
        data: {'search': userName},
    })
    .done((data) => {
        // データがダウンロードできたときの処理
        
        let tbody = $('#destination_body').empty()
        for(let i=0; i<data.names.length; i++){
        let user = data.names[i];
        tbody.append('<tr><td>'+user.id+'</td><td>'+user.name+'</td><td>'+user.department[0].department_name+'</td><td>'+user.email+'</td><td><button type="button" class="btn btn-outline-primary" onclick="choiceButton(\'' + user.email + '\')" data-bs-dismiss="modal">選択</button></td></tr>');
        };
        

        // 取得したデータを使って検索結果を表示する例
        if (data.length > 0) {
            // 検索結果がある場合の処理
            // ここで取得したデータを使って、検索結果を表示するためのコードを追加
            
        } else {
            // 検索結果がない場合の処理
            console.log('該当する結果がありません。');
        }
    })
    .fail((error) => {
        // データがダウンロードできなかったときの処理
        console.error('検索エラー:', error);
    });
});

