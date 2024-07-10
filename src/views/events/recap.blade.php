{{-- @extends('layouts.app')
@section('title', trans('entities.order') . ' Stand: ' . $stand_name)
@section('title_header', trans('entities.order') . ' Stand: ' . $stand_name)
@section('buttons')
    @if (isset($back_url))
        <a href="{{ url($back_url) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
            title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
    @else
        <a href="{{ url('admin/dashboard') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
            title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
    @endif
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="callout callout-info">
                    @php
                        $amount_no_tax = $amount / (1 + $iva / 100);
                        $amount_with_tax = $amount;
                        $tax_amount = $amount_with_tax - $amount_no_tax;
                    @endphp
                    <p class="m-0"><strong>{{ trans('generals.stand_price') }}</strong> {{ $amount_no_tax }}
                        €
                    </p>
                    {{-- <p class="m-0"><strong>{{ trans('generals.stand_price') }}</strong> {{ $amount }} €</p> --}}
{{-- <p class="m-0"><strong>{{ trans('generals.n_modules') }}</strong> {{ $n_modules }}</p>
                    @if ($extra > 0)
                        @php
                            $extra_no_tax = $extra / (1 + $iva / 100);
                            $extra_with_tax = $extra;
                            $tax_extra = $extra_with_tax - $extra_no_tax;
                        @endphp --}}
{{-- <p class="m-0"><strong>{{ trans('generals.furnishing_not_supplied_price') }}</strong>
                            {{ $extra }} €</p> --}}
{{-- <p class="m-0"><strong>{{ trans('generals.furnishing_not_supplied_price') }}</strong> --}}
{{-- {{ $extra_no_tax }} €</p> --}}
{{-- <p class="m-0"><strong>{{ trans('generals.tax') }} ({{ $iva }}%)</strong>
                            {{ (($amount + $extra) * $iva) / 100 }} €</p> --}}
{{-- <p class="m-0"><strong>{{ trans('generals.tax') }} ({{ $iva }}%)</strong> --}}
{{-- {{ $tax_amount + $tax_extra }} €</p> --}}
{{-- <p class="m-0"><strong>{{ trans('generals.total_tax') }}</strong>
                            {{ $amount + $extra + ($amount + $extra) * ($iva / 100) }} €</p> --}}
{{-- <p class="m-0"><strong>{{ trans('generals.total_tax') }}</strong> --}}
{{--     {{ $amount_with_tax + $extra_with_tax }} €</p>
                    @else
                        <p class="m-0"><strong>{{ trans('generals.tax') }} ({{ $iva }}%)</strong>
                            {{ $tax_amount }} €</p>
                        <p class="m-0"><strong>{{ trans('generals.total_tax') }}</strong> {{ $amount_with_tax }} €</p>
                        {{-- <p class="m-0"><strong>{{ trans('generals.total_tax') }}</strong> {{ $amount + ($amount * $iva / 100) }} €</p> --}}
{{-- @endif
</div>
</div>
</div>
<div class="row">
    @foreach ($orders as $order)
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $order->description }} - {{ $order->furnishing->color }}</h3>
                </div>
                <div class="card-body p-3 d-flex align-items-center justify-content-center">
                    <img style="width:100%;max-width:250px;height:250px;object-fit:cover;"
                        src="{{ getFurnishingImg($order->furnishing_id) }}">
                </div>
                <div class="card-footer">
                    <p class="m-0"><strong>{{ trans('tables.qty') }}</strong> {{ $order->qty }}</p>
                    <p class="m-0"><strong>{{ trans('tables.total') }}</strong> {{ $order->price }} €</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>
@endsection --}}


@extends('layouts/layoutMaster')

@section('title', trans('entities.order') . ' Stand: ' . $stand_name)
@section('title_header', trans('entities.order') . ' Stand: ' . $stand_name)

@section('path', trans('entities.events'))
@section('current', trans('entities.order') . ' Stand: ' . $stand_name)

@section('button')
    @php
        $url = isset($back_url) ? $back_url : url('admin/dashboard');
    @endphp
    <a href="{{ $url }}" class="btn btn-secondary create-new btn-primary waves-effect waves-light"
        data-toggle="tooltip" data-placement="bottom" title="{{ trans('generals.back') }}"><span><i
                class="fas fa-chevron-left"></i>
        </span></a>
    {{-- @if (isset($back_url))

        <a href="{{ url($back_url) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
            title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
    @else
        <a href="{{ url('admin/dashboard') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
            title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
    @endif --}}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            {{-- <div class="card">
                <div class="card-body"> --}}
            <div class="callout callout-info mb-3">
                @php
                    $amount_no_tax = $amount / (1 + $iva / 100);
                    $amount_with_tax = $amount;
                    $tax_amount = $amount_with_tax - $amount_no_tax;
                @endphp
                <p class="m-0"><strong>{{ trans('generals.stand_price') }}</strong> {{ $amount_no_tax }}
                    €
                </p>
                {{-- <p class="m-0"><strong>{{ trans('generals.stand_price') }}</strong> {{ $amount }} €</p> --}}
                <p class="m-0"><strong>{{ trans('generals.n_modules') }}</strong> {{ $n_modules }}</p>
                @if ($extra > 0)
                    @php
                        $extra_no_tax = $extra / (1 + $iva / 100);
                        $extra_with_tax = $extra;
                        $tax_extra = $extra_with_tax - $extra_no_tax;
                    @endphp
                    {{-- <p class="m-0"><strong>{{ trans('generals.furnishing_not_supplied_price') }}</strong>
                            {{ $extra }} €</p> --}}
                    <p class="m-0"><strong>{{ trans('generals.furnishing_not_supplied_price') }}</strong>
                        {{ $extra_no_tax }} €</p>
                    {{-- <p class="m-0"><strong>{{ trans('generals.tax') }} ({{ $iva }}%)</strong>
                            {{ (($amount + $extra) * $iva) / 100 }} €</p> --}}
                    <p class="m-0"><strong>{{ trans('generals.tax') }} ({{ $iva }}%)</strong>
                        {{ $tax_amount + $tax_extra }} €</p>
                    {{-- <p class="m-0"><strong>{{ trans('generals.total_tax') }}</strong>
                            {{ $amount + $extra + ($amount + $extra) * ($iva / 100) }} €</p> --}}
                    <p class="m-0"><strong>{{ trans('generals.total_tax') }}</strong>
                        {{ $amount_with_tax + $extra_with_tax }} €</p>
                @else
                    <p class="m-0"><strong>{{ trans('generals.tax') }} ({{ $iva }}%)</strong>
                        {{ $tax_amount }} €</p>
                    <p class="m-0"><strong>{{ trans('generals.total_tax') }}</strong> {{ $amount_with_tax }} €
                    </p>
                    {{-- <p class="m-0"><strong>{{ trans('generals.total_tax') }}</strong> {{ $amount + ($amount * $iva / 100) }} €</p> --}}
                @endif
            </div>
        </div>{{--
            </div>
        </div> --}}
    </div>
    <div class="row">
        @foreach ($orders as $order)
            <div class="col-3 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title">{{ $order->description }} - {{ $order->furnishing->color }}</h3>
                    </div>
                    <div class="card-body p-3 d-flex align-items-center justify-content-center">
                        <img style="width:100%;max-width:250px;height:250px;object-fit:cover;"
                            src="{{ getFurnishingImg($order->furnishing_id) }}">
                    </div>
                    <div class="card-footer">
                        <p class="m-0"><strong>{{ trans('tables.qty') }}</strong> {{ $order->qty }}</p>
                        <p class="m-0"><strong>{{ trans('tables.total') }}</strong> {{ $order->price }} €</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
