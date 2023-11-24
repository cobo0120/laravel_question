<?php

use Illuminate\Support\Facades\Route;

// 追加
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\SendTestMail;


// ルーティングを設定するコントローラーを宣言する
use App\Http\Controllers\PostController;
// ルーティングを設定するコントラーを宣言する（メール）
use App\Http\Controllers\MailSendController;

use App\Http\Controllers\ContactFormController;

use App\Http\Controllers\AjaxTestController;

// use App\Http\Livewire\Destination;

use App\Http\Livewire\Search;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'department']);



// WEB申請メニューに関しての設定

// WEB申請メニュー画面
Route::get('/posts',[PostController::class,'index'])->name('posts.index');
// WEB申請画面を表示
Route::get('/posts/create_applicant',[PostController::class,'create_applicant'])->name('posts.create_applicant');
// 申請画面にて送信先を表示させる
// ①送信先検索ボタンで起動
Route::get('/data_destination',[PostController::class,'data_destination'])->name('data_destination');
// ②検索ボタンで起動
Route::get('/search_destination',[PostController::class,'search_destination'])->name('search_destination');

// 作成用
Route::get('/posts/create',[PostController::class,'create'])->name('posts.create');
// WEB申請画面（create_applicant.php)に入力されたデータのルート設定を追加とnameを設定することによりblade.phpのformタグに省略して渡せる
Route::post('/posts/store',[PostController::class,'store'])->name('post.store');
// Route::post('/posts/store',[ItemController::class,'store'])->name('post.store');
// WEB申請画面（購買担当者）
Route::get('/posts/order',[PostController::class,'order'])->name('post.order');




// 申請履歴一覧の箇所
// WEB申請履歴一覧
Route::get('/posts/index_history',[PostController::class,'index_history'])->name('posts.index_history');
// WEB申請履歴一覧からの詳細画面
Route::get('/posts/show_applicant/{id}',[PostController::class,'show_applicant'])->name('post.show_applicant');
// 削除機能
Route::post('/posts/{id}/destroy',[PostController::class,'destroy'])->name('post.destroy');
// WEB申請履歴一覧からの複写画面・編集
Route::get('/posts/edit_applicant/{id}',[PostController::class,'edit_applicant'])->name('post.edit_applicant');
// 複写画面
Route::get('/posts/create_copy_applicant/{id}',[ItemController::class,'create_copy_applicant'])->name('post.create_copy_applicant');
// 編集画面
Route::get('/posts/{id}/edit',[PostController::class,'edit'])->name('post.edit');
// 編集更新
Route::post('/posts/{id}/update',[PostController::class,'update'])->name('post.update');
// WEB申請画面（上長）の表示
Route::get('/posts/{id}/create_authorizer',[PostController::class,'create_authorizer'])->name('posts.create_authorizer');





// WEBプロフィール画面一覧
Route::get('/posts/profile',[PostController::class,'profile'])->name('posts.profile');
// WEBパスワード変更画面
Route::get('/posts/{posts}/edit_password',[PostController::class,'posts.edit_password'])->name('posts.edit_password');
// パスワード編集画面
Route::controller(PostController::class)->group(function () {
Route::get('/posts/edit_password', 'edit_password')->name('posts.edit_password');
Route::put('/posts/edit_password', 'update_password')->name('posts.update_password');  
});




// 後略
// パスワード変更の編集させるための画面を表示させる
// Route::controller(PostController::class)->group(function () {
//     Route::get('/posts/change_pass', 'change_pass')->name('change_pass');
//     Route::get('/posts/change_pass', 'edit')->name('change_pass.edit');
//     Route::put('/posts/change_pass', 'update')->name('change_pass.update');
// });



// WEB申請画面をユーザーに渡す（テスト画面を表示させる）
// Route::get('/posts/applicant_t',[PostController::class,'applicant_t'])->name('posts.applicant_t');

// Route::get('/posts/show_destination',[PostController::class,'show_destination'])->name('posts.show_destination');





