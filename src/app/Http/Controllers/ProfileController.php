<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        if ($request->page == 'buy') {
            $items = Item::with('purchase', function ($query) {
                $query->where('user_id', auth()->id());
            })->get();
        } else {
            $items = Item::where('user_id', auth()->id())->get();
        }
        $user = auth()->user();
        return view('profile.profile', compact('items','user'));
    }
}
