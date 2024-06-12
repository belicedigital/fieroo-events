@extends('layouts.app')
@section('title', trans('crud.edit', ['item' => trans('entities.coupons')]))
@section('title_header', trans('crud.edit', ['item' => trans('entities.coupons')]))
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
                        <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
                            @METHOD('PATCH')
                            @csrf
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('forms.code') }}</strong>
                                        <input type="text" name="code" class="form-control"
                                            value="{{ $coupon->code }}" required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('forms.start') }}</strong>
                                        <input type="text" name="percentage" class="form-control"
                                            value="{{ $coupon->percentage }}" required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{ trans('tables.exhibitor') }}</strong>
                                        <select id="user_id" name="user_id" class="form-control">
                                            <option value="">{{ trans('forms.select_choice') }}</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $user->id == $coupon->user->user_id ? 'selected' : '' }}>
                                                    {{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="is_active" name="is_active"
                                                {{ $coupon->is_active ? 'checked' : '' }}>
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
