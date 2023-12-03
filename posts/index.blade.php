@extends('layouts.app')
 
@section('content')
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('/css/index.css')  }}" >
    <title>申請アプリメニュー選択画面</title>
</head>

<body>
    
<div class="container">
    <h3>WEB申請メニュー選択</h3>
        <div class="row justify-content-around mt-5">

            <div class="col-6 mb-3 wave" style="max-width: 18rem;">
              <div class="card ">
                <a href="{{ route('posts.create_applicant') }}" class="text-decoration-none" style="color: black">
                <h5 class="card-header card-title text-center bg-primary-subtle">備品・消耗品購入申請</h5>
                  <div class="card-body">
                  <p class="card-text text-center">備品・消耗品<br>購入申請手続き</p>
                 </div>
                </a>
               </div>
            </div>
            
        
            <div class="col-6 mb-3 wave" style="max-width: 18rem;">
              <div class="card">
                <a href="{{ route('posts.index_history') }}" class="text-decoration-none" style="color: black">
                    <h5 class="card-header card-title text-center bg-success-subtle">申請過去歴</h5>
                <div class="card-body">
                  <p class="card-text text-center">全申請者過去歴<br>閲覧・再申請・取下・複写・承認</p>
                </div>
                </a>
              </div>
            </div>


            <div class="col-6 mb-3 wave" style="max-width: 18rem;">
               <div class="card">
                <a href="{{ route('users.profile') }}" class="text-decoration-none" style="color: black">
                    <h5 class="card-header card-title text-center bg-danger-subtle">プロフィール閲覧</h5>
                  <div class="card-body">
                    <p class="card-text text-center">申請者プロフィール閲覧<br>パスワード変更</p>
                  </div>
                </a>
               </div>
            </div>   
            






    </div>
</div>

        {{-- <a href="{{ route('posts.create_applicant') }}" class="mx-auto  mb-5"><button type="button" class="btn btn-primary btn-lg mt-5">WEB申請</button></a>
    
        <a href="{{ route('posts.index_history') }}" class="mx-auto  mb-5"><button type="button" class="btn btn-primary btn-lg mt-5">申請履歴</button></a>
    
        <a href="{{ route('posts.profile') }}" class="mx-auto  mb-5"><button type="button" class="btn btn-primary btn-lg mt-5">プロフィール閲覧</button></a> --}}
   



<div class="container mt-5">
 <footer>        
    <p>&copy; WEB申請アプリ All rights reserved.</p>
 </footer>
</div>
</body>



</html>
@endsection
