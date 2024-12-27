<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PurchasedItem;
use App\Models\Item;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request) {
        \Stripe\Stripe::setApiKey(config('stripe.secret_key'));
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('stripe.webhook_secret');

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentSuccess($paymentIntent);
                    break;
                case 'payment_method.canceled':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentCanceled($paymentIntent);
                    break;
                default:
                    break;
            }

        } catch (\UnexpectedValueException $e) {
            http_response_code(400);
            exit();
        }

        http_response_code(200);
    }

    protected function handlePaymentSuccess($paymentIntent)
    {
        $itemId = $paymentIntent->metadata->item_id;
        $buyerId = $paymentIntent->metadata->buyer_id;

        $purchasedItem = PurchasedItem::where('item_id', $itemId)
        ->where('buyer_id', $buyerId)
        ->where('status', 1)
        ->first();

        if($purchasedItem) {
            $purchasedItem->status = 0;
            $purchasedItem->save();
        }
    }

    protected function handlePaymentCanceled($paymentIntent)
    {
        $itemId = $paymentIntent->metadata->item_id;
        $buyerId = $paymentIntent->metadata->buyer_id;

        $purchasedItem = PurchasedItem::where('item_id', $itemId)
        ->where('buyer_id', $buyerId)
        ->where('status', 1)
        ->first();

        if($purchasedItem) {
            $purchasedItem->status = 2;
            $purchasedItem->save();

            $item = Item::find($itemId);
            if($item) {
                $item->sold_out = false;
                $item->save();
            }
        }
    }
}
