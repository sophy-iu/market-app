<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    // 商品一覧仮面の機能
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

    // 商品詳細画面の機能
    public function detail($item_id)
    {
        $item = Item::with(['likes', 'purchase', 'category', 'condition', 'comments.user'])
            ->findOrFail($item_id);

        return view('items.detail', compact('item'));
    }

    public function like($item_id)
    {
        $like = Like::where('user_id', auth()->id())
            ->where('item_id', $item_id)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'item_id' => $item_id,
            ]);
        }

        return back();
    }

    public function comment(Request $request, $item_id)
    {
        $request->validate([
            'comment' => ['required', 'max:255'],
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        return back();
    }
}
