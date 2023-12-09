@extends('layouts.app')
 
@section('content')


<!DOCTYPE html>
 <html lang="ja">
 
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>WEB申請一覧</title>
     <link rel="stylesheet" href="css/style.css">
     <link rel="stylesheet" href="css/index.css">
 </head>
 

 <body>
  <div class="container">
     <main>
        <article class="">
             <h3>WEB申請履歴一覧</h3>
                <div>
                     <!-- ここに並検索ボックスを作成する -->
                     <form method="get" action="{{route('posts.index_history')}}"  class="form-group col-2" >
                        @csrf
                        <input type="text"  name="search" class="form-control mb-4" placeholder="購入先入力">
                        <button class="btn btn-outline-success mb-4">検索する</button>
                    </form>
                </div>
                
             <table class="table table-hover">
                <thead>
                  <tr>
                    <th>申請No</th>
                    <th>申請状況</th>
                    <th>申請日付</th>
                    <th>部署名</th>
                    <th>申請者名</th>
                    <th>購入先</th>
                    <th>納品希望日</th>
                    <th>発注金額合計</th>
                    <th>送信先</th>
                    <th>納品予定日</th>
                    <th>詳細</th>
                    <th>再申請/取下</th>
                    <th>複写</th>
                    <th>承認</th>
                  </tr>
                </thead>
                <tbody>
                     @foreach ($posts as $post) 
                     {{-- @php
                     var_dump($posts);
                     @endphp --}}
                     {{-- @if ($post->user || $post->item || $post->department) --}}
                        <tr>
                        <td>{{$post->id}}</td> 
                        <td>
                     @if ($post->application_status == 1)
                     <span style="color: red;">確認中</span>
                     @elseif ($post->application_status == 2)
                     <span style="color: green;">確認済</span>
                     @elseif ($post->application_status == 3)
                     <span style="color: blue;">発注済</span>
                     @else
                     <span style="color: olive;">差戻中</span>
                     @endif
                        </td>
                        <td>{{$post->application_day}}</td>
                        <td>{{$post->department->department_name}}</td>
                        <td>{{$post->user->name}}</td>
                        <td>{{$post->purchase}}</td>
                        <td>{{$post->delivery_hope_day}}</td>                       
                        <td>{{ number_format($post->total_amount, 0, ',', ',') }}</td>                                                              
                        <td>{{$post->destination}}</td>
                        <td>{{$post->delivery_day}}</td> 
                        
                        {{-- 各項目の詳細画面へのリンク --}}
                        <td><a href="{{route('post.show_applicant',['id'=>$post->id])}}">閲覧</a></td>
                        @if ($post->application_status == 3)
                        <td><span style="">不可</span></td>
                        @else
                        <td><a href="{{route('post.edit_reapplication',['id'=>$post->id])}}">再申請/取下</a></td>
                        @endif
                        <td><a href="{{route('post.create_copy_applicant',['id'=>$post->id])}}">複写</a></td>
                        <td>
                         @if ($post->application_status == 1)
                           <a href="{{route('post.create_authorizer',['id'=>$post->id])}}" style="color: red;">確認者</a>
                         @elseif ($post->application_status == 2)
                           <a href="{{route('post.create_order',['id'=>$post->id])}}" style="color: green;">注文者</a>
                         @elseif ($post->application_status == 4)
                           <span style="color:olive">再申請待ち</span>
                         @else 
                         <span style="center">完了</span>
                         @endif
                        </td>
                        </tr>
                        {{-- @endif --}}
                     @endforeach                                       
                </tbody>
               </table>
            </article>
     </main>

     <div>
     {{ $posts->links() }}
    </div>
  </div>
  
    <div class="container">
     <footer>
         <p class="copyright">&copy; WEB申請アプリ All rights reserved.</p>
     </footer>
    </div>
 </body>
 
 </html>
 @endsection