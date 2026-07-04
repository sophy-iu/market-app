<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        return view('purchases.purchase');
    }
    public function purchase($item_id)
    {
        Purchase::create([
        'user_id' => auth()->id(),
        'item_id' => $item_id,
        ]);
        return redirect()->away('https://buy.stripe.com/test_aFa6oGa6jgVL7lu4r53sI00');
    }
}
