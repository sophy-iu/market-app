<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        // ログイン中なら、自分が出品した商品を表示しない
        if(Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        $items = $query->get();
        Item::with('purchase')->get();

        return view('items.index', compact('items'));
    }
}
