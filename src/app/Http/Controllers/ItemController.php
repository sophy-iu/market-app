<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Condition;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\SellRequest;
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

    public function detail($item_id)
    {
        $item = Item::with(['likes', 'purchase', 'categories', 'condition', 'comments.user'])
            ->findOrFail($item_id);

        return view('items.detail', compact('item'));
    }

    public function like($item_id)
    {
        $item = Item::findOrFail($item_id);

        $like = Like::where('user_id', auth()->id())
            ->where('item_id', $item->id)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'item_id' => $item->id,
            ]);
        }

        return redirect()->back();
    }

    public function comment(CommentRequest $request, $item_id)
    {

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        return back();
    }

    public function sell()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('items.sell', compact('categories', 'conditions'));
    }

    public function store(SellRequest $request)
    {
        $path = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
        }

        $item = Item::create([
            'user_id' => auth()->id(),
            'image' => $path,
            'item_name' => $request->item_name,
            'brand_name' => $request->brand_name,
            'item_description' => $request->item_description,
            'price' => $request->price,
            'condition_id' => $request->condition_id,
        ]);

        $item->categories()->attach($request->categories);

        return redirect('/');
    }
}
