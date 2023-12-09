@extends('layouts.app')
 
@section('content')
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('/css/index.css')  }}" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <title>申請アプリメニュー選択画面</title>
</head>

<body>
<div class="container">
{{-- フラッシュメッセージ --}}
<script>
  @if (session('flash_message'))
      $(function () {
              toastr.success('{{ session('flash_message') }}');
      });
  @endif
</script>

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

        


<div class="container mt-5">
 <footer>        
    <p>&copy; WEB申請アプリ All rights reserved.</p>
 </footer>
</div>

<script src="{{ asset('/js/main.js') }}"></script>
</body>



</html>
@endsection
