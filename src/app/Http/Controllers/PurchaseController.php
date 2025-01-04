<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Item;
use App\Models\PurchasedItem;

use Stripe\PaymentIntent;
use Stripe\Customer;

class PurchaseController extends Controller
{
    public function show($id)
    {
        $item = Item::with('user', 'category', 'condition')->find($id);
        $userProfile = Auth::user()->userProfile;

        $hasUserAddress = $userProfile && $userProfile->address && $userProfile->postcode;

        return view('purchase', compact('item', 'userProfile', 'hasUserAddress'));
    }

    public function payment(Request $request, $item_id)
    {

        $selectedPayment = $request->input('payment_method');
        $name = $request->input('name');
        $price = $request->input('price');

        $user = User::where('id', Auth::id())->first();
        \Stripe\Stripe::setApiKey(config('stripe.secret_key'));

        $customer = Customer::create([
            'email' => $user->email,
        ]);

        $paymentMethod = match ($selectedPayment) {
            'クレジットカード' => 'card',
            '銀行振込' => 'customer_balance',
            'コンビニ支払' => 'konbini',
            default => 'card',
        };

        //カード決済
        if ($paymentMethod == 'card') {
            $checkout_session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'unit_amount' => $price,
                        'product_data' => [
                            'name' => $name,
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'customer' => $customer->id,
                'payment_method_types' => [$paymentMethod],
                'payment_method_options' => [
                    'customer_balance' => [
                        'funding_type' => 'bank_transfer',
                        'bank_transfer' => [
                            'type' => 'jp_bank_transfer',
                        ],
                    ],
                ],
                'metadata' => [
                    'item_id' => $item_id,
                    'buyer_id' => Auth::id(),
                ],
                'success_url' => url('/thanks?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url('/item/' . $item_id),
            ]);

            return redirect($checkout_session->url);

            //銀行振込
        } else if ($paymentMethod == 'customer_balance') {
            $paymentIntent = PaymentIntent::create([
                'amount' => $price,
                'currency' => 'jpy',
                'customer' => $customer->id,
                'payment_method_types' => ['customer_balance'],
                'payment_method_data' => [
                    'type' => 'customer_balance'
                ],
                'payment_method_options' => [
                    'customer_balance' => [
                        'funding_type' => 'bank_transfer',
                        'bank_transfer' => [
                            'type' => 'jp_bank_transfer'
                        ],
                    ],
                ],
                'metadata' => [
                    'item_id' => $item_id,
                    'buyer_id' => Auth::id(),
                ],
                'confirm' => true,
            ]);

            $bankTransferDetails = $paymentIntent->next_action->display_bank_transfer_instructions->financial_addresses;
            $zenginDetails = null;
            foreach ($bankTransferDetails as $detail) {
                if ($detail['type'] === 'zengin') {
                    $zenginDetails = $detail['zengin'];
                    break;
                }
            }

            PurchasedItem::purchaseProcessing($item_id, $selectedPayment);

            session([
                'payment_intent' => $paymentIntent->id,
                'item_id' => $item_id,
                'zengin_details' => $zenginDetails,
            ]);
            return redirect()->route('bank.show', ['item_id' => $item_id]);

            //コンビニ支払
        } else if ($paymentMethod == 'konbini') {
            $paymentIntent = PaymentIntent::create([
                'amount' => $price,
                'currency' => 'jpy',
                'confirm' => true,
                'customer' => $customer->id,
                'payment_method_types' => ['konbini'],
                'payment_method_data' => [
                    'type' => 'konbini',
                    'billing_details' => [
                        'name' => $name,
                        'email' => $user->email,
                    ],
                ],
                'metadata' => [
                    'item_id' => $item_id,
                    'buyer_id' => Auth::id(),
                ],
            ]);

            if (isset($paymentIntent->next_action) && $paymentIntent->next_action->type == 'konbini_display_details') {

                PurchasedItem::purchaseProcessing($item_id, $selectedPayment);

                session([
                    'payment_intent' => $paymentIntent->id,
                    'item_id' => $item_id,
                ]);

                return redirect()->route('konbini.show', compact('item_id'));
            } else {
                return redirect()->route('index')->withErrors('コンビニ支払いに失敗しました。');
            }
        }
    }

    public function showThanks(Request $request)
    {
        // リクエストから session_id を取得
        $sessionId = $request->query('session_id');

        try {
            // Stripe セッション情報を取得
            \Stripe\Stripe::setApiKey(config('stripe.secret_key'));
            $checkoutSession = \Stripe\Checkout\Session::retrieve($sessionId);
            $itemId = $checkoutSession->metadata->item_id;

            if (!$itemId) {
                return redirect('/')->with('error', '購入情報が見つかりません。');
            }

            // Item 情報を取得
            $item = Item::find($itemId);

            if (!$item) {
                return redirect('/')->with('error', 'アイテムが見つかりません。');
            }

            PurchasedItem::purchase($itemId, 'クレジットカード');
            return view('thanks', compact('item'));
        } catch (\Exception $e) {
            // エラー処理
            return redirect('/')->with('error', '購入情報の取得中にエラーが発生しました。');
        }
    }
}
