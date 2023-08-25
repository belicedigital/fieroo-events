@extends('layouts.app')
@section('title', trans('entities.subscribed_exhibitors', ['event_title' => $event_title]))
@section('title_header', trans('entities.subscribed_exhibitors', ['event_title' => $event_title]))
@section('buttons')
<a href="{{url('admin/events')}}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="{{trans('generals.back')}}"><i class="fas fa-chevron-left"></i></a>
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
                                <th>{{trans('tables.company')}}</th>
                                <th>{{trans('tables.email')}}</th>
                                <th class="no-sort">{{trans('tables.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $l)
                            <tr>
                                <td>{{$l->user->exhibitor->detail->company}}</td>
                                <td>{{$l->user->email}}</td>
                                <td>
                                    <div class="btn-group btn-group" role="group">
                                        <a data-toggle="tooltip" data-placement="top" title="{{trans('generals.recap')}}" class="btn btn-default" href="{{url('admin/events/'.$l->event_id.'/exhibitor/'.$l->user->exhibitor->id.'/recap-furnishings')}}"><i class="far fa-list-alt"></i></a>
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
                "sSearch": "{{trans('generals.search')}}",
                "oPaginate": {
                    "sFirst": "{{trans('generals.start')}}", // This is the link to the first page
                    "sPrevious": "«", // This is the link to the previous page
                    "sNext": "»", // This is the link to the next page
                    "sLast": "{{trans('generals.end')}}" // This is the link to the last page
                }
            }
        });
    });
</script>
@endsection