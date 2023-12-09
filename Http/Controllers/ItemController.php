<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function store(Request $request)
    {
        $item = new Item();
        $item->consumable_equipment_id = $request->input('consumable_equipment_id');
        $item->product_name = $request->input('product_name');
        $item->unit_purchase_price = $request->input('unit_purchase_price');
        $item->purchase_quantity = $request->input('purchase_quantities');
        $item->unit = $request->input('units');
        $item->account = $request->input('account_id');
        $item->save();
    }

}
