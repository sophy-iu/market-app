<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index($item_id)
    {
        $item = Item::findOrFail($item_id);
        $profile = Profile::where('user_id', auth()->id())->first();
        return view('purchases.purchase', compact('item'));
    }
    public function purchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $request->validate([
            'payment_method' => ['required'],
            'postal_code' => ['required'],
            'address' => ['required'],
            'building' => ['nullable'],
        ]);
        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);
        session()->forget('purchase_address');
        return redirect()->away('https://buy.stripe.com/test_aFa6oGa6jgVL7lu4r53sI00');
    }
}
