@extends('layouts.app')
 
@section('content')

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('/css/style.css')  }}" >
<title>申請入力フォームの作成</title>
{{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>


<body class="container">

{{-- データを渡す記述 --}}
<h2>新規申請</h2>
<form  method="post" action="{{route('post.store')}}">
  @csrf
       {{-- ステータス --}}
        <input class="form-control" type="hidden" name="application_status" value="1">
     

        {{-- 自動表示 --}}
        <div class="form-group col-1">
          <label class="label">申請No<br>（自動表示）</label>
          <input class="form-control" type="text" name="" value="自動採番">
      </div>

        
        
        {{-- 自動表示取得 --}}
        <div class="form-group col-1">
            <label class="label">申請日付<br>（自動表示）</label>
            <input class="form-control" type="text" name="application_day" value="{{ date('Ymd') }}">
        </div>

        {{-- データベースから取得し表示 --}}
        <div class="form-group col-1">
            <label class="label">部署名<br>（自動表示）</label>
            {{-- @php
                var_dump(auth()->user()->department->department_name);
            @endphp --}}
            <input class="form-control" type="text" name="" value="{{auth()->user()->department->department_name}}">
            <input type="hidden" name="department_id" value="{{auth()->user()->department->department_id}}">
        </select>
        </div>

        {{-- 自動表示取得 --}}
        <div class="form-group col-1">
             <label class="label">申請者名<br>（自動表示）</label>
             <input class="form-control" type="text" name="" value="{{ auth()->user()->name }}">
             <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        </div>
        <div class="form-group col-2">
             <label class="label">購入先</label>
             <input class="form-control" type="text" name="purchase" value="{{old('purchase')}}">
        </div>
        <div class="form-group col-1">
             <label class="label">購入先URL</label>
             <input class="form-control" type="text" name="purchasing_url" value="{{old('purchasing_url')}}">
        </div>
        {{-- テキスト入力 --}}
        <div class="form-group col-4">
             <label class="label">利用目的</label>
             <textarea class="form-control" name="purpose_of_use" value="{{old('purpose_of_use')}}"></textarea>
        </div>

        {{-- 自動表示 --}}
        <div class="form-group col-1">
            <label class="label">納品希望日</label>
            <input class="form-control" type="text" name="delivery_hope_day" value="{{old('delivery_hope_day')}}">
       </div>


{{-- 横並び項目 入力項目を増やすように設定する --}}
{{-- <form  method="post" action="{{route('item.store')}}">
  @csrf --}}
<div id="table-block" class="center">
    <button class="btn btn-outline-info" type="button" id="add-item">購入項目追加</button>
    <table>
      <thead>
        <tr>
          <th>区分</th>
          <th>商品名</th>
          <th>購入単価</th>
          <th>数量</th>
          <th>単位</th>
          <th>勘定科目</th>
          <th class="clear-column"></th>
        </tr>
      </thead>
      <tbody>
        <tr class="item">
          <td>
              <select class="form-control" name="consumables_equipment" value="{{old('')}}">
              @foreach ($consumables as $consumable)
              <option value="{{$consumable->id}}">{{$consumable->consumables_equipment}}</option>    
              @endforeach
              </select>
          </td>
          <td>
              <input type="text" class="form-control"  name="product_name" value="{{old('product_name')}}">
          </td>
          <td>
          <input type="text" class="form-control"  value="" name="unit_purchase_price" pattern="^[0-9]+$" value="{{old('unit_purchase_price')}}">
          </td>
          <td>
          <input type="text" class="form-control"  value="" name="purchase_quantity" pattern="^[0-9]+$"required value="{{old('purchase_quantity')}}">
          </td>
          <td>
            <input type="text" class="form-control" size="5" name="unit">
          </td>
          <td>
              <select class="form-control" name="account" value="{{old('')}}">
              @foreach ($accounts as $account)
                <option value="{{$account->id}}">{{$account->account}}</option>
              @endforeach
            </select>
          </td>
          <td class="clear-column close-icon">✖</td>
      </tbody>
    </table>
  </div>
{{-- </form> --}}
{{-- 横並び項目（ここまで） --}}  



     {{-- 金額合計関係（自動計算）算術演算子の設定 --}}
    <div class="form-group col-2 my-4" id="total">
        <label class="label">小計</label>
        <input class="form-control" type="text" value="0" name="subtotal" value="{{old('subtotal')}}">
    </div>

    
    <div class="form-group col-1 my-4">
        <label class="label">消費税</label>
        <input class="form-control" type="text" value="0" name="tax_amount" value="{{old('tax_amount')}}">
    </div>

    <div class="form-group col-2 my-4">
        <label class="label">発注金額合計</label>
        <input class="form-control" type="text" value="0" name="total_amount" value="{{old('total_amount')}}">
    </div>
     {{-- 金額合計関係（ここまで） --}}

    

    

    <!-- モーダル機能ボタン -->
   <button type="button" class="btn btn-outline-primary destination" data-bs-toggle="modal" data-bs-target="#staticBackdrop">送信先検索</button>

    <!-- モーダル機能-->
  
   <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    
    <div class="modal-dialog modal-fullscreen">
       <div class="modal-content">
         <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">送信先検索</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
      <div class="modal-body">
          <div class="form-group col-2 my-4 " > 
            <input type="search" id="search-input" name="search" class="form-control"  placeholder="氏名検索" value="">
            <button id="search-destination" class="btn btn-success my-4">検索する</button>
          </div>
        
        
          <table class="table table-hover">
            <thead>
              <tr>
                <th>No.</th>
                <th>氏名</th>
                <th>部署名</th>
                <th>メールアドレス</th>
                <th>送信先選択</th>
              </tr>
            </thead>
            <tbody id="destination_body">
                 {{-- @foreach ($users as $user) 
                    <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->department_id}}</td>
                    <td>{{$user->email}}</td>
                    {{-- 各項目へのリンク --}}
                
                    {{-- <td><button type="button" class="btn btn-secondary" onclick="choiceButton('{{$user->email}}')" data-bs-dismiss="modal">選択</button></td>
                    </tr>
                 @endforeach --}}
            </tbody>
           </table>
        
           <div id="page-nate"></div>
            {{-- <button  type="button" class="btn btn-primary add" data-bs-dismiss="modal"></button> --}}
           

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
      </div>
    </div>
  </div>
</div>

{{-- 送信先メールアドレスの選択検索機能を設けてアドレスのパラメータを変数に入れてMailableクラスに入れる --}}
<form action="" method="get">
  <div class="form-group col-2 my-4">
   <label class="label">送信先</label>
   <input class="form-control choice-email" type="email" value="" name="" >
 </div>
</form>
 
    {{-- ボタン配置 --}}
        <div class="btn-area">
            <a href=""><button type="submit" class="btn btn-success btn-lg" style="margin: 200px" >申請</button></a>
            <a href="{{route('posts.index')}}"><button type="button" class="btn btn-warning btn-lg" style="margin: 200px">キャンセル</button></a>
        </div>
    {{-- ボタン配置（ここまで） --}}

</div>
 </form>
 <script src="{{ asset('/js/applicant.js') }}"></script>
 <script type="module" src="{{ asset('/js/destination.js') }}"></script>
</body>
</html>
@endsection