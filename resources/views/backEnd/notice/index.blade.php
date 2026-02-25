@extends('backEnd.layouts.master')
@section('title', 'Notice List')

@section('css')
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />


@endsection

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex justify-content-between align-items-center">
                    <h4 class="page-title mb-0">Notice List</h4>
                    <a href="{{ route('notice.create') }}" class="btn btn-primary rounded-pill mb-0">Create</a>
                </div>
            </div>
        </div>

        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive ">
                            <table id="datatable-buttons" class="table table-striped dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Title</th>
                                        <th>Phone</th>
                                        <th>Whatsapp</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach ($notices as $notice)
                                        <tr>
                                            <td>{{ $loop->index + 1 }} </td>
                                            <td>{{ $notice->title }}</td>
                                            <td>{{ $notice->phone }}</td>
                                            <td>{{ $notice->whatsapp }}</td>
                                            <td>
                                                @if ($notice->status == 1)
                                                    <span class="badge bg-soft-success text-success">Active</span>
                                                @else
                                                    <span class="badge bg-soft-danger text-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="button-list">

                                                    @if ($notice->status == 1)
                                                        <form method="post" action="{{ route('notice.inactive') }}"
                                                            class="d-inline">
                                                            @csrf
                                                            <input type="hidden" value="{{ $notice->id }}"
                                                                name="hidden_id">
                                                            <button type="button"
                                                                class="btn btn-xs  btn-secondary waves-effect waves-light change-confirm"><i
                                                                    class="fe-thumbs-down"></i></button>
                                                        </form>
                                                    @else
                                                        <form method="post" action="{{ route('notice.active') }}"
                                                            class="d-inline">
                                                            @csrf
                                                            <input type="hidden" value="{{ $notice->id }}"
                                                                name="hidden_id">
                                                            <button type="button"
                                                                class="btn btn-xs  btn-success waves-effect waves-light change-confirm"><i
                                                                    class="fe-thumbs-up"></i></button>
                                                        </form>
                                                    @endif

                                                    <a href="{{ route('notice.edit', $notice->id) }}"
                                                        class="btn btn-xs btn-success waves-effect waves-light"><i
                                                            class="fe-edit-1"></i>
                                                    </a>


                                                    <form method="post"
                                                        action="{{ route('notice.destroy', $notice->id) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        <input type="hidden" value="{{ $notice->id }}" name="hidden_id">
                                                        <button type="button"
                                                            class="btn btn-xs  btn-danger waves-effect waves-light change-confirm"><i
                                                                class="fe-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="custom-paginate">
                            {{-- {{ $show_data->links('pagination::bootstrap-4') }} --}}
                        </div>
                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>
@endsection


@section('script')
    <!-- third party js -->
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js">
    </script>
    <script
        src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js">
    </script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js">
    </script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js">
    </script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="{{ asset('/public/backEnd/') }}/assets/js/pages/datatables.init.js"></script>
    <!-- third party js ends -->
@endsection
