@extends('layouts.app')
@section('title', trans('crud.edit', ['item' => trans('entities.event')]))
@section('title_header', trans('crud.edit', ['item' => trans('entities.event')]))
@section('buttons')
<a href="{{url('admin/events')}}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="{{trans('generals.back')}}"><i class="fas fa-chevron-left"></i></a>
@endsection
@section('content')
<div class="container">
    @if ($errors->any())
    @include('admin.partials.errors', ['errors' => $errors])
    @endif
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('events.update', $event->id)}}" method="POST">
                        @METHOD('PATCH')
                        @csrf
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>{{trans('forms.event_name')}}</strong>
                                    <input type="text" name="title" class="form-control" value="{{ $event->title }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>{{trans('forms.start')}}</strong>
                                    <input type="date" name="start" class="form-control" value="{{ $event->start }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>{{trans('forms.end')}}</strong>
                                    <input type="date" name="end" class="form-control" value="{{ $event->end }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>{{trans('forms.subscription_date_open_until')}}</strong>
                                    <input type="date" name="subscription_date_open_until" class="form-control" value="{{ $event->subscription_date_open_until }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="is_published" name="is_published" {{$event->is_published ? 'checked' : ''}}>
                                        <label for="is_published">{{trans('forms.publish')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-primary">{{trans('generals.save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        let start = $('input[name="start"]')
        let end = $('input[name="end"]')
        let subscription_date_open_until = $('input[name="subscription_date_open_until"]')
        start.change(function() {
            if(start.val() > end.val()) {
                toastr.error('La data di fine evento deve essere maggiore della data di inizio evento')
            }
        })
        end.change(function() {
            if(end.val() < start.val()) {
                toastr.error('La data di fine evento deve essere maggiore della data di inizio evento')
            }
        })
        subscription_date_open_until.change(function() {
            if(subscription_date_open_until.val() > start.val() || subscription_date_open_until.val() > end.val()) {
                toastr.error('La data di fine apertura iscrizioni deve essere minore della data inizio evento e della data fine evento')
            }
        })
    });
</script>
@endsection