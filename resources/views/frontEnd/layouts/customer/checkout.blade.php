@extends('frontEnd.layouts.master')
@section('title', 'Customer Checkout') 
@push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/select2.min.css') }}" />
<style>
   .shipping-area-box {
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden; /* Ensure border radius works properly */
}

.area-item {
    border-bottom: 1px solid #ddd;
    padding: 0 10px;
    position: relative;
}

/* Remove border from last item */
.area-item:last-child {
    border-bottom: none;
}

.area-item input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    cursor: pointer;
    z-index: 1; /* Make sure radio is clickable */
}

.area-item label {
    cursor: pointer;
    position: relative;
    display: block;
    padding: 13px;
    padding-left: 40px;
    line-height: 1;
    margin: 0;
    min-height: 50px;
    display: flex;
    align-items: center;
}

.area-item label::before {
    content: "";
    position: absolute;
    width: 20px;
    height: 20px;
    background-color: transparent;
    border: 2px solid var(--primary-color, #007bff); /* Fallback color */
    border-radius: 50%;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    transition: all 0.3s ease;
}

.area-item label::after {
    content: "";
    position: absolute;
    width: 5px;
    height: 5px;
    background-color: var(--secondary-color, #28a745); /* Fallback color */
    border-radius: 50%;
    left: 15px;
    top: 50%;
    transform: translateY(-50%) scale(0);
    transition: transform 0.3s ease;
}

/* Selected state */
.area-item input[type="radio"]:checked + label::after {
    transform: translateY(-50%) scale(1);
}

/* Hover effect */
.area-item:hover label::before {
    border-color: var(--secondary-color, #28a745);
}

/* Active/selected item background */
.area-item input[type="radio"]:checked ~ label {
    background-color: rgba(0, 123, 255, 0.05);
}
</style>
@endpush @section('content')
<section class="chheckout-section">
    @php
        $subtotal = Cart::instance('shopping')->subtotal();
        $subtotal = str_replace(',', '', $subtotal);
        $subtotal = str_replace('.00', '', $subtotal);
        $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
        $coupon = Session::get('coupon_amount') ? Session::get('coupon_amount') : 0;
        $discount = Session::get('discount') ? Session::get('discount') : 0;
        $cart = Cart::instance('shopping')->content();

    @endphp
    <div class="container">
        <div class="row">
            <div class="col-sm-6 cus-order-2">
                <div class="checkout-shipping">
                    <form  id="checkout-form" action="{{ route('customer.ordersave') }}" method="POST" data-parsley-validate="">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                             
                                <h6 class="check-position">{{ $checkout_contents['checkout_title'] ?? 'বিস্তারিত তথ্য পূরণ করুন এবং "অর্ডার নিশ্চিত করুন" বাটনে
                                    ক্লিক করুন ।' }} *</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group customized-input-box mb-3">
                                            <label for="name"> {{ $checkout_contents['name_label'] ?? 'আপনার নাম' }} *</label>
                                            <span class="input-icon-label">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                                            <input type="text" id="name"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ old('name') }}" placeholder="" required />
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-12">
                                        <div class="form-group customized-input-box mb-3">
                                           <label for="phone"> {{ $checkout_contents['phone_label'] ?? 'ফোন নাম্বার' }} *</label>
                                            <span class="input-icon-label">
                                                <i class="fa-solid fa-phone"></i>
                                            </span>
                                            <input type="text" minlength="11" maxlength="11"
                                                pattern="0[0-9]+"
                                                title="please enter number only and 0 must first character"
                                                title="Please enter an 11-digit number." id="phone"
                                                class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                value="{{ old('phone') }}" placeholder="" required />
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-12">
                                        <div class="form-group customized-input-box mb-3">
                                            <label for="address"> {{ $checkout_contents['address_label'] ?? 'আপনার এড্রেস' }} *</label>
                                            <span class="input-icon-label">
                                                <i class="fa-solid fa-map"></i>
                                            </span>
                                            <input type="address" id="address"
                                                class="form-control @error('address') is-invalid @enderror"
                                                name="address" placeholder="" value="{{ old('address') }}" required />
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group mb-3">
                                            <label for="area"><i class="fa-solid fa-truck"></i> {{ $checkout_contents['shipping_method_label'] ?? 'শিপিং মেথড' }} *</label>

                                          <div class="shipping-area-box">
    @foreach ($shippingcharge as $key => $value)
        <div class="form-check area-item" data-id="{{ $value->id }}">
            <input class="form-check-input" name="area" id="area-{{ $value->id }}" type="radio" value="{{ $value->id }}">
            <label class="form-check-label" for="area-{{ $value->id }}">{{ $value->name }}</label>
        </div>
    @endforeach
</div>

                                        </div>
                                    </div>
                                    <!-- col-end -->
                                    <div class="col-sm-12">
                                        <div class="radio_payment">
                                            <label id="payment_method">{{ $checkout_contents['payment_method_label'] ?? 'পেমেন্ট মেথড' }}</label>
                                        </div>
                                        <div class="payment-methods">

                                            <div class="form-check p_cash payment_method" data-id="cod">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    id="inlineRadio1" value="Cash On Delivery" checked required />
                                                <label class="form-check-label" for="inlineRadio1">
                                                    {{ $checkout_contents['payment_cash_on_delivery'] ?? 'ক্যাশ অন ডেলিভারি' }}
                                                </label>

                                            </div>
                                            @if ($bkash_gateway)
                                                <div class="form-check p_bkash payment_method" data-id="bkash">
                                                    <input class="form-check-input" type="radio" name="payment_method"
                                                        id="inlineRadio2" value="bkash" required />
                                                    <label class="form-check-label" for="inlineRadio2">
                                                        বিকাশ
                                                    </label>
                                                </div>
                                            @endif
                                            @if ($shurjopay_gateway)
                                                <div class="form-check p_shurjo payment_method" data-id="nagad">
                                                    <input class="form-check-input" type="radio" name="payment_method"
                                                        id="inlineRadio3" value="shurjopay" required />
                                                    <label class="form-check-label" for="inlineRadio3">
                                                        নগদ
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                           <button class="order_place" type="submit">{{ $checkout_contents['order_button'] ?? 'অর্ডার করুন' }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- card end -->

                    </form>
                </div>
            </div>
            <!-- col end -->
            <div class="col-sm-6 cust-order-1">
                <div class="cart_details table-responsive-sm">
                    <div class="card">
                        <div class="card-header">
                            <h5>অর্ডার বিবরণ </h5>
                        </div>
                        <div class="card-body cartlist">
                            <table class="cart_table table table-bordered table-striped text-center mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 20%;">ডিলিট </th>
                                        <th style="width: 40%;">প্রোডাক্ট </th>
                                        <th style="width: 20%;">পরিমাণ</th>
                                        <th style="width: 20%;">মোট </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach (Cart::instance('shopping')->content() as $value)
                                        <tr>
                                            <td>
                                                <a class="cart_remove" data-id="{{ $value->rowId }}"><i
                                                        class="fas fa-trash text-danger"></i></a>
                                            </td>
                                            <td class="text-left">
                                                <a href="{{ route('product', $value->options->slug) }}"> <img
                                                        src="{{ asset($value->options->image) }}" />
                                                    {{ Str::limit($value->name, 20) }}</a>
                                                @if ($value->options->product_size)
                                                    <p>Size: {{ $value->options->product_size }}</p>
                                                @endif
                                                @if ($value->options->product_color)
                                                    <p>Color: {{ $value->options->product_color }}</p>
                                                @endif
                                            </td>
                                            <td class="cart_qty">
                                                <div class="qty-cart vcart-qty">
                                                    <div class="quantity">
                                                        <button class="minus cart_decrement"
                                                            data-id="{{ $value->rowId }}">-</button>
                                                        <input type="text" value="{{ $value->qty }}" readonly />
                                                        <button class="plus cart_increment"
                                                            data-id="{{ $value->rowId }}">+</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span>৳ </span><strong>{{ $value->price }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">মোট </th>
                                        <td class="px-4">
                                            <span id="net_total"><span>৳
                                                </span><strong>{{ $subtotal }}</strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">ডেলিভারি চার্জ </th>
                                        <td class="px-4">
                                            <span id="cart_shipping_cost"><span>৳
                                                </span><strong>{{ $shipping }}</strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">ডিস্কাউন্ট </th>
                                        <td class="px-4">
                                            <span id="cart_shipping_cost"><span>৳
                                                </span><strong>{{ $discount + $coupon }}</strong></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end px-4">সর্বমোট </th>
                                        <td class="px-4">
                                            <span id="grand_total"><span>৳
                                                </span><strong>{{ $subtotal + $shipping - ($discount + $coupon) }}</strong></span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                       
                            <form
                                action="@if (Session::get('coupon_used')) {{ route('customer.coupon_remove') }} @else {{ route('customer.coupon') }} @endif"
                                class="checkout-coupon-form" method="POST">
                                @csrf
                                <div class="coupon">
                                    <input type="text" name="coupon_code"
                                        placeholder=" @if (Session::get('coupon_used')) {{ Session::get('coupon_used') }} @else Apply Coupon @endif"
                                        class="border-0 shadow-none form-control" />
                                    <input type="submit"
                                        value="@if (Session::get('coupon_used')) remove @else apply @endif "
                                        class="border-0 shadow-none btn btn-theme" />
                                </div>
                            </form>
                       
                        </div>
                    </div>
                </div>
            </div>
            <!-- col end -->
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="{{ asset('public/frontEnd/') }}/js/parsley.min.js"></script>
<script src="{{ asset('public/frontEnd/') }}/js/form-validation.init.js"></script>

<script>
setInterval(function() {
    fetch('/keep-alive');
}, 5 * 60 * 3000); 
</script>

<script>
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>


<script>
  
$(document).ready(function() {
    // Load saved form data and draft order ID
    loadFormData();

    // Auto-save on input changes
    $("#name, #phone, #address").on('input', function() {
        debouncedSaveDraft();
    });

    // Save on radio button changes
    $('input[name="area"], input[name="payment_method"]').on('change', function() {
        debouncedSaveDraft();
        
        // Update shipping charge
        if ($(this).attr('name') === 'area') {
            var id = $(this).val();
            $.ajax({
                type: "GET",
                data: { id: id },
                url: "{{ route('shipping.charge') }}",
                dataType: "html",
                success: function(response) {
                    $(".cartlist").html(response);
                },
            });
        }
    });

    // Save before leaving page
    $(window).on('beforeunload', function() {
        saveFormDataToLocal();
    });

    // Periodic save every 5 seconds
    setInterval(debouncedSaveDraft, 5000);
});

// Debounce function
function debounce(func, timeout = 3000) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { 
            func.apply(this, args); 
        }, timeout);
    };
}

// Load form data from localStorage
function loadFormData() {
    const savedData = localStorage.getItem('draftOrderData');
    if (savedData) {
        const data = JSON.parse(savedData);
        $('#name').val(data.name || '');
        $('#phone').val(data.phone || '');
        $('#address').val(data.address || '');
        if (data.area) {
            $(`input[name="area"][value="${data.area}"]`).prop('checked', true).trigger('change');
        }
        if (data.payment_method) {
            $(`input[name="payment_method"][value="${data.payment_method}"]`).prop('checked', true);
        }
    }
    
    // Select first area if none selected
    if (!$('input[name="area"]:checked').val()) {
        $('.area-item').first().find('input[type="radio"]').prop('checked', true).trigger('change');
    }
}

// Save form data to localStorage
function saveFormDataToLocal() {
    const formData = {
        name: $('#name').val(),
        phone: $('#phone').val(),
        address: $('#address').val(),
        area: $('input[name="area"]:checked').val(),
        payment_method: $('input[name="payment_method"]:checked').val()
    };
    localStorage.setItem('draftOrderData', JSON.stringify(formData));
}

// Save or update draft order
function saveDraftOrder() {
    var area = $('input[name="area"]:checked').val();
    var name = $("#name").val();
    var phone = $("#phone").val();
    var address = $("#address").val();
    
    // Only save if required fields are filled
    if (area && name && phone && address) {
        // Get existing draft order ID from localStorage
        var draftOrderId = localStorage.getItem('draftOrderId');
        
        $.ajax({
            type: "GET",
            data: {
                _token: "{{ csrf_token() }}",
                draft_order_id: draftOrderId, // Send existing draft ID if available
                area: area,
                name: name,
                phone: phone,
                address: address,
                payment_method: $('input[name="payment_method"]:checked').val()
            },
            url: "{{ route('order.store.draft') }}",
            success: function(data) {
                if (data.order_id) {
                    // Store the draft order ID in localStorage
                    localStorage.setItem('draftOrderId', data.order_id);
                    console.log('Draft saved/updated successfully. Order ID:', data.order_id);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error saving draft:', error);
            }
        });
    }
}

// Debounced save function
const debouncedSaveDraft = debounce(() => {
    saveFormDataToLocal();
    saveDraftOrder();
}, 3000);

// Clear draft data after successful order
function clearDraftData() {
    localStorage.removeItem('draftOrderData');
    localStorage.removeItem('draftOrderId');
}
</script>

<script type="text/javascript">
    dataLayer.push({
        ecommerce: null
    }); // Clear the previous ecommerce object.
    dataLayer.push({
        event: "view_cart",
        ecommerce: {
            items: [
                @foreach (Cart::instance('shopping')->content() as $cartInfo)
                    {
                        item_name: "{{ $cartInfo->name }}",
                        item_id: "{{ $cartInfo->id }}",
                        price: "{{ $cartInfo->price }}",
                        item_brand: "{{ $cartInfo->options->brand }}",
                        item_category: "{{ $cartInfo->options->category }}",
                        item_size: "{{ $cartInfo->options->size }}",
                        item_color: "{{ $cartInfo->options->color }}",
                        currency: "BDT",
                        quantity: {{ $cartInfo->qty ?? 0 }}
                    },
                @endforeach
            ]
        }
    });
</script>
<script type="text/javascript">
    // Clear the previous ecommerce object.
    dataLayer.push({
        ecommerce: null
    });

    // Push the begin_checkout event to dataLayer.
    dataLayer.push({
        event: "begin_checkout",
        ecommerce: {
            items: [
                @foreach (Cart::instance('shopping')->content() as $cartInfo)
                    {
                        item_name: "{{ $cartInfo->name }}",
                        item_id: "{{ $cartInfo->id }}",
                        price: "{{ $cartInfo->price }}",
                        item_brand: "{{ $cartInfo->options->brands }}",
                        item_category: "{{ $cartInfo->options->category }}",
                        item_size: "{{ $cartInfo->options->size }}",
                        item_color: "{{ $cartInfo->options->color }}",
                        currency: "BDT",
                        quantity: {{ $cartInfo->qty ?? 0 }}
                    },
                @endforeach
            ]
        }
    });
</script>
<script>
  function autoSaveLead() {

    let nameField = document.querySelector('input[name="name"]');
    let phoneField = document.querySelector('input[name="phone"]');
    let addressField = document.querySelector('input[name="address"]'); // FIXED

    if (!nameField || !phoneField || !addressField) {
        console.warn("Checkout lead fields not found.");
        return;
    }

    let data = {
        name: nameField.value,
        phone: phoneField.value,
        address: addressField.value
    };

    fetch("{{ route('checkout.lead.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    });
}

let form = document.querySelector("#checkout-form");

if (form) {
    let timer;
    form.addEventListener("input", function () {
        clearTimeout(timer);
        timer = setTimeout(autoSaveLead, 2000); // 2-second delay
    });
}


</script>

@endpush