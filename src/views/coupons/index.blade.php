{{-- @extends('layouts.app')
@section('title', trans('entities.coupons'))
@section('title_header', trans('entities.coupons'))
@section('buttons')
    <a href="{{ url('admin/coupons/create') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
        title="{{ trans('generals.add') }}"><i class="fas fa-plus"></i></a>
@endsection
@section('content')
    <div class="container-fluid">
        @if (Session::has('success'))
            @include('admin.partials.success')
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive p-3">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>{{ trans('tables.code') }}</th>
                                    <th>{{ trans('entities.exhibitor') }}</th>
                                    <th>{{ trans('tables.percentage') }}</th>
                                    <th>{{ trans('tables.is_active') }}</th>
                                    <th class="no-sort">{{ trans('tables.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $l)
                                    <tr>
                                        <td>{{ $l->code }}</td>
                                        <td>{{ $l->user ? $l->user->user->email : 'N/A' }}</td>
                                        <td>{{ $l->percentage }}</td>
                                        <td>{{ $l->is_active ? trans('generals.yes') : trans('generals.no') }}</td>
                                        <td>
                                            <div class="btn-group btn-group" role="group">
                                                <a data-toggle="tooltip" data-placement="top"
                                                    title="{{ trans('generals.edit') }}" class="btn btn-default"
                                                    href="{{ url('admin/coupons/' . $l->id . '/edit') }}"><i
                                                        class="fa fa-edit"></i></a>
                                                <form action="{{ route('coupons.destroy', $l->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button data-toggle="tooltip" data-placement="top"
                                                        title="{{ trans('generals.delete') }}" class="btn btn-default"
                                                        type="submit"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('form button').on('click', function(e) {
            var $this = $(this);
            e.preventDefault();
            Swal.fire({
                title: "{!! trans('generals.confirm_remove') !!}",
                showCancelButton: true,
                confirmButtonText: "{{ trans('generals.confirm') }}",
                cancelButtonText: "{{ trans('generals.cancel') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    $this.closest('form').submit();
                }
            })
        });
        $(document).ready(function() {
            $('table').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": false,
                "autoWidth": false,
                "responsive": false,
                columnDefs: [{
                    orderable: false,
                    targets: "no-sort"
                }],
                "oLanguage": {
                    "sSearch": "{{ trans('generals.search') }}",
                    "oPaginate": {
                        "sFirst": "{{ trans('generals.start') }}", // This is the link to the first page
                        "sPrevious": "«", // This is the link to the previous page
                        "sNext": "»", // This is the link to the next page
                        "sLast": "{{ trans('generals.end') }}" // This is the link to the last page
                    }
                }
            });
        });
    </script>
@endsection
--}}

@extends('layouts/layoutMaster')

@section('title', trans('entities.coupons'))
@section('title_header', trans('entities.coupons'))

@section('button')
    <a href="{{ url('admin/coupons/create') }}" class="btn btn-secondary create-new btn-primary waves-effect waves-light"
        data-toggle="tooltip" data-placement="bottom" title="{{ trans('generals.add') }}"><span><i
                class="ti ti-plus me-sm-1"></i>
        </span></a>
    {{-- <a href="{{ url('admin/coupons/create') }}" class="btn btn-secondary create-new btn-primary waves-effect waves-light"
        data-toggle="tooltip" data-placement="bottom"><span><i class="ti ti-plus me-sm-1"></i>
            <span class="d-none d-sm-inline-block">{{ trans('generals.add') }}</span>
        </span></a> --}}
@endsection

@section('path', trans('entities.coupons'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>{{ trans('tables.code') }}</th>
                                <th>{{ trans('entities.exhibitor') }}</th>
                                <th>{{ trans('tables.percentage') }}</th>
                                <th>{{ trans('tables.is_active') }}</th>
                                <th class="no-sort">{{ trans('tables.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                                <tr>
                                    <td>{{ $l->code }}</td>
                                    <td>{{ $l->user ? $l->user->user->email : 'N/A' }}</td>
                                    <td>{{ $l->percentage }}</td>
                                    <td>{{ $l->is_active ? trans('generals.yes') : trans('generals.no') }}</td>
                                    <td>
                                        <div class="btn-group btn-group" role="group">
                                            <a data-toggle="tooltip" data-placement="top"
                                                title="{{ trans('generals.edit') }}" class="btn btn-default"
                                                href="{{ url('admin/coupons/' . $l->id . '/edit') }}"><i
                                                    class="fa fa-edit"></i></a>
                                            <form action="{{ route('coupons.destroy', $l->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button data-toggle="tooltip" data-placement="top"
                                                    title="{{ trans('generals.delete') }}" class="btn btn-default"
                                                    type="submit"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <!-- Table -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('page-script')
    <script>
        $('form button').on('click', function(e) {
            var $this = $(this);
            e.preventDefault();
            Swal.fire({
                title: "{!! trans('generals.confirm_remove') !!}",
                showCancelButton: true,
                confirmButtonText: "{{ trans('generals.confirm') }}",
                cancelButtonText: "{{ trans('generals.cancel') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    $this.closest('form').submit();
                }
            })
        });
        $(document).ready(function() {
            $('table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                columnDefs: [{
                    orderable: false,
                    targets: "no-sort"
                }],
                "lengthMenu": [5, 10, 25, 50],
                "pageLength": 10,
                "language": {
                    "search": "{{ trans('generals.search') }}",
                    "paginate": {
                        "first": "{{ trans('generals.start') }}",
                        "previous": "«",
                        "next": "»",
                        "last": "{{ trans('generals.end') }}"
                    }
                }
            });
        });
    </script>
@endsection
