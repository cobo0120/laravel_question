@extends('layouts.app')
 
@section('content')

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="{{ asset('/css/style.css') }}" >
<title>申請入力フォームの作成</title>
{{-- @vite(['resources/js/app.js']) --}}
</head>

<body>
  <div class="container">

<h2>過去歴詳細</h2>
{{-- $postを$postsにしたらエラーがなくなったのはなぜ？ --}}
<div class="text-right">
  <a href="{{route('post.create_copy_applicant',['id'=>$posts->id])}}"><button class="btn btn-warning">複写新規</button></a>
</div>
{{-- HTTPリクエストメソッドでデータを渡す記述とルーティング設定でname()を定義したのでroute()でデータベースに渡す記述 --}}
{{-- ステータス上長承認へ--}}
<input class="form-control" type="hidden" name="application_status" value="1">
             
        
                {{-- 自動表示 --}}
                <div class="form-group col-1 mb-2">
                  <label class="label">申請No<br></label>
                  <input class="form-control" type="text" name="" value="{{$posts->id}}" readonly>
              </div>
        
                
                
                {{-- 自動表示取得 --}}
                <div class="form-group col-1 mb-2">
                    <label class="label">申請日付<br></label>
                    <input class="form-control" type="text"  name="application_day" value="{{$posts->application_day}}" style="width: 100px"; readonly>
                </div>
        
                {{-- データベースから取得し表示 --}}
                <div class="form-group col-1 mb-2">
                    <label class="label">部署名<br></label>
                    {{-- @php
                        var_dump(auth()->user()->department->department_name);
                    @endphp --}}
                    <input class="form-control" type="text" name="" value="{{$posts->department->department_name}}" readonly>
                    {{-- <input type="hidden" name="department_id" value="{{auth()->user()->department_id}}"> --}}
                    {{-- <input type="hidden" name="department_id" value="2"> --}}
                </select>
                </div>
        
                {{-- 自動表示取得 --}}
                <div class="form-group col-2 mb-2">
                     <label class="label">申請者名<br></label>
                     <input class="form-control" type="text" name="" value="{{ $posts->user->name }}" readonly>
                     {{-- <input type="hidden" name="user_id" value="{{ auth()->user()->id }}"> --}}
                </div>
                <div class="form-group col-4 mb-2">
                     <label class="label">購入先</label>
                     <input class="form-control" type="text" name="purchase" value="{{$posts->purchase}}" readonly>
                </div>
                <div class="form-group col-6 mb-2">
                     <label class="label">購入先URL</label>
                     <input class="form-control" type="text" name="purchasing_url" value="{{$posts->purchasing_url}}" readonly>
                </div>
                {{-- テキスト入力 --}}
                <div class="form-group col-4 mb-2">
                     <label class="label">利用目的</label>
                     <input class="form-control" name="purpose_of_use" value="{{$posts->purpose_of_use}}" readonly>
                </div>
        
                {{-- 自動表示 --}}
                <div class="form-group col-2 mb-2">
                    <label class="label">納品希望日</label>
                    <input class="form-control" type="text" name="delivery_hope_day" style="width: 125px" value="{{$posts->delivery_hope_day}}" readonly>
               </div>
        
        
        {{-- 横並び項目 入力項目を増やすように設定する --}}
        <div id="table-block" class="center mb-2">
            {{-- <button class="btn btn-outline-info" type="button" id="add-item" value="">購入項目追加</button> --}}
            <table id="item_table" class="table table-bordered table table-hover">
              <thead class="table table-info">
                <tr>
                  <th style="width: 150px;">区分</th>
                  <th >商品名</th>
                  <th style="width: 150px">購入単価</th>
                  <th style="width: 100px;">数量</th>
                  <th style="width: 100px;">単位</th>
                  <th style="width: 100px;">勘定科目</th>
                  <th class="clear-column"></th>
                </tr>
              </thead>
              <tbody>
                @foreach($items as $item)
                <tr class="item">
                  <td>
                      <input class="form-control" name="consumables_equipment_id[0]" value="{{$item->consumables_equipment_id}}" readonly>
                  </td>
                  <td>
                      <input type="text" class="form-control"  name="product_name[0]" value="{{$item->product_name}}" readonly>
                  </td>
                  <td>
                  <input type="text" class="form-control text-right" size="5" name="unit_purchase_price[0]" pattern="^[0-9]+$" value="{{$item->unit_purchase_price}}" readonly>
                  </td>
                  <td>
                  <input type="text" class="form-control text-right" size="3" name="purchase_quantities[0]" pattern="^[0-9]+$"required value="{{$item->purchase_quantities}}" readonly>
                  </td>
                  <td>
                    <input type="text" class="form-control text-right" size="3" name="units[0]" value="{{$item->units}}" readonly>
                  </td>
                  <td>
                      <input class="form-control" name="account_id[0]" value="{{$item->account_id}}" readonly>
                  </td>
                  <td class="clear-column close-icon">✖</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        
        {{-- 横並び項目（ここまで） --}}  
        
        
        
             {{-- 金額合計関係（自動計算）算術演算子の設定 --}}
             <div class="form-group col-1 my-4" id="total">
              <label class="label">小計</label>
              <input class="form-control text-right" type="text" name="subtotal" value="{{ number_format($posts->subtotal, 0, ',', ',') }}" readonly>
           </div>
           
           <div class="form-group col-1 my-4">
              <label class="label">消費税</label>
              <input class="form-control text-right" type="text" name="tax_amount" value="{{ number_format($posts->tax_amount, 0, ',', ',') }}" readonly>
           </div>
           
           <div class="form-group col-1 my-4">
              <label class="label font-weight-bold">発注金額合計</label>
              <input class="form-control text-right" type="text" name="total_amount" value="{{ number_format($posts->total_amount, 0, ',', ',') }}" readonly>
           </div>
           
             {{-- 金額合計関係（ここまで） --}}
             
        {{-- 送信先メールアドレスの選択検索機能を設けてアドレスのパラメータを変数に入れてMailableクラスに入れる --}}
       
          <div class="form-group col-4 my-4">
           <label for="email">送信先</label>
           <input id="email" class="form-control choice-email" type="email" value="{{$posts->destination}}" name="email" readonly>
         </div>


         {{-- 備考欄 --}}
         <div class="form-group col-4 mb-2">
            <label class="label">備考</label>
            <input class="form-control remaks" name="remarks" value="{{$posts->remarks}}"  style="height: 100px;" readonly>
        </div>

        {{-- 差戻理由 --}}
        <div class="form-group col-4 mb-2">
          <label class="label">差戻理由</label>
          <input class="form-control remaks" name="remarks" value="{{$posts->reason_for_remand}}"  style="height: 100px;" readonly>
        </div>


         {{-- 納品予定日 --}}
         <div class="form-group col-2 mb-2">
           <label class="label">納品予定日</label>
           <input class="form-control" style="width: 130px" type="date" name="delivery_day" style="width: 125px" value="{{$posts->delivery_day}}" readonly>
         </div>
        
         
          {{-- ボタン配置 --}}
          <a href="{{route('posts.index_history')}}"><button type="button" class="btn btn-outline-success btn-lg" style="width: 100px">戻る</button></a>
        </div>


        <div class="container mt-5">
          <footer>        
             <p>&copy; WEB申請アプリ All rights reserved.</p>
          </footer>
        </div>

        </body>
        </html>
        @endsection