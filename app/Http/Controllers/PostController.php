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
// 旧書式テストバージョン
use App\Mail\SendTestMail;
// 申請者が自分の申請を承認（申請）した時のメール
use App\Mail\ApplicantApprovalMail;
// 承認者が申請者の申請を承認した時のメール
use App\Mail\AuthorizerApprovalMail;
// 承認者が申請者の申請を差戻した時のメール
use App\Mail\AuthorizerDisapprovalMail;
// 注文者が確認者の承認を承認した時のメール
use App\Mail\OrdererCompletedMail;
// 注文者が確認者の承認を差戻した時のメール
use App\Mail\OrdererDisapprovedMail;
// 申請者が再申請した際のメール
use App\Mail\ReApplicationMail;
use App\Mail\WithdrawAnApplicationMail;
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
        foreach($names as $name){
            $name['department'] = $name->department()->get();
        }
        
        // レスポンスとしてJSONデータを返す
        return response()->json([
            'names' => $names,

            ]);
}



// storeメソッドを用いてモデルにパラメータを渡す
   public function store_applicant(Request $request){
    // dd($request);
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
   
    // メールアドレス欄の追加
    $post->destination = $request->input('destination');
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
    // $user = new User();
    Mail::to($request->destination)->send(new ApplicantApprovalMail($post,$item));

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
    ('id','application_status','application_day','department_id','user_id','purchase','delivery_hope_day','total_amount','destination','delivery_day')->paginate(10);// idと購入先を検索画面をページネーションで表示されるページを調整

    // $items = Item::select('product_name')->get();
    return view('posts.index_history',compact('posts'));
}



//申請時の詳細画面
public function show_applicant($id){
    $posts = Post::find($id);
    $items = $posts->items;
    // dd($items);
    return view('posts.show_applicant', compact('posts', 'items'));
    // dd($items);
}



// 詳細画面を編集できるような処理を実行
public function edit_reapplication($id){
    $consumables = Consumable::all();
    $accounts = Account::all();
    $posts =Post::find($id);
    $items = $posts->items;
    return view('posts.edit_reapplication',compact('posts','items','consumables','accounts'));
}

// 複写機能
public function create_copy_applicant(Request $request, $id)
{
    $posts = Post::find($id);
    $items = $posts->items;
    $consumables = Consumable::all();
    $accounts = Account::all();
    return view('posts.create_copy_applicant',compact('posts','items','consumables','accounts'));
}



// 申請者の再申請
public function update_reapplication(Request $request, $id){
    
    $post =Post::find($id);
    // dd($request);
    $post->application_status = $request->application_status;
    $post->application_day = $request->application_day;
    $post->user_id = $request->user_id;
    $post->department_id = $request->department_id;
    $post->purchase = $request->purchase;
    $post->purchasing_url = $request->purchasing_url;
    $post->purpose_of_use = $request->purpose_of_use;
    $post->delivery_hope_day = $request->delivery_hope_day;

    //カンマを消す 
    $post->subtotal = str_replace(',', '', $request->subtotal);
    $post->tax_amount = str_replace(',', '', $request->tax_amount);
    $post->total_amount = str_replace(',', '', $request->total_amount);
   
    // メールアドレス欄の追加
    $post->destination = $request->destination;
    // 確認者or注文担当者が記載していく項目
    $post->remarks = $request->remarks;
    $post->save();
    
    
    // 複数レコードが渡って来ないのでどうすればいいか?
    $i = 0;
    foreach ($request->consumables_equipment_id as $id) {
    $item =Item::find($id);
    // dd($item);
    $item->post_id = $post->id;
    $item->consumables_equipment_id = $request->consumables_equipment_id[$i];
    $item->product_name = $request->product_name[$i];
    $item->unit_purchase_price = $request->unit_purchase_price[$i];
    $item->purchase_quantities= $request->purchase_quantities[$i];
    $item->units = $request->units[$i];
    $item->account_id = $request->account_id[$i];
    $item->save();
    $i++;
    };

    
    
    // return redirect()->route('posts.index')->with('flash_message', '申請されました。');
    // $user = new User();
    Mail::to($request->destination)->send(new ReApplicationMail($post,$item));

    return redirect()->route('posts.index')->with('messages','購入申請しました');
   }

// ここが分からない
   // 削除機能（取下機能）
    public function destroy_reapplication($post_id)
    {
      $post = Post::find($post_id);
    //   dd($post );
      $post -> delete();
      $items = Item::where('post_id',$post_id)->get();
      foreach($items as $item)
      {
      $item -> delete();
      };
    // 注文者にもメールを送りたい  
      Mail::to($post->destination)->send(new WithdrawAnApplicationMail($post,$item[0]));
      Mail::to('order@test.test')->send(new WithdrawAnApplicationMail($post,$item[0]));
      return to_route('posts.index');
    }




// 上長承認画面（CRUDでいうcreate)をViewに渡しユーザーに表示させる
public function create_authorizer(Request $request,$id)
{
    $consumables = Consumable::all();
    $accounts = Account::all();
    $posts = Post::find($id);
    $items = $posts->items;
    return view('posts.create_authorizer',compact('posts','items','consumables','accounts'));
}


// 上長の承認
public function update_authorizer(Request $request, $id){
    
    $post =Post::find($id);
    // dd($post);
    
    // 確認者or注文担当者が記載していく項目
    $post->application_status = $request->application_status;
    $post->remarks = $request->remarks;
    $post->save();
    
    // 注文者
    Mail::to('order@test.test')->send(new AuthorizerApprovalMail($post,$id));
    // 別に送りたい人
    if($request->email){
    Mail::to($request->email)->send(new AuthorizerApprovalMail($post,$id));
    }
    return redirect()->route('posts.index')->with('messages','購入申請しました');
   }


// 上長の差戻
public function remand_authorizer(Request $request, $id){
    
    $post =Post::find($id);
    $post->application_status = 4;//申請ステータスは常に4とし、意味は差戻という意味。
    $post->reason_for_remand = $request->reason_for_remand;
    // dd($post);
    $post->save();
    

    Mail::to($post->user->email)->send(new AuthorizerDisapprovalMail($post,$id));
    

    return redirect()->route('posts.index')->with('messages','申請を差戻しました');
   }




// 確認者承認画面の表示
public function create_order(Request $request,$id)
{
    $posts = Post::find($id);
    $items = $posts->items;
    $consumables = Consumable::all();
    $accounts = Account::all();
    return view('posts.create_order',compact('posts','items','consumables','accounts'));
}


// 注文担当者の承認
public function complete_order(Request $request, $id){
    
    $post =Post::find($id);
    // dd($request);
    $post->application_status = $request->application_status;
    $post->remarks = $request->remarks;
    $post->delivery_day = $request->delivery_day;
    $post->save();
    Mail::to($post->user->email)->send(new OrdererCompletedMail($post,$id));
    return redirect()->route('posts.index')->with('messages','注文完了しました');
   }


// 注文者担当者の差戻
public function remand_order(Request $request, $id){
    
    $post =Post::find($id);
    // dd($request);
    $post->application_status = 4;//申請ステータスは常に4とし、意味は差戻という意味。
    $post->remarks = $request->input('remarks');
    $post->delivery_day = $request->input('delivery_day');
    $post->save();
    Mail::to($post->user->email)->send(new OrdererDisapprovedMail($post,$id));

    return redirect()->route('posts.index')->with('messages','申請を差戻しました');
   }


}