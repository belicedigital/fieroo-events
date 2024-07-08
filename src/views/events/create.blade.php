{{-- @extends('layouts.app')
@section('title', trans('crud.new', ['obj' => trans('entities.event')]))
@section('title_header', trans('crud.new', ['obj' => trans('entities.event')]))
@section('buttons')
    <a href="{{ url('admin/events') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
        title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
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
                        <form action="{{ route('events.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('forms.event_name') }}</strong>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ old('title') }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('forms.start') }}</strong>
                                        <input type="date" name="start" class="form-control"
                                            value="{{ old('start') }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('forms.end') }}</strong>
                                        <input type="date" name="end" class="form-control"
                                            value="{{ old('end') }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('forms.subscription_date_open_until') }}</strong>
                                        <input type="date" name="subscription_date_open_until" class="form-control"
                                            value="{{ old('subscription_date_open_until') }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('tables.stand') }}</strong>
                                        <select id="stand_type_id" name="stand_type_id[]" class="form-control" multiple>
                                            <option value="" disabled>{{ trans('forms.select_choice') }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="is_published" name="is_published">
                                            <label for="is_published">{{ trans('forms.publish') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-primary">{{ trans('generals.save') }}</button>
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
        const initStands = () => {
            common_request.post('/admin/furnishings/stands')
                .then(response => {
                    let data = response.data
                    if (data.status) {
                        $.each(data.data, function(index, value) {
                            let opt = document.createElement('option')
                            opt.text = value.name + ' (' + value.size + ' {{ trans('generals.mq') }})';
                            opt.value = value.stand_type_id;
                            $('#stand_type_id').append(opt)
                        })
                        $('#stand_type_id').select2();
                    } else {
                        toastr.error(data.message)
                    }
                })
                .catch(error => {
                    toastr.error(error)
                    console.log(error)
                })
        }
        $(document).ready(function() {
            initStands()
        });
    </script>
@endsection
 --}}

@extends('layouts/layoutMaster')

@section('title', trans('crud.new', ['obj' => trans('entities.event')]))
@section('title_header', trans('crud.new', ['obj' => trans('entities.event')]))

@section('path', trans('entities.events'))
@section('current', trans('crud.new', ['obj' => trans('entities.event')]))

@section('buttons')
    <a href="{{ url('admin/events') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
        title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('events.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('forms.event_name') }}</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('forms.start') }}</label>
                                    <input type="date" name="start" class="form-control" value="{{ old('start') }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('forms.end') }}</label>
                                    <input type="date" name="end" class="form-control" value="{{ old('end') }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label
                                        class="form-label fs-6 fw-bolder">{{ trans('forms.subscription_date_open_until') }}</label>
                                    <input type="date" name="subscription_date_open_until" class="form-control"
                                        value="{{ old('subscription_date_open_until') }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('tables.stand') }}</label>
                                    <select id="stand_type_id" name="stand_type_id[]" class="form-control" multiple>
                                        <option value="" disabled>{{ trans('forms.select_choice') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="switch switch-primary switch-sm me-0">
                                        <input class='switch-input'type="checkbox" id="is_published" name="is_published"
                                            data-toggle="toggle" data-on="{{ trans('generals.yes') }}"
                                            data-off="{{ trans('generals.no') }}" data-onstyle="success"
                                            data-offstyle="danger" data-size="sm">
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on"></span>
                                            <span class="switch-off"></span>
                                        </span>
                                        <span class="switch-label fs-6 fw-bolder">{{ trans('forms.publish') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-end">
                            <button type="submit" class="btn btn-primary">{{ trans('generals.save') }}</button>
                            <a href="{{ url('admin/events') }}"
                                class="btn btn-label-secondary">{{ trans('generals.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')
    <script>
        const initStands = () => {
            common_request.post('/admin/furnishings/stands')
                .then(response => {
                    let data = response.data
                    if (data.status) {
                        $.each(data.data, function(index, value) {
                            let opt = document.createElement('option')
                            opt.text = value.name + ' (' + value.size + ' {{ trans('generals.mq') }})';
                            opt.value = value.stand_type_id;
                            $('#stand_type_id').append(opt)
                        })
                        $('#stand_type_id').select2();
                    } else {
                        toastr.error(data.message)
                    }
                })
                .catch(error => {
                    toastr.error(error)
                    console.log(error)
                })
        }
        $(document).ready(function() {
            initStands()
        });
    </script>
@endsection
