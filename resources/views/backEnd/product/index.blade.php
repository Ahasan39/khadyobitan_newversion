@extends('backEnd.layouts.master')
@section('title','Product Manage')
@section('content')
<div class="container-fluid">
    
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{route('products.create')}}" class="btn btn-danger rounded-pill"><i class="fe-shopping-cart"></i> Add Product</a>
                </div>
                <h4 class="page-title">Product Manage</h4>
            </div>
        </div>
    </div>       
    <!-- end page title --> 
   <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-8">
                        <ul class="action2-btn">
                            <li><a href="{{route('products.update_deals',['status'=>1])}}" class="btn rounded-pill btn-success hotdeal_update"><i class="fe-thumbs-up"></i> Deal</a></li>
                            <li><a href="{{route('products.update_deals',['status'=>0])}}" class="btn  rounded-pill btn-danger hotdeal_update"><i class="fe-thumbs-down"></i> Deal</a></li>
                            
                            <li><a href="{{route('products.update_status',['status'=>1])}}" class="btn rounded-pill btn-primary update_status"><i class="fe-thumbs-up"></i> Active</a></li>
                            <li><a href="{{route('products.update_status',['status'=>0])}}" class="btn  rounded-pill btn-warning update_status"><i class="fe-thumbs-down"></i> Inactive</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-4">
                        <form class="custom_form">
                            <div class="form-group">
                                <input type="text" name="keyword" placeholder="Search">
                                <button class="btn  rounded-pill btn-info">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table nowrap w-100">
                    <thead>
                        <tr>
                            <th style="width:2%"><div class="form-check"><label class="form-check-label"><input type="checkbox" class="form-check-input checkall" value=""></label>
                            <th style="width:2%">SL</th>
                                    </div></th>
                            <th style="width:10%">Action</th>
                            <th style="width:20%">Name</th>
                            <th style="width:10%">Category</th>
                            <th style="width:10%">Image</th>
                            <th style="width:10%">Price</th>
                            <th style="width:8%">Stock</th>
                            <th style="width:14%">Deal & Feature</th>
                            <th style="width:8%">Status</th>
                        </tr>
                    </thead>               
                
                    <tbody>
                        @foreach($data as $key=>$value)
                        <tr>
                            <td><input type="checkbox" class="checkbox" value="{{$value->id}}"></td>
                            <td>{{$loop->iteration}}</td>
                            <td>
                                <div class="button-list custom-btn-list">
                                    @if($value->status == 1)
                                    <form method="post" action="{{route('products.inactive')}}" class="d-inline"> 
                                    @csrf
                                    <input type="hidden" value="{{$value->id}}" name="hidden_id">       
                                    <button type="button" class="change-confirm" title="Active"><i class="fe-thumbs-down"></i></button></form>
                                    @else
                                    <form method="post" action="{{route('products.active')}}" class="d-inline">
                                        @csrf
                                    <input type="hidden" value="{{$value->id}}" name="hidden_id">        
                                    <button type="button" class="change-confirm" title="Inactive"><i class="fe-thumbs-up"></i></button></form>
                                    @endif

                                    <a href="{{route('products.edit',$value->id)}}" title="Edit"><i class="fe-edit"></i></a>

                                    <form method="post" action="{{route('products.destroy')}}" class="d-inline">        
                                        @csrf
                                    <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                    <button type="submit" class="delete-confirm" title="Delete"><i class="fe-trash-2"></i></button></form>
                                     <a href="{{route('products.purchase_history',$value->id)}}" title="Purchase"><i class="fe-shopping-bag"></i></a>
                                     <a href="{{route('products.duplicate',$value->id)}}" title="Duplicate" onclick="return confirm('Are you sure you want to duplicate this product?')">
                                      <svg width='30' height='20' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path fill="#63E6BE" d="M288 64C252.7 64 224 92.7 224 128L224 384C224 419.3 252.7 448 288 448L480 448C515.3 448 544 419.3 544 384L544 183.4C544 166 536.9 149.3 524.3 137.2L466.6 81.8C454.7 70.4 438.8 64 422.3 64L288 64zM160 192C124.7 192 96 220.7 96 256L96 512C96 547.3 124.7 576 160 576L352 576C387.3 576 416 547.3 416 512L416 496L352 496L352 512L160 512L160 256L176 256L176 192L160 192z"/></svg>
                                     </a>
                                </div>
                            </td>
                            <td>{{$value->name}}</td>
                            <td>{{$value->category?$value->category->name:''}}</td>
                            <td><img src="{{asset($value->image?$value->image->image:'')}}" class="backend-image" alt=""></td>
                            <td>{{$value->variable?$value->variable->new_price: $value->new_price}}</td>
                             <td>{{$value->type == 0 ? $value->variables_sum_stock : $value->stock}}</td>
                            <td><p class="m-0">Hot Deals : {{$value->topsale==1?'Yes':'No'}}</p>
                                <p class="m-0">Top Feature : {{$value->feature_product==1?'Yes':'No'}}</p></td>
                            <td>@if($value->status==1)<span class="badge bg-soft-success text-success">Active</span> @else <span class="badge bg-soft-danger text-danger">Inactive</span> @endif</td>
                        </tr>
                        @endforeach
                     </tbody>
                    </table>
                </div>
                <div class="custom-paginate">
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
   </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $(".checkall").on('change',function(){
      $(".checkbox").prop('checked',$(this).is(":checked"));
    });
    
    $(document).on('click', '.hotdeal_update', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('url',url);
        var product = $('input.checkbox:checked').map(function(){
          return $(this).val();
        });
        var product_ids=product.get();
        if(product_ids.length ==0){
            toastr.error('Please Select A Product First !');
            return ;
        }
        $.ajax({
           type:'GET',
           url:url,
           data:{product_ids},
           success:function(res){
               if(res.status=='success'){
                toastr.success(res.message);
                window.location.reload();
            }else{
                toastr.error('Failed something wrong');
            }
           }
        });
    });
    $(document).on('click', '.update_status', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var product = $('input.checkbox:checked').map(function(){
          return $(this).val();
        });
        var product_ids=product.get();
        if(product_ids.length ==0){
            toastr.error('Please Select A Product First !');
            return ;
        }
        $.ajax({
           type:'GET',
           url:url,
           data:{product_ids},
           success:function(res){
               if(res.status=='success'){
                toastr.success(res.message);
                window.location.reload();
            }else{
                toastr.error('Failed something wrong');
            }
           }
        });
    });
    $(document).on('click', '.update_status', function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var product = $('input.checkbox:checked').map(function(){
          return $(this).val();
        });
        var product_ids=product.get();
        if(product_ids.length ==0){
            toastr.error('Please Select A Product First !');
            return ;
        }
        $.ajax({
           type:'GET',
           url:url,
           data:{product_ids},
           success:function(res){
               if(res.status=='success'){
                toastr.success(res.message);
                window.location.reload();
            }else{
                toastr.error('Failed something wrong');
            }
           }
        });
    });
    
    
})
</script>

<script>
    $(document).on('click', '.duplicate-product', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    
    if(confirm('Are you sure you want to duplicate this product?')) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                Toastr.success('Product duplicated successfully');
                window.location.reload();
            },
            error: function(xhr) {
                Toastr.error('Error duplicating product');
            }
        });
    }
});
</script>
@endsection