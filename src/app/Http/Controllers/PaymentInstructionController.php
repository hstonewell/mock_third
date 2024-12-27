<?php

namespace App\Http\Controllers;

use App\Models\Item;

use Illuminate\Http\Request;

class PaymentInstructionController extends Controller
{
    public function showBank(Request $request, $itemId = null)
    {
        $paymentIntentId = session('payment_intent');
        $itemId = session('item_id');
        $zenginDetails = session('zengin_details');

        if (!$paymentIntentId || !$itemId) {
            return redirect()->route('index')->withErrors('不正なアクセスです。');
        }

        $item = Item::findOrFail($itemId);

        return view('bank', compact('paymentIntentId', 'item', 'zenginDetails'));
    }

    public function showKonbini(Request $request, $itemId = null)
    {
        $paymentIntentId = session('payment_intent');
        $itemId = session('item_id');
        $item = Item::findOrFail($itemId);

        if (!$paymentIntentId || !$itemId) {
            return redirect()->route('index')->withErrors('不正なアクセスです。');
        }

        \Stripe\Stripe::setApiKey(config('stripe.secret_key'));
        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

        $konbiniDetails = $paymentIntent->next_action->konbini_display_details ?? null;

        if (!$konbiniDetails) {
            return redirect()->route('index')->withErrors('支払い情報が取得できませんでした。');
        }

        return view('konbini', compact('konbiniDetails', 'item'));
    }
}
