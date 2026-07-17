<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Item;

class AddressController extends Controller
{
    public function address($item_id)
    {
        $item = Item::findOrFail($item_id);
        $profile = auth()->user()->profile;
        $postalCode = $purchaseAddress['postal_code'] ?? $profile?->postal_code;
        $address = $purchaseAddress['address'] ?? $profile?->address;
        $building = $purchaseAddress['building'] ?? $profile?->building;
        return view('purchases.address', compact('item','postalCode','address','building'));
    }

    public function update(AddressRequest $request, $item_id)
    {
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
