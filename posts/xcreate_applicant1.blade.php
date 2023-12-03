@extends('layouts.app')
 
@section('content')

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('/css/style.css')  }}" >
<link href="css/selmodal.css" rel="stylesheet">
<title>申請入力フォームの作成</title>
{{-- @vite(['resources/js/app.js']) --}}
</head>


<body>

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
            <input type="hidden" name="department_id" value="1">
        </select>
        </div>

        {{-- 自動表示取得 --}}
        <div class="form-group col-1">
             <label class="label">申請者名<br>（自動表示）</label>
             <input class="form-control" type="text" name="" value="{{ auth()->user()->name }}">
             <input type="hidden" name="user_id" value="1">
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
    <button type="button" id="add-item">購入項目追加</button>
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
            <input type="text" class="form-control" name="unit">
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

     {{-- 送信先メールアドレスの選択検索機能を設けてアドレスのパラメータを変数に入れてMailableクラスに入れる --}}
     

     <div class="form-group col-2 my-4">
      <label class="label">送信先入力</label>
      <input class="form-control" type="" value="" name="" >
    </div>

    
    <a href="{{route('posts.show_destination')}}"><button class="btn btn-success" type="button">送信先検索</button></a>

    <div>通常のセレクト</div>
<select name="default">
    <option value="0">選択してください。</option>
    <option value="1">選択肢1</option>
    <option value="2">選択肢2</option>
    <option value="3">選択肢3</option>
    <option value="4">選択肢4</option>
    <option value="5">選択肢5選択肢5選択肢5選択肢5選択肢5選択肢5選択肢5選択肢5選択肢5選択肢5選択肢5</option>
    <option value="6">選択肢6</option>
    <option value="7">選択肢7</option>
    <option value="8">選択肢8</option>
    <option value="9">選択肢9</option>
    <option value="10">選択肢10</option>
</select>

<div>selmodal.jsのセレクト</div>
<select name="test" class="selmodaltest">
    <option value="0">選択してください。</option>
    <option value="1">選択肢1</option>
    <option value="2">選択肢2</option>
    <option value="3">選択肢3</option>
    <option value="4">選択肢4</option>
    <option value="5">選択肢5選択肢5選択肢5選択肢5選択肢5選択肢5選択肢5選択肢5選択肢5選択肢5選択肢5</option>
    <option value="6">選択肢6</option>
    <option value="7">選択肢7</option>
    <option value="8">選択肢8</option>
    <option value="9">選択肢9</option>
    <option value="10">選択肢10</option>
</select>

<!-- Jqueryを読み込む -->
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js?ver=1.12.4'></script>
<!-- プラグインのjsを読み込む -->
<script src="js/Jquery.selmodal.js"></script>
<script>
$(function(){
    //後者のセレクトをプラグインに適用
    $('.selmodaltest').selModal();
    //すべてのセレクトボックスに同じ処理する場合は下記のように書く
    //$('select').selModal();
});
</script>

       
    {{-- ボタン配置 --}}
        <div class="btn-area">
            <a href=""><button type="submit" class="btn btn-primary add" style="margin: 100px">申請</button></a>
            
            <button type="submit" class="btn btn-primary add" style="margin: 100px">戻る</button>
        </div>
    {{-- ボタン配置（ここまで） --}}

    </div>
    </form>

<script src="{{ asset('/js/applicant.js') }}"></script>
{{-- <script> var consumables = @json($consumables);</script> --}}
  </body>
</html>
@endsection