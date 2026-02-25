@extends('backEnd.layouts.master')
@section('title', 'Category Create')
@section('css')
    <link href="{{ asset('public/backEnd') }}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/backEnd') }}/assets/css/switchery.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/backEnd') }}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet"
        type="text/css" />
    <style>
        /* Tag style */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #0d6efd;
            border: 1px solid #0d6efd;
            color: white;
        }
    </style>
@endsection
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <a href="{{ route('categories.index') }}" class="btn btn-primary rounded-pill">Manage</a>
                </div>
                <h4 class="page-title">Category Create</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST" class="row"
                        data-parsley-validate="" enctype="multipart/form-data">
                        @csrf
                        <!-- Name -->
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" id="name" required="">
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="image" class="form-label">Image *</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror "
                                    name="image" value="{{ old('image') }}" id="image">
                                @error('image')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Banner -->
                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="banner" class="form-label">Category Banner (Optional)</label>
                                <input type="file" class="form-control @error('banner') is-invalid @enderror"
                                    name="banner" value="{{ old('banner') }}" id="banner">
                                @error('banner')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Meta -->
                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="meta_title" class="form-label">Meta Title (Optional)</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                    name="meta_title" value="{{ old('meta_title') }}" id="meta_title">
                                @error('meta_title')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group mb-3">
                                <label for="meta_description" class="form-label">Meta Description (Optional)</label>
                                <textarea class="summernote form-control @error('meta_description') is-invalid @enderror"
                                    name="meta_description" rows="6" id="meta_description">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="col-sm-12 mb-3">
                            <div class="form-group">
                                <label for="tags" class="form-label">Tags</label>
                                <select class="form-control select2-tags" name="tags[]" multiple="multiple">
                                    @foreach($defaultTags ?? [] as $tag)
                                        <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Select or type new tags and press enter.</small>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col mb-3">
                            <div class="form-group">
                                <label for="status" class="d-block">Status</label>
                                <label class="switch">
                                    <input type="checkbox" value="1" name="status" checked>
                                    <span class="slider round"></span>
                                </label>
                                @error('status')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <!-- Front View -->
                        <div class="col mb-3">
                            <div class="form-group">
                                <label for="front_view" class="d-block">Front View</label>
                                <label class="switch">
                                    <input type="checkbox" value="1" name="front_view">
                                    <span class="slider round"></span>
                                </label>
                                @error('front_view')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <input type="submit" class="btn btn-success" value="Submit">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('public/backEnd/') }}/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="{{ asset('public/backEnd/') }}/assets/js/pages/form-validation.init.js"></script>
<script src="{{ asset('public/backEnd/') }}/assets/libs/select2/js/select2.min.js"></script>
<script src="{{ asset('public/backEnd/') }}/assets/js/pages/form-advanced.init.js"></script>
<script src="{{ asset('public/backEnd/') }}/assets/js/switchery.min.js"></script>
<script src="{{ asset('public/backEnd/') }}/assets/libs/summernote/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        // Summernote
        $(".summernote").summernote({
            placeholder: "Enter Your Text Here",
        });

        // Switchery
        var elem = document.querySelector('.js-switch');
        if(elem) { new Switchery(elem); }

        // Select2 Tags
        $('.select2-tags').select2({
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: "Select or type tags",
            width: '100%'
        });

        // Auto replace space with dash when new tag added
        $('.select2-tags').on('select2:select', function (e) {
            var tagVal = e.params.data.id;
            if(tagVal.includes(' ')){
                var newTag = tagVal.replace(/\s+/g, '-');
                var option = $(this).find('option[value="'+tagVal+'"]');
                option.val(newTag).text(newTag).trigger('change.select2');
            }
        });
    });
</script>
@endsection
