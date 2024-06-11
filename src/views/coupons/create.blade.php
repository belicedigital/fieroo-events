@extends('layouts.app')
@section('title', trans('crud.new', ['obj' => trans('entities.coupon')]))
@section('title_header', trans('crud.new', ['obj' => trans('entities.coupon')]))
@section('buttons')
    <a href="{{ url('admin/coupons') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
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
                        <form action="{{ route('coupons.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('forms.code') }}</strong>
                                        <input type="text" name="code" class="form-control"
                                            value="{{ old('code') }}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('tables.exhibitor') }}</strong>
                                        <select id="user_id" name="user_id" class="form-control">
                                            <option value="" disabled>{{ trans('forms.select_choice') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="is_active" name="is_active">
                                            <label for="is_active">{{ trans('forms.publish') }}</label>
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
