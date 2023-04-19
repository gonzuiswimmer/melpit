<?php

namespace App\Http\Controllers\MyPage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SoldItemsController extends Controller
{
    public function showSoldItems(){
        $user = Auth::user();

        $items = $user->soldItems()->orderBy('id','DESC')->get();

        return view('mypage.sold_items')->with('items',$items);
    }
}
