<div class="product_item_inner">
    @if ($value->old_price)
        <div class="discount">
            <p>@php $discount=(((($value->old_price)-($value->new_price))*100) / ($value->old_price)) @endphp {{ number_format($discount, 0) }}% Discount</p>
        </div>
    @endif
    <div class="pro_img">
        <a href="{{ route('product', $value->slug) }}">
            <img src="{{ asset($value->image ? $value->image->image : '') }}"
                alt="{{ $value->name }}" loading="lazy" />
        </a>
    </div>
    <div class="pro_des">
        <div class="pro_name">
            <a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 80) }}</a>
        </div>
        <div class="pro_price">
            @if ($value->variable_count > 0 && $value->type == 0)
                <p>
                    @if ($value->variable->old_price)
                        <del>৳ {{ $value->variable->old_price }}</del>
                    @endif
                    ৳ {{ $value->variable->new_price }}
                </p>
            @else
                <p>
                    @if ($value->old_price)
                        <del>৳ {{ $value->old_price }}</del>
                    @endif
                    ৳ {{ $value->new_price }}
                </p>
            @endif
        </div>
        <div class="pro_btn">
            @if ($value->variable_count > 0 && $value->type == 0)
            <button class="variable-modal" data-id="{{ $value->id }}">
                <i class="fa-solid fa-shopping-cart"></i> Order Now
            </button>
            @else
            <div class="cart_btn">
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $value->id }}" />
                    <button type="submit"><i class="fa-solid fa-shopping-cart"></i> Order Now</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
        $(document).on('click', '.ajax-stock-check', function(e) {
            var color = $(".variable_color:checked").data('color');
            var size = $(".variable_size:checked").data('size');
            var id = $('#variableSubmit').data('id');
            console.log(id);
            console.log(size);
            console.log(color);
            if (id) {
                $.ajax({
                    type: "GET",
                    data: {
                        id: id,
                        color: color,
                        size: size
                    },
                    url: "{{ route('stock_check') }}",
                    dataType: "json",
                    success: function(response) {
                        if (response.status && response.product.stock > 0) {
                            $('.variable-submit').prop('disabled', false);
                        } else {
                            toastr.warning("Please select another color or size");
                            $('.variable-submit').prop('disabled', true);
                        }
                    }
                });
            }
        });
    </script>
