@extends('backEnd.layouts.master')
@section('title', 'Order Invoice')
@section('content')
    <style>
        .customer-invoice {
            margin: 25px 0;
        }

        .invoice_btn {
            margin-bottom: 15px;
        }

        p {
            margin: 0;
        }

        td {
            font-size: 16px;
        }

        /* Responsive Table Styles */
        .responsive-table {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .responsive-table table {
            min-width: 600px;
        }

        .invoice-summary-table {
            overflow-x: auto;
        }

        .invoice-summary-table table {
            min-width: 300px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .invoice-innter {
                width: 100% !important;
                padding: 15px !important;
            }
            
            .invoice-header-table {
                display: block;
            }
            
            .invoice-header-table tr {
                display: block;
                border: none;
            }
            
            .invoice-header-table td {
                display: block;
                width: 100% !important;
                float: none !important;
                padding: 10px 0;
            }
            
            .invoice-bar {
                margin-left: 0 !important;
                width: 100% !important;
                transform: none !important;
                text-align: center !important;
            }
            
            .invoice-bar p {
                transform: none !important;
                text-align: center !important;
            }
            
            .invoice_to {
                text-align: left !important;
                padding-top: 10px !important;
            }
            
            .invoice_to p {
                text-align: left !important;
            }
            
            .invoice-summary-table {
                float: none !important;
                width: 100% !important;
            }
            
            .responsive-table table {
                min-width: 500px;
            }
            
            td {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .responsive-table table {
                min-width: 400px;
            }
            
            td {
                font-size: 12px;
                padding: 5px !important;
            }
            
            th {
                font-size: 12px;
                padding: 5px !important;
            }
        }

        @page {
            margin: 0px;
        }

        @media print {
            .invoice-innter {
                margin-left: -120px !important;
                width: 760px !important;
                padding: 30px !important;
            }

            .invoice_btn {
                margin-bottom: 0 !important;
            }

            td {
                font-size: 18px;
            }

            p {
                margin: 0;
            }

            header,
            footer,
            .no-print,
            .left-side-menu,
            .navbar-custom {
                display: none !important;
            }
            
            /* Reset mobile styles for print */
            .invoice-header-table {
                display: table;
            }
            
            .invoice-header-table tr {
                display: table-row;
            }
            
            .invoice-header-table td {
                display: table-cell;
                float: left;
            }
            
            .invoice-bar {
                background: #4DBC60 !important;
                transform: skew(38deg) !important;
                width: 100% !important;
                margin-left: 65px !important;
                padding: 20px 60px !important;
            }
            
            .invoice-bar p {
                transform: skew(-38deg) !important;
                text-align: right !important;
            }
            
            .invoice_to {
                text-align: right !important;
            }
            
            .invoice_to p {
                text-align: right !important;
            }
            
            .invoice-summary-table {
                float: right !important;
                width: 300px !important;
            }
            
            .responsive-table {
                overflow: visible;
            }
            
            .responsive-table table {
                min-width: auto;
            }
        }
    </style>
    <section class="customer-invoice ">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <a href="javascript:history.back()" class="no-print"><strong><i class="fe-arrow-left"></i> Back To
                            Order</strong></a>
                </div>
                <div class="col-sm-6">
                    <button onclick="printFunction()"class="no-print btn btn-xs btn-success waves-effect waves-light"><i
                            class="fa fa-print"></i></button>
                </div> 
                <div class="col-sm-12 mt-3">
                    <div class="invoice-innter"
                        style="width:760px;margin: 0 auto;background: #fff;overflow: hidden;padding: 30px;padding-top: 0;">
                        <table class="invoice-header-table" style="width:100%">
                            <tr>
                                <td style="width: 40%; float: left; padding-top: 15px;">
                                    <img src="{{ asset($generalsetting->dark_logo) }}"
                                        style="margin-top:25px !important;width:160px" alt="">
                                    <p style="font-size: 14px; margin-top:15px; color: #222;"><strong>Payment
                                            Method:</strong> <span
                                            style="text-transform: uppercase;">{{ $order->payment ? $order->payment->payment_method : '' }}</span>
                                    </p>
                                    @if ($order->payment->sender_number)
                                        <p> Sender Number : {{ $order->payment->sender_number }}</p>
                                    @endif
                                    @if ($order->payment->trx_id)
                                        <p> Trx ID : {{ $order->payment->trx_id }}</p>
                                    @endif
                                    @if ($order->payment->card_number)
                                        <p> Card Number : {{ $order->payment->card_number }}</p>
                                    @endif
                                    <div class="invoice_form mt-3">
                                        <p style="font-size:16px;line-height:1.8;color:#222"><strong>Invoice From:</strong>
                                        </p>
                                        <p style="font-size:16px;line-height:1.8;color:#222">{{ $generalsetting->name }}</p>
                                        <p style="font-size:16px;line-height:1.8;color:#222">{{ $contact->phone }}</p>
                                        <p style="font-size:16px;line-height:1.8;color:#222">{{ $contact->email }}</p>
                                        <p style="font-size:16px;line-height:1.8;color:#222">{{ $contact->address }}</p>
                                    </div>
                                </td>
                                <td style="width:60%;float: left;">
                                    <div class="invoice-bar"
                                        style=" background: #4DBC60; transform: skew(38deg); width: 100%; margin-left: 65px; padding: 20px 60px; ">
                                        <p
                                            style="font-size: 30px; color: #fff; transform: skew(-38deg); text-transform: uppercase; text-align: right; font-weight: bold;">
                                            Invoice</p>
                                    </div>
                                    <div class="invoice-bar"
                                        style="background: #fff; transform: skew(36deg); width: 72%; margin-left: 182px; padding: 12px 32px; margin-top: 6px;">
                                        <p
                                            style="font-size: 15px; color: #222;font-weight:bold; transform: skew(-36deg); text-align: right; padding-right: 18px">
                                            Invoice ID : <strong>#{{ $order->invoice_id }}</strong></p>
                                        <p
                                            style="font-size: 15px; color: #222;font-weight:bold; transform: skew(-36deg); text-align: right; padding-right: 32px">
                                            Invoice Date: <strong>{{ $order->created_at->format('d-m-y') }}</strong></span>
                                        </p>
                                    </div>
                                    <div class="invoice_to" style="padding-top: 20px;">
                                        <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;">
                                            <strong>Invoice To:</strong></p>
                                        <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;">
                                            {{ $order->shipping ? $order->shipping->name : '' }}</p>
                                        <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;">
                                            {{ $order->shipping ? $order->shipping->phone : '' }}</p>
                                        <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;">
                                            {{ $order->shipping ? $order->shipping->address : '' }}</p>
                                        <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;">
                                            {{ $order->shipping ? $order->shipping->area : '' }}</p>
                                        @if ($order->note)
                                            <p style="font-size:16px;line-height:1.8;color:#222;text-align: right;">Note :
                                                {{ $order->note }}</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                        <div class="responsive-table">
                            <table class="table" style="margin-top: 30px;margin-bottom: 0;">
                                <thead style="background: #4DBC60; color: #fff;">
                                    <tr>
                                        <th>SL</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderdetails as $key => $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->product_name }} <br>
                                                @if ($value->product_size)
                                                    <small>Size: {{ $value->product_size }}</small>
                                                    @endif @if ($value->product_color)
                                                        <small>Color: {{ $value->product_color }}</small>
                                                    @endif
                                            </td>
                                            <td>৳{{ $value->sale_price }}</td>
                                            <td>{{ $value->qty }}</td>
                                            <td>৳{{ $value->sale_price * $value->qty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="invoice-bottom">
                            <div class="invoice-summary-table" style="width: 300px; float: right; margin-bottom: 30px;">
                                <table class="table">
                                    <tbody style="background:#f1f9f8">
                                        <tr>
                                            <td><strong>SubTotal</strong></td>
                                            <td><strong>৳{{ $order->amount + $order->discount - $order->shipping_charge }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Shipping(+)</strong></td>
                                            <td><strong>৳{{ $order->shipping_charge }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Discount(-)</strong></td>
                                            <td><strong>৳{{ $order->discount }}</strong></td>
                                        </tr>
                                        <tr style="background:#4DBC60;color:#fff">
                                            <td><strong>Final Total</strong></td>
                                            <td><strong>৳{{ $order->amount }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="terms-condition"
                                style="overflow: hidden; width: 100%; text-align: center; padding: 20px 0; border-top: 1px solid #ddd; clear: both;">
                                <h5 style="font-style: italic;"><a
                                        href="{{ route('page', ['slug' => 'terms-condition']) }}">Terms & Conditions</a></h5>
                                <p style="text-align: center; font-style: italic; font-size: 15px; margin-top: 10px;">* This
                                    is a computer generated invoice, does not require any signature.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function printFunction() {
            window.print();
        }
    </script>
@endsection