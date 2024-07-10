{{-- @extends('layouts.app')
@section('title', trans('entities.subscribed_exhibitors', ['event_title' => $event_title]))
@section('title_header', trans('entities.subscribed_exhibitors', ['event_title' => $event_title]))
@section('buttons')
    <a href="{{ url('admin/events') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
        title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
    <a href="{{ url('admin/export/event/' . $id . '/exhibitors') }}" class="btn btn-primary" data-toggle="tooltip"
        data-placement="bottom" title="{{ trans('generals.export') }}"><i class="fas fa-file-export"></i></a>
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
                                    <th>{{ trans('tables.company') }}</th>
                                    <th>{{ trans('tables.email') }}</th>
                                    <th class="no-sort">{{ trans('tables.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list as $l)
                                    <tr>
                                        <td>{{ $l->user->exhibitor->detail->company }}</td>
                                        <td>{{ $l->user->email }}</td>
                                        <td>
                                            <div class="btn-group btn-group" role="group">
                                                <a data-toggle="tooltip" data-placement="top"
                                                    title="{{ trans('generals.recap') }}" class="btn btn-default"
                                                    href="{{ url('admin/events/' . $l->event_id . '/exhibitor/' . $l->user->exhibitor->id . '/recap-furnishings') }}"><i
                                                        class="far fa-list-alt"></i></a>
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
@endsection --}}
@extends('layouts/layoutMaster')

@section('title', trans('entities.subscribed_exhibitors', ['event_title' => $event_title]))
@section('title_header', trans('entities.subscribed_exhibitors', ['event_title' => $event_title]))

@section('path', trans('entities.events'))
@section('current', trans('entities.subscribed_exhibitors', ['event_title' => $event_title]))

@section('button')
    <a href="{{ url('admin/events') }}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
    <a href="{{ url('admin/export/event/' . $id . '/exhibitors') }}" class="btn btn-primary" data-bs-toggle="tooltip"
        data-bs-placement="bottom" data-bs-original-title="{{ trans('generals.export') }}"><i
            class="fas fa-file-export"></i></a>
@endsection

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>{{ trans('tables.company') }}</th>
                                <th>{{ trans('tables.email') }}</th>
                                <th class="no-sort">{{ trans('tables.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $l)
                                <tr>
                                    <td>{{ $l->user->exhibitor->detail->company }}</td>
                                    <td>{{ $l->user->email }}</td>
                                    <td>
                                        <div class="btn-group btn-group" role="group">
                                            <a data-toggle="tooltip" data-placement="top"
                                                title="{{ trans('generals.recap') }}" class="btn btn-default"
                                                href="{{ url('admin/events/' . $l->event_id . '/exhibitor/' . $l->user->exhibitor->id . '/recap-furnishings') }}"><i
                                                    class="far fa-list-alt"></i></a>
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
