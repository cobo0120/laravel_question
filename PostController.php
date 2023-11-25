<?php
// 名前空間
namespace App\Http\Controllers;


use App\Models\Department;
use App\Models\Consumable;
use App\Models\Account;
use App\Models\User;
use App\Models\Post;
use App\Models\Item;



use Illuminate\Http\Request;

// メールクラスのUse宣言
use App\Mail\SendTestMail;
// 認証しているユーザーにデータを渡すためのuse宣言
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\DB;








class PostController extends Controller
{
// メニュー選択画面を表示させる
    public function index(){
       

        return view('posts.index');
        

    }


// 申請入力画面（CRUDでいうcreate)をViewに渡しユーザーに表示させる
public function create_applicant(Request $request)
{
    // 一覧選択
    $consumables = Consumable::all();
    $accounts = Account::all();
    // 検索機能の実装
    // $users=User::all();
    // $search = $request->search;
    // $query = User::search($search);//クエリのローカルスコープ
    // $users = $query->select('id','name','department_id','email')->paginate(2);// idと購入先を検索画面をページネーションで表示されるページを調整（ここはいらない）
    // dd($users);
    // $user = User::where('name',$request->input)->get();
    // $param =['input'=>$request->input, 'user'=> $user];
    return view('posts.create_applicant',compact('consumables','accounts'));
}

// テーブルデータを取得してビューに渡す
public function data_destination(Request $request)
{
 
    // データを取得（1〜5件取ってくる）各ページごとに最初の行をスキップしてデータを取得するように設定
    $users = User::offset(($request->page-1)*3)->limit(3)->get();
    foreach($users as $user){
        $user['department'] = $user->department()->get();
    }
    $allUsers=User::all();
    
    // データ数を取得
    // $dataCount = count($allUsers);
    // ページ番号の最大値を取得
    $pageMax = ceil(count($allUsers) / 3);
    // // ページ番号を生成するための配列を作成
    // $pageNumbers = [];
    // // ページ番号を生成する
    // for ($i = 1; $i <= $pageMax; $i++) {
    // $pageNumbers[] = $i;
    //   }

    // 部署名を表示させるには多次元連想配列を使う？
    return response()->json([
        'users' => $users,  
        'pageMax' => $pageMax,]);
    
}

public function search_destination(Request $request)
{

     // リクエストから検索ワードを取得
        $search = $request->input('search');

        // Userモデルを使用してデータベースから検索
        $names = User::where('name', 'like', "%{$search}%")->get();
        
        // レスポンスとしてJSONデータを返す
        return response()->json([
            'names' => $names,  
            ]);
}



// 上長承認画面上の表示
// public function authorizer()
// {
//     return view('posts.applicant_t',compact('departments','consumables','accounts','emails'));
  
// }

// storeメソッドを用いてモデルにパラメータを渡す
   public function store(Request $request){
    $post = new Post();
    $post->application_status = $request->input('application_status');//申請ステータスは常に１とする。意味は、上長or購買担当者確認中
    $post->application_day = $request->input('application_day');
    $post->user_id = $request->input('user_id');
    $post->department_id = $request->input('department_id');
    $post->purchase = $request->input('purchase');
    $post->purchasing_url = $request->input('purchasing_url');
    $post->purpose_of_use = $request->input('purpose_of_use');
    $post->delivery_hope_day = $request->input('delivery_hope_day');
    //カンマを消す 
    $post->subtotal = str_replace(',', '', $request->input('subtotal'));
    $post->tax_amount = str_replace(',', '', $request->input('tax_amount'));
    $post->total_amount = str_replace(',', '', $request->input('total_amount'));
    $post->remarks = $request->input('remarks');
    $post->delivery_day = $request->input('delivery_day');
    $post->save();
    
    
    $i = 0;
    foreach ($request->input('consumables_equipment_id') as $val) {
    $item = new Item();
    $item->post_id = $post->id;
    $item->consumables_equipment_id = $request->input('consumables_equipment_id')[$i];
    $item->product_name = $request->input('product_name')[$i];
    $item->unit_purchase_price = $request->input('unit_purchase_price')[$i];
    $item->purchase_quantities= $request->input('purchase_quantities')[$i];
    $item->units = $request->input('units')[$i];
    $item->account_id = $request->input('account_id')[$i];
    $item->save();
    $i++;
    };

    
    
    // return redirect()->route('posts.index')->with('flash_message', '申請されました。');
    $user = new User();
    Mail::to($request->email)->send(new SendTestMail($post,$item));

    return redirect()->route('posts.index')->with('messages','購入申請しました');
   }


// WEB申請の履歴一覧を表示
// タイプヒントと依存注入 
// 申請済みデータを閲覧履歴に表示させるための設定
public function index_history(Request $request){
    // 検索機能
    
    $search = $request->search;
    $query = Post::search($search);//クエリのローカルスコープ
    $posts = $query->select
    ('id','application_status','application_day','department_id','user_id','purchase','delivery_hope_day','total_amount','total_amount')->paginate(10);// idと購入先を検索画面をページネーションで表示されるページを調整
    
    $items = Item::all();
    
   
    // $posts = Post::paginate(5); これがあるとエラーが起こるのはなぜ？
    // dd($items);
    return view('posts.index_history',compact('posts','items'));
}


//申請時の詳細画面
public function show_applicant($id){
    $posts = Post::find($id);
    return view('posts.show_applicant', compact('posts'));
}






// 削除機能（取下機能）
public function destroy($id)
{
    $posts = Post::find($id);
    $posts -> delete();
    return to_route('posts.index');
}


// 複写機能の実装のため詳細画面を編集できるような処理を実行
public function edit_applicant($id){
    $departments = Department::all();
    $consumables = Consumable::all();
    $accounts = Account::all();
    $emails = User::all();
    $posts =Post::find($id);
    return view('posts.edit_applicant',compact('posts','departments','consumables','accounts','emails'));
}

// 確認者（上長）の時点においてeditアクションを用いてビューに指定の変数を渡す処理
public function create($id)
{
    $posts = Post::find($id);
    $departments = Department::all();
    $consumables = Consumable::all();
    $accounts = Account::all();
    $emails = User::all();

    return view('posts.edit',compact('posts','departments','consumables','accounts','emails'));

}







// 承認確認画面をViewに渡しユーザーに表示させる
public function update(Request $request, $id){
    
    $post =Post::find($id);
    $post->application_status = 2;//申請ステータスは常に2とする。意味は購買担当者確認中
    $post->application_day = $request->input('application_day');
    $post->employee_name = $request->input('employee_name');
    $post->department_name = $request->input('department_name');
    $post->purchase = $request->input('purchase');
    $post->delivery_day = $request->input('delivery_day');
    $post->consumable_equipment = $request->input('consumable_equipment');
    $post->product_name = $request->input('product_name');
    $post->unit_purchase_price = $request->input('unit_purchase_price');
    $post->purchase_quantity = $request->input('purchase_quantity');
    $post->subtotal = $request->input('subtotal');
    $post->tax_amount = $request->input('tax_amount');
    $post->total_amount = $request->input('total_amount');
    $post->unit = $request->input('unit');
    $post->account = $request->input('account');
    $post->mail_address = $request->input('mail_address');//valueが数値になる
    $post ->save();
    return to_route('posts.index_history');
}



// プロフィール画面
public function profile(){
    $profiles = Auth::user();
    return view('posts.profile',compact('profiles'));
}
// パスワード変更画面（プロフィール画面から遷移させる設定をビューで行う）
public function edit_password(){
    return view('posts.edit_password');
    
}




// パスワードアップデート
// public function update_password(Request $request)
//      {
//          $validatedData = $request->validate([
//              'password' => 'required|confirmed',
//          ]);
 
//          $user = Auth::user();
 
//          if ($request->input('password') == $request->input('password_confirmation')) {
//              $user->password = bcrypt($request->input('password'));
//              $user->update();
//          } else {
//              return to_route('posts.edit_password');
//          }
 
//          return to_route('posts');
//      }

// mailの設定
public function send()
{
    $posts =Post::all();
    $user =User::all();
    $mailable = new SendTestMail($posts,$user);
    // dd($mailable);
    Mail::send($mailable);
    return redirect()->route('posts');
    
}



// （予備）申請入力画面（CRUDでいうcreate)をViewに渡しユーザーに表示させる
public function applicant_t()
{
   $departments = Department::all();
   $consumables = Consumable::all();
   $accounts = Account::all();
   $emails = User::all();


   return view('posts.applicant_t',compact('departments','consumables','accounts','emails'));
   

}



// public function choiceEmail($id) {
//     // データベースからメールアドレスを取得する
//     $email = DB::table("users")
//     ->where("id", $id)
//     ->value("email");

//     // メールアドレスを返す
//     return response()->json([
//       "email" => $email,
//     ]);
//   }


// public function search(Request $request, User $user)
// {
  
//     $consumables = Consumable::all();
//     $accounts = Account::all();

//     // 1対多 親->子
//     // $posts = Consumable::find(1)->posts();
//     // 検索機能の実装
//     $search = $request->search;
//     $query = User::search($search);//クエリのローカルスコープ
//     $users = $query->select('id','name','department_id','email')->paginate(2);// idと購入先を検索画面をページネーションで表示されるページを調整
//     return view('search',compact('consumables','accounts','users'));
// }


// 申請時の詳細画面
// public function show_applicant($id){
//     $departments = Department::all();
//     $consumables = Consumable::all();
//     $accounts = Account::all();
//     $emails = User::all();
//     $posts =Post::find($id);
//     return view('posts.show_applicant',compact('posts','departments','consumables','accounts','emails'));
// }



}