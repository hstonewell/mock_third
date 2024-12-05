@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/purchase.css')}}">
@endsection

@section('main')
<div class="main__inner">
    <div class="main__inner--left">
        <div class="item-summary">
            <div class="item-summary--img">
                <img src="{{ asset($item->image) }}" alt="{{ $item->item_name }}">
            </div>
            <div class="item-summary--caption">
                <h2>{{ $item->item_name }}</h2>
                <h5>¥{{ number_format($item->price) }}</h5>
            </div>
        </div>
        <div class="item-summary--option">
            <div class="item-summary--option--header">
                <h3>支払い方法</h3>
            </div>
            <div class="item-summary--option--detail">
                <form id="payment-form" data-amount="{{ $item->price }}">
                    @csrf
                    <div id="payment-element">
                        <!--Stripe.js injects the Payment Element-->
                    </div>
                    <div id="error-message">
                        <!-- Display error message to your customers here -->
                    </div>
                </form>
            </div>
        </div>
        <div class="item-summary--option">
            <div class="item-summary--option--header">
                <h3>配送先</h3>
                <a href="{{ route('address.show', ['item_id' => $item->id]) }}">変更する</a>
            </div>
            <div class="item-summary--option--detail">
                @if($userProfile)
                <p>〒{{ $userProfile->postcode }}</p>
                <p>{{ $userProfile->address }} {{ $userProfile->building }}</p>
                @else
                <p>住所を設定してください。</p>
                @endif
            </div>
        </div>
    </div>
    <div class="main__inner-right">
        <div class="item-purchase__box">
            <div class="item-purchase__payment-unit">
                <p>商品代金</p>
                <p>¥{{ number_format($item->price) }}</p>
            </div>
            <div class="item-purchase__payment-unit">
                <p>支払い金額</p>
                <p>¥{{ number_format($item->price) }}</p>
            </div>
            <div class="item-purchase__payment-unit">
                <p>支払い方法</p>
                <p>支払い方法</p>
            </div>
            <div class="item-purchase--button">
                @if($item->sold_out == false)
                <button id="submit" class="submit-button" aria-controls="payment-form" {{ !$hasUserAddress ? 'disabled' : '' }}>購入する</button>
                @else
                <button id="submit" class="submit-button" disabled>売り切れ</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit');
        const errorElement = document.getElementById('error-message');

        submitButton.addEventListener('click', (e) => {
            e.preventDefault(); // デフォルトのクリック動作を無効化
            form.dispatchEvent(new Event('submit')); // フォーム送信イベントを手動でトリガー
        });

        // デバッグ用のコンソールログを追加
        console.log('Stripe Key:', "{{ env('STRIPE_KEY') }}");
        console.log('Form:', form);
        console.log('Amount:', form.dataset.amount);

        try {
            const response = await fetch("{{ route('purchase.secret', ['item_id' => $item->id]) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({
                    amount: parseInt(form.dataset.amount),
                }),
            });

            // レスポンスの詳細をログ出力
            console.log('Response status:', response.status);
            const responseText = await response.text();
            console.log('Response body:', responseText);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}, body: ${responseText}`);
            }

            const {
                clientSecret
            } = JSON.parse(responseText);
            console.log('Client Secret:', clientSecret);

            // Stripe Elementsの初期化
            const appearance = {
                theme: 'stripe',
                variables: {
                    colorPrimary: '#0073CC',
                    colorBackground: '#ffffff',
                    colorText: '#000',
                    colorDanger: '#FF5555',
                    fontFamily: 'Ideal Sans, system-ui, sans-serif',
                    spacingUnit: '2px',
                    borderRadius: '4px',
                },
                rules: {
                    '.Input': {
                        borderColor: '#5f5f5f'
                    },
                    '.Input:focus': {
                        borderColor: '#0073CC'
                    }
                }
            };

            const elements = stripe.elements({
                mode: 'payment',
                amount: parseInt(form.dataset.amount),
                currency: 'jpy',
                appearance,
                paymentMethodTypes: ['card', 'customer_balance', 'konbini'],
                customerBalance: {
                    funding_type: 'bank_transfer',
                    bank_transfer: {
                        type: 'jp_bank_transfer'
                    }
                }
            });

            // デバッグ用のログ
            console.log('Elements created:', elements);

            const paymentElement = elements.create('payment', {
                clientSecret: clientSecret,
                fields: {
                    billingDetails: {
                        address: {
                            country: 'never'
                        }
                    }
                }
            });

            // マウント時のエラーをキャッチ
            try {
                paymentElement.mount("#payment-element");
                console.log('Payment element mounted successfully');
            } catch (mountError) {
                console.error('Element mounting error:', mountError);
                errorElement.textContent = `要素のマウントに失敗しました: ${mountError.message}`;
            }

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                submitButton.disabled = true;
                errorElement.textContent = '';

                // 支払い方法の取得
                const paymentMethod = paymentMethodElement.value; // 支払い方法の取得
                console.log(`選択された支払い方法: ${paymentMethod}`);

                try {
                    if (paymentMethod === 'card') {
                        // card支払いの場合、従来の処理
                        const submitResult = await elements.submit();

                        if (submitResult.error) {
                            errorElement.textContent = submitResult.error.message;
                            submitButton.disabled = false;
                            return;
                        }

                        const {
                            error,
                            paymentIntent
                        } = await stripe.confirmPayment({
                            elements,
                            clientSecret: clientSecret,
                            redirect: 'if_required',
                            confirmParams: {
                                return_url: "{{ route('index') }}",
                                payment_method_data: {
                                    billing_details: {
                                        address: {
                                            country: "JP"
                                        }
                                    }
                                }
                            }
                        });

                        if (error) {
                            // 支払い方法ごとの分岐処理
                            if (error.type === 'card_error' || error.type === 'validation_error') {
                                errorElement.textContent = error.message;
                            } else if (error.payment_intent?.next_action?.type === 'display_bank_transfer_instructions') {
                                window.location.href = "{{ route('bank.show', ['item_id' => $item->id]) }}";
                            } else if (error.payment_intent?.next_action?.type === 'display_konbini_instructions') {
                                window.location.href = "{{ route('konbini.show', ['item_id' => $item->id]) }}";
                            } else {
                                errorElement.textContent = '予期しないエラーが発生しました';
                            }
                            submitButton.disabled = false;
                            return;
                        }

                        const completeResponse = await fetch("{{ route('purchase.complete', ['item_id' => $item->id]) }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            },
                            body: JSON.stringify({
                                payment_intent_id: paymentIntent.id,
                            }),
                        });

                        if (!completeResponse.ok) {
                            const errorData = await completeResponse.json();
                            throw new Error(errorData.error || '購入処理に失敗しました');
                        }

                        alert("購入が完了しました！");
                        window.location.href = "{{ route('index') }}";
                    } else if (paymentMethod === 'bank_transfer') {
                        window.location.href = "{{ route('bank.show', ['item_id' => $item->id]) }}";
                    } else if (paymentMethod === 'konbini') {
                        window.location.href = "{{ route('konbini.show', ['item_id' => $item->id]) }}";
                    } else {
                        throw new Error('無効な支払い方法が選択されました');
                    }
                } catch (error) {
                    console.error("購入処理エラー:", error);
                    errorElement.textContent = error.message;
                    submitButton.disabled = false;
                }
            });



        } catch (error) {
            console.error("完全なエラー詳細:", error);
            errorElement.textContent = `システムエラーが発生しました: ${error.message}`;
        }
    });
</script>
@endsection