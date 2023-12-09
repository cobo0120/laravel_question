<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class AjaxTestController extends Controller
{
    public function getIndex()
{
    return view('ajax_test');
}

public function showAll()
{
    $products = DB::table('products')->get();

    $productList = array();

    foreach($products as $product){
        $productList[] = array(
          'id'    => $product->id,
          'name'  => $product->name,
          'price' => $product->price
        );
    }

    // ヘッダーを指定することによりjsonの動作を安定させる
    header('Content-type: application/json');

    // htmlへ渡す配列$productListをjsonに変換する
    echo json_encode($productList);
}


}
