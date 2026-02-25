@extends('backEnd.layouts.master')
@section('title', 'Customer Review')
@section('css')
    <style>
        .increment_btn,
        .remove_btn,
        .btn-warning {
            margin-top: -17px;
            margin-bottom: 10px;
        }
    </style>
    <link href="{{ asset('backEnd') }}/assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backEnd') }}/assets/libs/summernote/summernote-lite.min.css" rel="stylesheet" type="text/css" />
    @endsection @section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <a href="{{ route('customer_reviews.index') }}" class="btn btn-primary rounded-pill">Manage</a>
                    </div>
                    <h4 class="page-title">Customer Review</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="col-md-10">
            <form action="{{ route('customer_reviews.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="images" class="form-label">Upload Images</label>
                    <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                    @error('images')
                        <strong class="text-danger">{{$message}} </strong>
                    @enderror
                </div>

                <!-- Preview Container -->
                <div id="imagePreview" class="mt-3 d-flex flex-wrap gap-2"></div>

                <script>
                    const imageInput = document.getElementById('images');
                    const previewContainer = document.getElementById('imagePreview');

                    imageInput.addEventListener('change', function(event) {
                        previewContainer.innerHTML = ''; // clear old previews
                        const files = Array.from(event.target.files);

                        files.forEach((file, index) => {
                            if (!file.type.startsWith('image/')) return;

                            const reader = new FileReader();
                            reader.onload = function(e) {
                                // Create wrapper
                                const wrapper = document.createElement('div');
                                wrapper.className = 'position-relative d-inline-block';

                                // Create image element
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.className = 'img-thumbnail';
                                img.style.width = '120px';
                                img.style.height = '120px';
                                img.style.objectFit = 'cover';
                                img.style.margin = '5px';

                                // Create remove button
                                const removeBtn = document.createElement('button');
                                removeBtn.innerHTML = '&times;';
                                removeBtn.type = 'button';
                                removeBtn.className =
                                    'btn btn-danger btn-sm position-absolute top-0 start-100 translate-middle rounded-circle';
                                removeBtn.style.padding = '0px 6px';
                                removeBtn.style.fontSize = '16px';
                                removeBtn.style.lineHeight = '1';

                                // Remove preview on click
                                removeBtn.addEventListener('click', () => {
                                    wrapper.remove();

                                    // Remove file from input
                                    const dataTransfer = new DataTransfer();
                                    Array.from(imageInput.files)
                                        .filter((_, i) => i !== index)
                                        .forEach(file => dataTransfer.items.add(file));
                                    imageInput.files = dataTransfer.files;
                                });

                                wrapper.appendChild(img);
                                wrapper.appendChild(removeBtn);
                                previewContainer.appendChild(wrapper);
                            };
                            reader.readAsDataURL(file);
                        });
                    });
                </script>




                <button type="submit" class="btn btn-success">Save Review</button>
            </form>
        </div>

    </div>
    @endsection @section('script')
    <script src="{{ asset('backEnd/') }}/assets/libs/parsleyjs/parsley.min.js"></script>
    <script src="{{ asset('backEnd/') }}/assets/js/pages/form-validation.init.js"></script>
    <script src="{{ asset('backEnd/') }}/assets/libs/select2/js/select2.min.js"></script>
    <script src="{{ asset('backEnd/') }}/assets/js/pages/form-advanced.init.js"></script>

    <!-- JS -->
 
@endsection
