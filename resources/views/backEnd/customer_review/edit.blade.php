@extends('backEnd.layouts.master')
@section('title', 'Customer Review Edit')
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
                        <a href="{{ route('customer_reviews.index') }}" class="btn btn-primary rounded-pill">Manage</a>
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
                        <form action="{{ route('customer_reviews.update', $customerReviw->id) }}" method="POST"
                            enctype="multipart/form-data" class="row" name="editForm">
                            @csrf
                            {{-- @method('PUT') --}}

                            <input type="hidden" value="{{ $customerReviw->id }}" name="id" />

                            <div class="col-sm-8">
                                <div class="form-group mb-3">
                                    <label for="images">Product Images (Ctrl to select multiple)</label>
                                    <input type="file" id="images" name="images[]" multiple
                                        class="form-control @error('images') is-invalid @enderror"
                                        onchange="previewImages(event)">
                                    @error('images')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- OLD IMAGES -->
                            <div class="col-12 mt-3">
                                <h6>Existing Images</h6>
                                <div class="d-flex flex-wrap gap-2 mb-3" id="old-images">
                                    @php
                                        $images = is_string($customerReviw->images)
                                            ? json_decode($customerReviw->images, true)
                                            : $customerReviw->images;
                                    @endphp

                                    @foreach ($images ?? [] as $key => $img)
                                        <div class="image-wrapper position-relative">
                                            <img src="{{ asset( $img) }}" class="preview-img" alt="Old Image">
                                            <button type="button" class="btn btn-sm btn-danger remove-btn"
                                                onclick="removeOldImage(this, '{{ $img }}')">×</button>
                                            <input type="hidden" name="existing_images[]" value="{{ $img }}">
                                        </div>
                                    @endforeach


                                </div>
                            </div>

                            <!-- NEW IMAGES PREVIEW -->
                            <div class="col-12 mt-3">
                                <h6>New Images Preview</h6>
                                <div class="d-flex flex-wrap gap-2" id="preview-container"></div>
                            </div>

                            <div class="col-12 mt-3">
                                <input type="submit" class="btn btn-success" value="Update">
                            </div>
                        </form>

                        <style>
                            .image-wrapper {
                                width: 120px;
                                height: 120px;
                                border-radius: 10px;
                                overflow: hidden;
                                border: 1px solid #ddd;
                                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                                position: relative;
                            }

                            .preview-img {
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                            }

                            .remove-btn {
                                position: absolute;
                                top: 5px;
                                right: 5px;
                                padding: 0 8px;
                                border-radius: 50%;
                                line-height: 1;
                                background-color: rgba(220, 53, 69, 0.9);
                                color: #fff;
                                border: none;
                                cursor: pointer;
                            }
                        </style>

                        <script>
                            /* ✅ PREVIEW NEW IMAGES */
                            function previewImages(event) {
                                const container = document.getElementById('preview-container');
                                container.innerHTML = '';

                                const files = Array.from(event.target.files);
                                files.forEach((file, index) => {
                                    const reader = new FileReader();
                                    reader.onload = e => {
                                        const wrapper = document.createElement('div');
                                        wrapper.classList.add('image-wrapper');

                                        const img = document.createElement('img');
                                        img.src = e.target.result;
                                        img.classList.add('preview-img');

                                        const btn = document.createElement('button');
                                        btn.type = 'button';
                                        btn.classList.add('remove-btn', 'btn', 'btn-sm', 'btn-danger');
                                        btn.textContent = '×';
                                        btn.onclick = () => {
                                            wrapper.remove();
                                            removeFileFromInput(index);
                                        };

                                        wrapper.appendChild(img);
                                        wrapper.appendChild(btn);
                                        container.appendChild(wrapper);
                                    };
                                    reader.readAsDataURL(file);
                                });
                            }

                            /* ✅ REMOVE A FILE FROM FILE INPUT */
                            function removeFileFromInput(removeIndex) {
                                const input = document.getElementById('images');
                                const dt = new DataTransfer();
                                Array.from(input.files)
                                    .forEach((file, index) => {
                                        if (index !== removeIndex) dt.items.add(file);
                                    });
                                input.files = dt.files;
                            }

                            /* ✅ REMOVE OLD IMAGE */
                            function removeOldImage(button, imgPath) {
                                // remove visually
                                button.parentElement.remove();

                                // add hidden input to mark for deletion
                                const form = document.forms['editForm'];
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'deleted_images[]';
                                input.value = imgPath;
                                form.appendChild(input);
                            }
                        </script>


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

@endsection
