@extends('backEnd.layouts.master')
@section('title', 'Product Edit')
@section('css')
    <style>
        .increment_btn,
        .remove_btn,
        .btn-warning {
            margin-top: -17px;
            margin-bottom: 10px;
        }
    </style>
    <link href="{{ asset('public/backEnd') }}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/backEnd') }}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet"
        type="text/css" />
    @endsection @section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill">Manage</a>
                    </div>
                    <h4 class="page-title">Product Edit</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('products.update') }}" method="POST" class="row" data-parsley-validate=""
                            enctype="multipart/form-data" name="editForm">
                            @csrf
                            <input type="hidden" value="{{ $edit_data->id }}" name="id" />
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Product Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ $edit_data->name }}" id="name" required="" />
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col-end -->
                            <div class="col-sm-6">
                                <div class="form-group mb-3">
                                    <label for="slug" class="form-label">Slug *</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        name="slug" value="{{ $edit_data->slug }}" id="slug" required="" />
                                    @error('slug')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col-end -->
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label">Categories *</label>
                                    <select
                                        class="form-control form-select select2 @error('category_id') is-invalid @enderror"
                                        name="category_id" value="{{ old('category_id') }}" required>
                                        <optgroup>
                                            <option value="">Select..</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    @if ($edit_data->category_id == $category->id) selected @endif>{{ $category->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="subcategory_id" class="form-label">Sub Categories </label>
                                    <select
                                        class="form-control form-select select2-multiple @error('subcategory_id') is-invalid @enderror"
                                        id="subcategory_id" name="subcategory_id" data-placeholder="Choose ...">
                                        <optgroup>
                                            <option value="">Select..</option>
                                            @foreach ($subcategory as $key => $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    @error('subcategory_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="childcategory_id" class="form-label">Child Categories </label>
                                    <select
                                        class="form-control form-select select2-multiple @error('childcategory_id') is-invalid @enderror"
                                        id="childcategory_id" name="childcategory_id" data-placeholder="Choose ...">
                                        <optgroup>
                                            <option value="">Select..</option>
                                            @foreach ($childcategory as $key => $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                    @error('childcategory_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->

                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="brand_id" class="form-label">Brands</label>
                                    <select class="form-control select2 @error('brand_id') is-invalid @enderror"
                                        value="{{ old('brand_id') }}" name="brand_id">
                                        <option value="">Select..</option>
                                        @foreach ($brands as $value)
                                            <option value="{{ $value->id }}"
                                                @if ($edit_data->brand_id == $value->id) selected @endif>{{ $value->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->

                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="pro_video" class="form-label">Product Video </label>
                                    <input type="text" class="form-control @error('pro_video') is-invalid @enderror"
                                        name="pro_video" value="{{ $edit_data->pro_video }}" id="pro_video" />
                                    @error('pro_video')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="pro_barcode" class="form-label">Product Barcode </label>
                                    <input type="text"
                                        class="barcode form-control @error('stock') is-invalid @enderror"
                                        name="pro_barcode" value="{{ old('pro_barcode') }}" id="pro_barcode">
                                    @error('pro_barcode[]')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="image">Product Image (ctrl to multiple) *</label>
                                    <div class="input-group control-group increment">
                                        <input type="file" name="image[]" multiple
                                            class="form-control @error('image') is-invalid @enderror" />
                                        @error('image')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="product_img">
                                    @foreach ($edit_data->images as $image)
                                        <img src="{{ asset($image->image) }}" class="edit-image border"
                                            alt="" />
                                        <a href="{{ route('products.image.destroy', ['id' => $image->id]) }}"
                                            class="btn btn-xs btn-danger waves-effect waves-light"><i
                                                class="mdi mdi-close"></i></a>
                                    @endforeach
                                </div>
                            </div>


                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="type" class="form-label">Product Type</label>
                                    <select class="form-control select2 @error('type') is-invalid @enderror" disabled
                                        value="{{ old('type') }}" id="product_type" name="type">
                                        <option value="1" @if ($edit_data->type == 1) selected @endif>Normal
                                            Product</option>
                                        <option value="0" @if ($edit_data->type == 0) selected @endif>Variable
                                            Product</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- col-end -->

                            @if ($edit_data->type == 0)
                                <div class="variable_product">
                                    <!-- variable edit part -->
                                    @foreach ($variables as $variable)
                                        <input type="hidden" value="{{ $variable->id }}" name="up_id[]">
                                        <div class="row mb-2">
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="up_size" class="form-label">Size/Weight</label>
                                                    <select class="form-control" name="up_sizes[]">
                                                        <option value="">Select</option>
                                                        @foreach ($sizes as $size)
                                                            <option value="{{ $size->name }}"
                                                                @if ($variable->size == $size->name) selected @endif>
                                                                {{ $size->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('up_size')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!--col end -->
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="up_color" class="form-label">Color </label>
                                                    <select class="form-control" name="up_colors[]">
                                                        <option value="">Select</option>
                                                        @foreach ($colors as $color)
                                                            <option value="{{ $color->name }}"
                                                                @if ($variable->color == $color->name) selected @endif>
                                                                {{ $color->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('up_color')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!--col end -->

                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="up_purchase_prices" class="form-label">Purchase Price
                                                        *</label>
                                                    <input type="text"
                                                        class="form-control @error('up_purchase_prices') is-invalid @enderror"
                                                        name="up_purchase_prices[]"
                                                        value="{{ $variable->purchase_price }}"
                                                        id="up_purchase_prices" />
                                                    @error('purchase_price')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- col-end -->
                                            <!-- col-end -->
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="up_old_prices" class="form-label">Old Price</label>
                                                    <input type="text"
                                                        class="form-control @error('up_old_prices') is-invalid @enderror"
                                                        name="up_old_prices[]" value="{{ $variable->old_price }}"
                                                        id="up_old_prices" />
                                                    @error('old_prices')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- col-end -->
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="up_new_prices" class="form-label">New Price *</label>
                                                    <input type="text"
                                                        class="form-control @error('up_new_prices') is-invalid @enderror"
                                                        name="up_new_prices[]" value="{{ $variable->new_price }}"
                                                        id="up_new_prices" />
                                                    @error('up_new_prices')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- col-end -->
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="up_stocks" class="form-label">Stock *</label>
                                                    <input type="text" class="form-control" name="up_stocks[]"
                                                        value="{{ $variable->stock }}">
                                                    @error('up_stocks')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="up_images">Color Image </label>
                                                    <div class="input-group control-group">
                                                        <input type="file" name="up_images[]"
                                                            class="form-control @error('up_images') is-invalid @enderror" />
                                                        <div class="input-group-btn">
                                                        </div>
                                                        @error('images[]')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                @if ($variable->image)
                                                    <img src="{{ asset($variable->image) }}"
                                                        class="edit-image border mt-1">
                                                @endif
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="pro_barcodes" class="form-label">Product Barcode </label>
                                                    <input type="text"
                                                        class="form-control @error('stock') is-invalid @enderror"
                                                        name="pro_barcodes[]" value="{{ $variable->pro_barcode }}"
                                                        id="pro_barcodes">
                                                    @error('pro_barcodes[]')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- col end -->
                                            <div class="input-group-btn">
                                                <a href="{{ route('products.price.destroy', ['id' => $variable->id]) }}"
                                                    class="btn btn-danger btn-xs text-white"
                                                    onclick="return confirm('Are you want delete this?')"
                                                    type="button"><i class="mdi mdi-close"></i></a>
                                            </div>
                                        </div>
                                    @endforeach
                                    <!--edit variable product  end-->

                                    <!-- new variable add-->
                                    <div class="row mt-3">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="roles" class="form-label">Size/Weight *</label>
                                                <select class="form-control" name="sizes[]">
                                                    <option value="">Select</option>
                                                    @foreach ($sizes as $size)
                                                        <option value="{{ $size->name }}">{{ $size->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('sizes')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--col end -->
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="color" class="form-label">Color </label>
                                                <select class="form-control" name="colors[]">
                                                    <option value="">Select</option>
                                                    @foreach ($colors as $color)
                                                        <option value="{{ $color->name }}">{{ $color->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('color')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!--col end -->

                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="purchase_prices" class="form-label">Purchase Price *</label>
                                                <input type="text"
                                                    class="form-control @error('purchase_prices') is-invalid @enderror"
                                                    name="purchase_prices[]" value="{{ old('purchase_prices') }}"
                                                    id="purchase_prices" />
                                                @error('purchase_price')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->
                                        <!-- col-end -->
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="old_prices" class="form-label">Old Price</label>
                                                <input type="text"
                                                    class="form-control @error('old_prices') is-invalid @enderror"
                                                    name="old_prices[]" value="{{ old('old_prices') }}"
                                                    id="old_prices" />
                                                @error('old_prices')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="new_prices" class="form-label">New Price *</label>
                                                <input type="text"
                                                    class="form-control @error('new_prices') is-invalid @enderror"
                                                    name="new_prices[]" value="{{ old('new_prices') }}"
                                                    id="new_prices" />
                                                @error('new_prices')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label for="stocks" class="form-label">Stock *</label>
                                                <input type="text" class="form-control" name="stocks[]">
                                                @error('stocks')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-8 ">
                                            <div class="form-group">
                                                <label for="images">Color Image </label>
                                                <div class="input-group control-group">
                                                    <input type="file" name="images[]"
                                                        class="form-control @error('images') is-invalid @enderror" />
                                                    <div class="input-group-btn">
                                                    </div>
                                                    @error('images[]')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <!-- col end -->

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="pro_barcodes" class="form-label">Product Barcode </label>
                                                <input type="text" class="form-control" name="pro_barcodes[]">
                                                @error('pro_barcodes')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col end -->
                                        <div class="input-group-btn mt-2">
                                            <button class="btn btn-success increment_btn  btn-xs text-white"
                                                type="button"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="clone_variable" style="display:none">
                                        <div class="row increment_control">
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="roles" class="form-label">Size/Weight</label>
                                                    <select class="form-control" name="sizes[]">
                                                        <option value="">Select</option>
                                                        @foreach ($sizes as $size)
                                                            <option value="{{ $size->name }}">{{ $size->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('size')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!--col end -->
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="color" class="form-label">Color </label>
                                                    <select class="form-control " name="colors[]">
                                                        <option value="">Select</option>
                                                        @foreach ($colors as $color)
                                                            <option value="{{ $color->name }}">
                                                                {{ $color->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('size')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!--col end -->
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="purchase_prices" class="form-label">Purchase Price
                                                        *</label>
                                                    <input type="text"
                                                        class="form-control @error('purchase_prices') is-invalid @enderror"
                                                        name="purchase_prices[]" value="{{ old('purchase_prices') }}"
                                                        id="purchase_prices" />
                                                    @error('purchase_prices')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- col-end -->
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="old_prices" class="form-label">Old Price</label>
                                                    <input type="text"
                                                        class="form-control @error('old_prices') is-invalid @enderror"
                                                        name="old_prices[]" value="{{ old('old_prices') }}"
                                                        id="old_prices" />
                                                    @error('old_prices')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- col-end -->
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="new_prices" class="form-label">New Price *</label>
                                                    <input type="text"
                                                        class="form-control @error('new_prices') is-invalid @enderror"
                                                        name="new_prices[]" value="{{ old('new_prices') }}"
                                                        id="new_prices" />
                                                    @error('new_prices')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- col-end -->
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label for="stocks" class="form-label">Stock *</label>
                                                    <input type="text"
                                                        class="form-control @error('stock') is-invalid @enderror"
                                                        name="stocks[]" value="{{ old('stocks') }}" id="stocks">
                                                    @error('stocks[]')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-sm-8 ">
                                                <div class="form-group">
                                                    <label for="images">Color Image </label>
                                                    <div class="input-group control-group">
                                                        <input type="file" name="images[]"
                                                            class="form-control @error('images') is-invalid @enderror" />
                                                        <div class="input-group-btn">
                                                        </div>
                                                        @error('images[]')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- col end -->
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="pro_barcodes" class="form-label">Product Barcode</label>
                                                    <input type="text" class="form-control" name="pro_barcodes[]">
                                                    @error('pro_barcodes')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <!-- col end -->
                                            <div class="input-group-btn mt-2">
                                                <button class="btn btn-danger remove_btn  btn-xs text-white"
                                                    type="button"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="normal_product">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group mb-3">
                                                <label for="purchase_price" class="form-label">Purchase Price *</label>
                                                <input type="text"
                                                    class="form-control @error('purchase_price') is-invalid @enderror"
                                                    name="purchase_price" value="{{ $edit_data->purchase_price }}"
                                                    id="purchase_price" />
                                                @error('purchase_price')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->
                                        <!-- col-end -->
                                        <div class="col-sm-3">
                                            <div class="form-group mb-3">
                                                <label for="old_price" class="form-label">Old Price</label>
                                                <input type="text"
                                                    class="form-control @error('old_price') is-invalid @enderror"
                                                    name="old_price" value="{{ $edit_data->old_price }}"
                                                    id="old_price" />
                                                @error('old_price')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->
                                        <div class="col-sm-3">
                                            <div class="form-group mb-3">
                                                <label for="new_price" class="form-label">New Price *</label>
                                                <input type="text"
                                                    class="form-control @error('new_price') is-invalid @enderror"
                                                    name="new_price" value="{{ $edit_data->new_price }}"
                                                    id="new_price" />
                                                @error('new_price')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->

                                        <div class="col-sm-3">
                                            <div class="form-group mb-3">
                                                <label for="stock" class="form-label">Stock *</label>
                                                <input type="text"
                                                    class="form-control @error('stock') is-invalid @enderror"
                                                    name="stock" value="{{ $edit_data->stock }}" id="stock" />
                                                @error('stock')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- col-end -->
                                    </div>
                                </div>
                                <!-- normal product end -->
                            @endif
                               
 @php
$default_tags = [
    "Top Trending in BD",
    "Flash Sale",
    "Free Delivery",
    "Cashback Offer",
    "Buy 1 Get 1",
    "Eid Sale",
    "Mega Deal",
    "Mystery Box",
    "Clearance Sale",
    "Combo Offer",
    "Hot Deal",

    "Flat 50% Off",
    "Under 999",
    "Tk 99 Store",
    "Best Price",
    "Crazy Sale",
    "Budget Buy",
    "Loot Offer",
    "Stock Out",

    "11.11 Sale",
    "12.12 Sale",
    "Friday Deal",
    "Year End Sale",
    "Winter Sale",
    "Happy Hour",
    "Midnight Deal",

    "New Arrival",
    "App Exclusive",
    "Shopping Fest",
    "Premium Pick",
    "Gift Voucher"
];


$selected_tags = $edit_data->tags->pluck('name')->toArray();
@endphp

<style>
.tag-badge {
    display: inline-block;
    padding: 6px 12px;
    background: #eee;
    border-radius: 20px;
    margin: 4px;
    cursor: pointer;
    font-size: 14px;
    border: 1px solid #ddd;
}
.tag-badge.selected {
    background: #28a745;
    color: white;
}
</style>

<div id="tag-buttons">
    @foreach($default_tags as $tag)
        <span class="tag-badge 
            {{ in_array($tag, $selected_tags) ? 'selected' : '' }}"
            data-tag="{{ $tag }}">
            {{ $tag }}
        </span>
    @endforeach
</div>

                      <div class="col-sm-12">
    <div class="form-group mb-3">
        <label class="form-label">Tags</label>

        <select name="tags[]" id="tags" multiple="multiple" class="form-control">

            @foreach($all_tags as $tag)
                <option value="{{ $tag->name }}"
                    @if(in_array($tag->name, $selected_tags)) selected @endif>
                    {{ $tag->name }}
                </option>
            @endforeach

            {{-- If some selected tags are custom (not in DB), add them --}}
            @foreach($selected_tags as $tag)
                @if(!in_array($tag, $all_tags->pluck('name')->toArray()))
                    <option value="{{ $tag }}" selected>{{ $tag }}</option>
                @endif
            @endforeach

        </select>

        @error('tags')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>

                            <div class="col-sm-12 mb-3">
                                <div class="form-group">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea name="description" rows="6"
                                        class="summernote form-control @error('description') is-invalid @enderror" required>{{ $edit_data->description }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            
                           <div class="col-sm-12 mb-3">
    <hr>
    <h5 class="mt-4">Product Policies</h5>

    <div id="policy-wrapper">
        @if($edit_data->policies && $edit_data->policies->count() > 0)
            @foreach($edit_data->policies as $policy)
                <div class="policy-item border p-3 rounded mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Icon</label>
                                <input type="text" name="policy_icon[]" 
                                       value="{{ $policy->icon }}" 
                                       class="form-control" 
                                       placeholder="fa-truck or fa-rotate-left">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="policy_title[]" 
                                       value="{{ $policy->title }}" 
                                       class="form-control" 
                                       placeholder="Policy Title">
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="policy_description[]" 
                                          rows="4" 
                                          class="summernote form-control"
                                          placeholder="Enter policy details...">{{ $policy->description }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-1 d-flex align-items-end justify-content-end">
                            <button type="button" class="btn btn-danger remove-policy"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            {{-- যদি আগের কোনো policy না থাকে তাহলে একটি খালি ফর্ম দেখাবে --}}
            <div class="policy-item border p-3 rounded mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="text" name="policy_icon[]" class="form-control" placeholder="fa-truck or fa-rotate-left">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="policy_title[]" class="form-control" placeholder="Policy Title">
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="policy_description[]" rows="4" class="summernote form-control" placeholder="Enter policy details..."></textarea>
                        </div>
                    </div>

                    <div class="col-md-1 d-flex align-items-end justify-content-end">
                        <button type="button" class="btn btn-danger remove-policy"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <button type="button" id="add-policy" class="btn btn-sm btn-primary mt-2">
        <i class="fa fa-plus"></i> Add Policy
    </button>
</div>

                            
                              <div class="col-sm-6">
                                  <div class="form-group mb-3">
                                    <label for="shipping_charge_dhaka" class="form-label">Shipping Charge (Inside Dhaka)</label>
                                    <input type="number" step="0.01" class="form-control @error('shipping_charge_dhaka') is-invalid @enderror" 
                                           name="shipping_charge_dhaka" 
                                           value="{{ old('shipping_charge_dhaka', $edit_data->shipping_charge_dhaka ?? '') }}" 
                                           id="shipping_charge_dhaka">
                                    @error('shipping_charge_dhaka')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                  </div>
                                </div>
                                
                                <div class="col-sm-6">
                                  <div class="form-group mb-3">
                                    <label for="shipping_charge_outside_dhaka" class="form-label">Shipping Charge (Outside Dhaka)</label>
                                    <input type="number" step="0.01" class="form-control @error('shipping_charge_outside_dhaka') is-invalid @enderror" 
                                           name="shipping_charge_outside_dhaka" 
                                           value="{{ old('shipping_charge_outside_dhaka', $edit_data->shipping_charge_outside_dhaka ?? '') }}" 
                                           id="shipping_charge_outside_dhaka">
                                    @error('shipping_charge_outside_dhaka')
                                      <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                  </div>
                                </div>

                        <div class="col-sm-6">
                            <div class="form-group mb-3">
                                <label for="warranty" class="form-label">Warranty </label>
                                <input type="text" step="0.01" class="form-control @error('warranty ') is-invalid @enderror"
                                       name="warranty" value="{{$edit_data->warranty }}">
                                @error('warranty ')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                            <div class="col-sm-4">
                                <div class="form-group mb-3">
                                    <label for="stock_alert" class="form-label">Stock Alert </label>
                                    <input type="text" class="form-control @error('stock_alert') is-invalid @enderror"
                                        name="stock_alert" value="{{ $edit_data->stock_alert }}" id="stock_alert" />
                                    @error('stock_alert')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-8">
                                <div class="form-group mb-3">
                                    <label for="meta_title" class="form-label">Meta Title (SEO)</label>
                                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                        name="meta_title" value="{{ $edit_data->meta_title }}" id="meta_title" />
                                    @error('meta_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-12 mb-3">
                                <div class="form-group">
                                    <label for="meta_description" class="form-label">Meta Description (SEO)</label>
                                    <textarea name="meta_description" rows="6"
                                        class="form-control @error('meta_description') is-invalid @enderror">{{ $edit_data->meta_description }}</textarea>
                                    @error('meta_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-12 mb-3">
                                <div class="form-group">
                                    <label for="meta_keyword" class="form-label">Meta Keyword (SEO)</label>
                                    <textarea name="meta_keyword" rows="6"
                                        class="form-control @error('meta_keyword') is-invalid @enderror">{{ $edit_data->meta_keyword }}</textarea>
                                    @error('meta_keyword')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->

                            <!-- col end -->
                            <div class="col-sm-3 mb-3">
                                <div class="form-group">
                                    <label for="status" class="d-block">Status</label>
                                    <label class="switch">
                                        <input type="checkbox" value="1" name="status"
                                            @if ($edit_data->status == 1) checked @endif>
                                        <span class="slider round"></span>
                                    </label>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-3 mb-3">
                                <div class="form-group">
                                    <label for="topsale" class="d-block">Best Deals</label>
                                    <label class="switch">
                                        <input type="checkbox" value="1" name="topsale"
                                            @if ($edit_data->topsale == 1) checked @endif>
                                        <span class="slider round"></span>
                                    </label>
                                    @error('topsale')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-3 mb-3">
                                <div class="form-group">
                                    <label for="topsale" class="d-block">New Arrival</label>
                                    <label class="switch">
                                        <input type="checkbox" value="1" name="new_arrival"  @if ($edit_data->new_arrival == 1) checked @endif />
                                        <span class="slider round"></span>
                                    </label>
                                    @error('topsale')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-3 mb-3">
                                <div class="form-group">
                                    <label for="topsale" class="d-block">Top Rated</label>
                                    <label class="switch">
                                        <input type="checkbox" value="1" name="top_rated"  @if ($edit_data->top_rated == 1) checked @endif  />
                                        <span class="slider round"></span>
                                    </label>
                                    @error('topsale')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-3 mb-3">
                                <div class="form-group">
                                    <label for="topsale" class="d-block">Top Selling</label>
                                    <label class="switch">
                                        <input type="checkbox" value="1" name="top_selling"   @if ($edit_data->top_selling == 1) checked @endif />
                                        <span class="slider round"></span>
                                    </label>
                                    @error('topsale')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->


                            <!-- col end -->

                            <div>
                                <input type="submit" class="btn btn-success" value="Submit" />
                            </div>
                        </form>
                    </div>
                    <!-- end card-body-->
                </div>
                <!-- end card-->
            </div>
            <!-- end col-->
        </div>
    </div>
    @endsection @section('script')
    <script src="{{ asset('public/backEnd/') }}/assets/libs/parsleyjs/parsley.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/js/pages/form-validation.init.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/libs/select2/js/select2.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/js/pages/form-advanced.init.js"></script>
    <!-- Plugins js -->
    <script src="{{ asset('public/backEnd/') }}/assets/libs//summernote/summernote-lite.min.js"></script>
    <script>
        $(".summernote").summernote({
            placeholder: "Enter Your Text Here",
        });
    </script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('.summernote').summernote({
        height: 120,
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });

    let wrapper = document.getElementById('policy-wrapper');
    let addBtn = document.getElementById('add-policy');

    addBtn.addEventListener('click', function() {
        let newField = `
        <div class="policy-item border p-3 rounded mb-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Icon</label>
                        <input type="text" name="policy_icon[]" class="form-control" placeholder="fa-truck or fa-rotate-left">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="policy_title[]" class="form-control" placeholder="Policy Title">
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="policy_description[]" rows="4" class="summernote form-control" placeholder="Enter policy details..."></textarea>
                    </div>
                </div>

                <div class="col-md-1 d-flex align-items-end justify-content-end">
                    <button type="button" class="btn btn-danger remove-policy"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        </div>`;

        wrapper.insertAdjacentHTML('beforeend', newField);

        // ewly added textarea 
        $('.summernote').summernote({
            height: 120,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview']]
            ]
        });
    });

    // ✅ remove button
    wrapper.addEventListener('click', function(e) {
        if (e.target.closest('.remove-policy')) {
            e.target.closest('.policy-item').remove();
        }
    });
});
</script>


    <script>
        $(document).ready(function() {
            $('#product_type').change(function() {
                var id = $(this).val();
                if (id == 1) {
                    $('.normal_product').show();
                    $('.variable_product').hide();
                } else {
                    $('.variable_product').show();
                    $('.normal_product').hide();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            function updateSerialNumbers() {
                $(".increment_control").each(function(index) {
                    $(this).find("input, select").each(function() {
                        var name = $(this).attr("name");
                        if (name) {
                            var updatedName = name.replace(/\[\d+\]/, "[" + index + "]");
                            $(this).attr("name", updatedName);
                        }
                    });
                });
            }

            $(".increment_btn").click(function() {
                var lastIndex = $(".increment_control").length;
                var html = $(".clone_variable").html();
                var newHtml = html.replace(/\[0\]/g, "[" + lastIndex + "]"); // Ensure proper index
                $(".variable_product").append(newHtml); // Append at the end
                updateSerialNumbers(); // Re-index all elements
            });

            $("body").on("click", ".remove_btn", function() {
                $(this).closest(".increment_control").remove();
                updateSerialNumbers(); // Update indexes after removal
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".select2").select2();
        });

        // category to sub
        $("#category_id").on("change", function() {
            var ajaxId = $(this).val();
            if (ajaxId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('ajax-product-subcategory') }}?category_id=" + ajaxId,
                    success: function(res) {
                        if (res) {
                            $("#subcategory_id").empty();
                            $("#subcategory_id").append('<option value="0">Choose...</option>');
                            $.each(res, function(key, value) {
                                $("#subcategory_id").append('<option value="' + key + '">' +
                                    value + "</option>");
                            });
                        } else {
                            $("#subcategory_id").empty();
                        }
                    },
                });
            } else {
                $("#subcategory_id").empty();
            }
        });

        // subcategory to childcategory
        $("#subcategory_id").on("change", function() {
            var ajaxId = $(this).val();
            if (ajaxId) {
                $.ajax({
                    type: "GET",
                    url: "{{ url('ajax-product-childcategory') }}?subcategory_id=" + ajaxId,
                    success: function(res) {
                        if (res) {
                            $("#childcategory_id").empty();
                            $("#childcategory_id").append('<option value="0">Choose...</option>');
                            $.each(res, function(key, value) {
                                $("#childcategory_id").append('<option value="' + key + '">' +
                                    value + "</option>");
                            });
                        } else {
                            $("#childcategory_id").empty();
                        }
                    },
                });
            } else {
                $("#childcategory_id").empty();
            }
        });
    </script>
    <script type="text/javascript">
        document.forms["editForm"].elements["category_id"].value = "{{ $edit_data->category_id }}";
        document.forms["editForm"].elements["subcategory_id"].value = "{{ $edit_data->subcategory_id }}";
        document.forms["editForm"].elements["childcategory_id"].value = "{{ $edit_data->childcategory_id }}";

        $('#name').on('input', function() {
            const name = $(this).val();
            const id = {{ $edit_data->id }};

            $.ajax({
                url: '{{ route('products.generate_slug') }}',
                method: 'POST',
                data: {
                    name: name,
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#slug').val(response.slug);
                }
            });
        });

        $('#slug').on('input', function() {
            let clean = $(this).val().toLowerCase().replace(/[^a-z0-9\-]/g, '-').replace(/-+/g, '-');
            clean = clean.replace(/^-+|-+$/g, ''); // removes leading/trailing hyphens
            $(this).val(clean);
        });
    </script>
   
<script>
$(document).ready(function() {

    $('#tags').select2({
        tags: true,
        tokenSeparators: [','],
        width: "100%"
    });

    $('.tag-badge').click(function () {

        let tag = $(this).data('tag');
        let selected = $('#tags').val() || [];

        // Add only once
        if (!selected.includes(tag)) {
            selected.push(tag);
            $('#tags').val(selected).trigger('change');
        }

        // Highlight UI
        $(this).addClass("selected");
    });

});
</script>

@endsection
