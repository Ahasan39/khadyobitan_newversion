@extends('backEnd.layouts.master')
@section('title', 'Banner Edit')
@section('css')
    <link href="{{ asset('public/backEnd') }}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{ route('banners.index') }}" class="btn btn-primary rounded-pill">Manage</a>
                    </div>
                    <h4 class="page-title">Banner Edit</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('banners.update') }}" method="POST" class="row" data-parsley-validate=""
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{ $edit_data->id }}" name="id">

                            <!-- col-end -->
                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label">Banner Category *</label>
                                    <select class="form-control select2-multiple @error('link') is-invalid @enderror"
                                        name="category_id" data-toggle="select2" data-placeholder="Choose ...">
                                        <optgroup>
                                            <option value="">Select..</option>
                                            @foreach ($bcategories as $value)
                                                <option value="{{ $value->id }}"
                                                    @if ($edit_data->category_id == $value->id) selected @endif>{{ $value->name }}
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

                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title', $edit_data->title) }}" id="title"
                                        required="">
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="Slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('Slug') is-invalid @enderror"
                                        name="Slug" value="{{ old('Slug', $edit_data->slug) }}" id="Slug" readonly>
                                    @error('Slug')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                          <script>
    document.getElementById('title').addEventListener('keyup', function() {
        let title = this.value;

        // Keep Bengali vowel signs 
        let normalized = title.normalize("NFC");

        let slug = normalized
            .toLowerCase()
            .replace(/[^\p{L}\p{M}\p{N}\s-]/gu, '') // allow unicode letters + marks + numbers
            .trim()
            .replace(/\s+/g, '-'); // replace spaces with dashes

        document.getElementById('Slug').value = slug;
    });
</script>


                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="sub_title" class="form-label">Sub Title </label>
                                    <input type="text" class="form-control @error('sub_title') is-invalid @enderror"
                                        name="sub_title" value="{{ old('sub_title', $edit_data->sub_title) }}"
                                        id="sub_title" >
                                    @error('sub_title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="link" class="form-label">link </label>
                                    <input type="text" class="form-control @error('link') is-invalid @enderror"
                                        name="link" value="{{ $edit_data->link }}" id="link" >
                                    @error('link')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group mb-3">
                                    <label for="button_link" class="form-label">Button Link </label>
                                    <input type="text" class="form-control @error('button_link') is-invalid @enderror"
                                        name="button_link" value="{{ old('button_link', $edit_data->button_link) }}"
                                        id="button_link" >
                                    @error('button_link')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 mb-3">
                                <div class="form-group">
                                    <label for="image" class="form-label">Image *</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        name="image" value="{{ $edit_data->image }}" id="image">
                                    <img src="{{ asset($edit_data->image) }}" alt="" class="edit-image">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- col end -->
                            <div class="col-sm-12 mb-3">
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
                            <div>
                                <input type="submit" class="btn btn-success" value="Submit">
                            </div>

                        </form>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('public/backEnd/') }}/assets/libs/parsleyjs/parsley.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/js/pages/form-validation.init.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/libs/select2/js/select2.min.js"></script>
    <script src="{{ asset('public/backEnd/') }}/assets/js/pages/form-advanced.init.js"></script>
@endsection
