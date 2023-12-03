@extends('layouts.app')
 
@section('content')

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('/css/style.css')  }}" >
<title>申請入力フォームの作成（複写）</title>
{{-- @vite(['resources/js/app.js']) --}}
</head>

<h2>確認者画面</h2>
{{-- $postを$postsにしたらエラーがなくなったのはなぜ？ --}}

{{-- HTTPリクエストメソッドでデータを渡す記述とルーティング設定でname()を定義したのでroute()でデータベースに渡す記述 --}}
<form  method="post" action="{{route('post.update',['id=>$posts->id'])}}">
  @csrf
        {{-- 自動表示 --}}
        
        <div class="form-group col-1">
            <label class="label">申請No</label>
            <input class="form-control" type="text" name="application_number" value="{{$posts->id}}">
        </div>
        {{-- 自動表示取得 --}}
        <div class="form-group col-1">
            <label class="label">申請日付</label>
            <input class="form-control" type="text" name="application_day" value="{{$posts->application_day}}">
        </div>

        {{-- データベースから取得し表示 --}}
        <div class="form-group col-1">
            <label class="label">部署名</label>
             <select class="form-control" name="department_name" value="{{$posts->department_name}}">
             @foreach ($departments as $department)
             <option value="{{$department->id}}">{{$department->department_name}}</option> 
            @endforeach
        </select>
        </div>

        {{-- 自動表示取得 --}}
        <div class="form-group col-1">
             <label class="label">申請者名</label>
             <input class="form-control" type="text" name="employee_name" value="{{$posts->employee_name}}">
        </div>

        <div class="form-group col-2">
             <label class="label">購入先</label>
             <input class="form-control" type="text" name="purchase" value="{{$posts->purchase}}">
        </div>
        <div class="form-group col-1">
             <label class="label">購入先URL</label>
             <input class="form-control" type="text" name="delivery_day" value="{{$posts->delivery_day}}">
        </div>
        {{-- テキスト入力 --}}
        <div class="form-group col-4">
             <label class="label">利用目的</label>
             <textarea class="form-control" name="purpose_of_use" value="{{$posts->purpose_of_use}}"></textarea>
        </div>

        {{-- 自動表示 --}}
        <div class="form-group col-1">
            <label class="label">納品希望日</label>
            <input class="form-control" type="text" name="delivery_hope_day" value="{{$posts->delivery_hope_day}}">
       </div>
       <div class="form-group col-1">
        <label class="label">納品日</label>
        <input class="form-control" type="text" name="delivery_day" value="{{$posts->delivery_day}}">
       </div>
       <div class="form-group col-1">
        <label class="label">商品区分</label>
        <input class="form-control" type="text" name="consumable_equipment" value="{{$posts->consumable_equipment}}">
       </div>


{{-- 横並び項目 入力項目を増やすように設定する --}}
<div id="table-block">
    <button type="button">購入項目追加</button>
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
              <select class="form-control" name="consumables_equipment" value="{{$posts->consumables_equipment}}">
              @foreach ($consumables as $consumable)
              <option value="{{$consumable->id}}">{{$consumable->consumables_equipment}}</option>    
              @endforeach
              </select>
          </td>
          <td>
              <input type="text" class="form-control"  name="product_name" value="{{$posts->product_name}}">
          </td>
          <td>
          <input type="text" class="form-control"  value="0" name="unit_purchase_price" pattern="^[0-9]+$" value="{{$posts->unit_purchase_price}}">
          </td>
          <td>
          <input type="text" class="form-control"  value="0" name="purchase_quantity" pattern="^[0-9]+$"required value="{{$posts->purchase_quantity}}">
          </td>
          <td>
          
            <input type="text" class="form-control" name="unit" value="{{$posts->unit}}">
          </td>
          <td>
            
            <select class="form-control" name="account" value="{{$posts->account}}">
              @foreach ($accounts as $account)
                <option value="{{$account->id}}">{{$account->account}}</option>
              @endforeach
            </select>
          </td>
          <td class="clear-column close-icon">✖</td>
      </tbody>
    </table>
  </div>

{{-- 横並び項目（ここまで） --}}  



     {{-- 金額合計関係（自動計算）算術演算子の設定 --}}
    <div class="form-group col-2 my-4">
        <label class="label">小計</label>
        <input class="form-control" type="text" value="{{$posts->subtotal}}" name="subtotal">
    </div>

    
    <div class="form-group col-1 my-4">
        <label class="label">消費税</label>
        <input class="form-control" type="text" value="{{$posts->tax_amount}}" name="tax_amount">
    </div>

    <div class="form-group col-2 my-4">
        <label class="label">発注金額合計</label>
        <input class="form-control" type="text" value="{{$posts->total_amount}}" name="total_amount">
    </div>
     {{-- 金額合計関係（ここまで） --}}

     {{-- 計算のためのコード --}}
     

     {{--選択された部署によりメールアドレスの表示条件が変更 --}}
     <div class="form-group col-1">
        <label class="label">部署名</label>
         <select class="form-control" name="department_name" value="{{$posts->department_name}}">
         @foreach ($departments as $department)
         <option value="{{$department->id}}">{{$department->department_name}}</option> 
        @endforeach
    </select>
    </div>

     {{-- 送信先メールアドレスの選択(正規化処理をする&アカウント登録してなければエラーが出るように設定する) --}}
     <div class="col-4 my-4">
        <label class="label">メールアドレス</label>
        <select class="form-control" type="email" name="mail_address" value="">
            @foreach ($emails as $email)
               <option value="{{$email->id}}">{{$email->email}}</option>
            @endforeach
        </select>
    </div>

    {{-- テキスト入力 --}}
    <div class="form-group col-4">
      <label class="label">備考</label>
      <textarea class="form-control" name="remarks" value=""></textarea>
    </div>


       
    {{-- ボタン配置 --}}
        <div class="btn-area">
            <button type="submit" class="btn btn-primary add" style="margin: 100px">承認</button>
            {{-- 差戻ならば、備考欄の理由を添えたメールが申請者に送信され、申請者は編集して再申請 --}}
            <button type="submit" class="btn btn-primary add" style="margin: 100px">差戻</button>
        </div>
    {{-- ボタン配置（ここまで） --}}

    </div>
    </form>

<script src="{{ asset('/js/applicant.js') }}"></script>
  </body>
</html>
@endsection