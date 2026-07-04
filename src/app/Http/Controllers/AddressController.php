<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function address($item_id)
    {
        return view('purchases.address', compact('item_id'));
    }
    public function update(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => ['required'],
            'address' => ['required'],
            'building' => ['nullable'],
        ]);
        session([
            'purchase_address' => [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        ]);
        return redirect('/purchase/' . $item_id);
    }
}
