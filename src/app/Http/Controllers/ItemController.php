<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        if ($request->tab === 'mylist') {
            if (!auth()->check()) {
                $items = collect();
            } else {
                $items = Item::with('purchase')
                    ->whereHas('likes', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->when($request->keyword, function ($query, $keyword) {
                        $query->where('item_name', 'like', '%' . $keyword . '%');
                    })
                    ->get();
            }
        } else {
            $query = Item::with('purchase');

            if (auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }

            if ($request->keyword) {
                $query->where('item_name', 'like', '%' . $request->keyword . '%');
            }
            
            $items = $query->get();
        }

        return view('items.index', compact('items'));
    }
}
