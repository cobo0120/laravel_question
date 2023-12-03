@extends('layouts.app')
 
@section('content')

<form method="get" action=""  class="form-group col-2 my-4 " >
    @csrf
    <input type="text"  name="search" class="form-control" placeholder="氏名or部署で検索">
    <button class="btn btn-success my-4">検索する</button>
  </form>


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
    <tbody>
         @foreach ($users as $user) 
            <tr>
            <td>{{$user->id}}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->department_id}}</td>
            <td>{{$user->email}}</td>
            {{-- 各項目へのリンク --}}
        
          <form method="post" action="">
            <td><a href="">選択</a></td>  
          </form>
            </tr>
         @endforeach
    </tbody>
   </table>

   <div>
    {{ $users->links() }}
   </div>

  @endsection