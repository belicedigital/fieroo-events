{{-- @extends('layouts.app')
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
                                        <strong>{{ trans('tables.code') }}</strong>
                                        <input type="text" name="code" class="form-control"
                                            value="{{ old('code') }}" required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('tables.percentage') }}</strong>
                                        <input type="text" name="percentage" class="form-control"
                                            value="{{ old('percentage') }}" required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('entities.exhibitor') }}</strong>
                                        <select id="user_id" name="user_id" class="form-control">
                                            <option value="">{{ trans('forms.select_choice') }}</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->user_id }}">{{ $user->user->name }}</option>
                                            @endforeach
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
@endsection --}}


@extends('layouts/layoutMaster')

@section('title', trans('crud.new', ['obj' => trans('entities.coupon')]))
@section('title_header', trans('crud.new', ['obj' => trans('entities.coupon')]))

@section('path', trans('entities.coupons'))
@section('current', trans('crud.new', ['obj' => trans('entities.coupon')]))

@section('button')
    <a href="{{ url('admin/coupons') }}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom"
        data-bs-original-title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('coupons.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <strong>{{ trans('tables.code') }}</strong>
                                    <input type="text" name="code" class="form-control" value="{{ old('code') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <strong>{{ trans('tables.percentage') }}</strong>
                                    <input type="text" name="percentage" class="form-control"
                                        value="{{ old('percentage') }}" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <strong>{{ trans('entities.exhibitor') }}</strong>
                                    <select id="user_id" name="user_id" class="form-control">
                                        <option value="">{{ trans('forms.select_choice') }}</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->user_id }}">{{ $user->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <label class="switch switch-primary switch-sm me-0">
                                        <input class='switch-input'type="checkbox" id="is_active" name="is_active"
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
                            {{-- <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group mb-3">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="is_active" name="is_active">
                                        <label for="is_active">{{ trans('forms.publish') }}</label>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 text-end">
                            <button type="submit" class="btn btn-primary">{{ trans('generals.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
