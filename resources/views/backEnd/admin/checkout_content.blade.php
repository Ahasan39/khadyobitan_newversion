@extends('backEnd.layouts.master')
@section('title', 'Checkout Content Settings')

@section('css')
    <style>
        .form-section {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.05);
        }

        .form-section h4 {
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .btn-primary {
            padding: 10px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-section">
                <h4>🛒 Checkout Content Settings</h4>

                <form method="POST" action="{{ route('checkout.content.update') }}">
                    @csrf
                    @foreach([
                        'checkout_title' => 'Checkout Title',
                        'name_label' => 'Name Label',
                        'phone_label' => 'Phone Label',
                        'address_label' => 'Address Label',
                        'shipping_method_label' => 'Shipping Method Label',
                        'shipping_inside_dhaka' => 'Shipping Inside Dhaka',
                        'shipping_outside_dhaka' => 'Shipping Outside Dhaka',
                        'payment_method_label' => 'Payment Method Label',
                        'payment_cash_on_delivery' => 'Payment Cash on Delivery',
                        'order_button' => 'Order Button'
                    ] as $field => $label)
                        <div class="form-group mb-3">
                            <label for="{{ $field }}">{{ $label }}</label>
                            <input type="text" name="{{ $field }}" id="{{ $field }}" class="form-control"
                                placeholder="Enter {{ strtolower($label) }}"
                                value="{{ $contents[$field]->value ?? '' }}">
                        </div>
                    @endforeach

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            💾 Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
