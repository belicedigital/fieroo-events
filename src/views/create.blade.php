@extends('layouts.app')
@section('title', trans('crud.new', ['obj' => trans('entities.event')]))
@section('title_header', trans('crud.new', ['obj' => trans('entities.event')]))
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
                    <form action="{{route('events.store')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>{{trans('forms.event_name')}}</strong>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>{{trans('forms.start')}}</strong>
                                    <input type="date" name="start" class="form-control" value="{{ old('start') }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>{{trans('forms.end')}}</strong>
                                    <input type="date" name="end" class="form-control" value="{{ old('end') }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>{{trans('forms.subscription_date_open_until')}}</strong>
                                    <input type="date" name="subscription_date_open_until" class="form-control" value="{{ old('subscription_date_open_until') }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="is_published" name="is_published">
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