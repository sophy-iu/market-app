<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        if ($request->page == 'buy') {
            $items = Item::whereHas('purchase', function ($query) {
                $query->where('user_id', auth()->id());
            })->get();
        } else {
            $items = Item::where('user_id', auth()->id())->get();
        }
        $user = auth()->user();
        $profile = Profile::firstOrNew([
            'user_id' => auth()->id(),
        ]);
        return view('profiles.profile', compact('items','user','profile'));
    }

    public function edit()
    {
        $profile = Profile::firstOrNew([
            'user_id' => auth()->id(),
        ]);
        return view('profiles.edit', compact('profile'));
    }

    public function update(ProfileRequest $request)
    {
        $profile = Profile::firstOrNew([
            'user_id' => auth()->id(),
        ]);

        if ($request->hasFile('image')) {
            $profile->image = $request->file('image')
                ->store('profiles', 'public');
        }

        $profile->user_id = auth()->id();
        $profile->name = $request->name;
        $profile->postal_code = $request->postal_code;
        $profile->address = $request->address;
        $profile->building = $request->building;
        $profile->save();

        return redirect('/mypage');
    }
}
