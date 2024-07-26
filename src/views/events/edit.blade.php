@extends('layouts/layoutMaster')

@section('title', trans('crud.edit', ['item' => trans('entities.event')]))
@section('title_header', trans('crud.edit', ['item' => trans('entities.event')]))

@section('path', trans('entities.events'))
@section('current', trans('crud.edit', ['item' => trans('entities.event')]))

@section('button')
    <a href="{{ url('admin/events') }}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('events.update', $event->id) }}" method="POST">
                        @METHOD('PATCH')
                        @csrf
                        <input type="hidden" name="selected_stands_ids" value="{{ $selected_stands_ids }}">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('forms.event_name') }}</label>
                                    <input type="text" name="title" class="form-control" value="{{ $event->title }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('forms.start') }}</label>
                                    <input type="date" name="start" class="form-control" value="{{ $event->start }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('forms.end') }}</label>
                                    <input type="date" name="end" class="form-control" value="{{ $event->end }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label
                                        class="form-label fs-6 fw-bolder">{{ trans('forms.subscription_date_open_until') }}</label>
                                    <input type="date" name="subscription_date_open_until" class="form-control"
                                        value="{{ $event->subscription_date_open_until }}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label fs-6 fw-bolder">{{ trans('tables.stand') }}</label>
                                    <select id="stand_type_id" name="stand_type_id[]" class="form-control" multiple>
                                        <option value="">{{ trans('forms.select_choice') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="switch switch-primary switch-sm me-0">
                                        <input class='switch-input'type="checkbox" id="is_published" name="is_published"
                                            {{ $event->is_published ? 'checked' : '' }} data-toggle="toggle"
                                            data-on="{{ trans('generals.yes') }}" data-off="{{ trans('generals.no') }}"
                                            data-onstyle="success" data-offstyle="danger" data-size="sm">
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
                        </div>
                    </form>
                </div>
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
                    console.log(data)
                    if (data.status) {
                        $.each(data.data, function(index, value) {
                            let opt = document.createElement('option')
                            opt.text = value.name
                            opt.value = value.stand_type_id
                            if ($('#selected_stands_ids').val().includes(value.stand_type_id)) {
                                opt.selected = true
                            }
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
