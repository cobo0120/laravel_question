<?php

namespace App\Http\Livewire;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;

class Search extends Component
{


    public function scopeSearch($query, $search)
   {
       if ($search !== null) {
           $search_split = mb_convert_kana($search, 's'); //全角スペースを半角
           $search_split2 = preg_split('/[\s]+/', $search_split); //空白で区切る
           foreach ($search_split2 as $value) {
               $query->where('name', 'like', '%' . $value . '%');
           }
       }
       return $query;
   }

    

    public $search;

    public function render()
    {
        $results = User::search($this->search)->get();
        return view('livewire.search', ['results' => $results]);
    }
 }

