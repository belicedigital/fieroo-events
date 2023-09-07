@extends('layouts.app')
@section('title', trans('entities.order').' Stand: '.$stand_name)
@section('title_header', trans('entities.order').' Stand: '.$stand_name)
@section('buttons')
@if(isset($back_url))
<a href="{{url($back_url)}}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="{{trans('generals.back')}}"><i class="fas fa-chevron-left"></i></a>
@else
<a href="{{url('admin/dashboard')}}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="{{trans('generals.back')}}"><i class="fas fa-chevron-left"></i></a>
@endif
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="callout callout-info">
                <p class="m-0"><strong>{{trans('generals.stand_price')}}</strong> {{$amount}} €</p>
                @if($extra > 0)
                <p class="m-0"><strong>{{trans('generals.furnishing_not_supplied_price')}}</strong> {{$extra}} €</p>
                @endif
                <p class="m-0"><strong>{{trans('generals.n_modules')}}</strong> {{$n_modules}}</p>
            </div>
        </div>
    </div>
    <div class="row">
        @foreach($orders as $order)
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$order->description}}</h3>
                </div>
                <div class="card-body p-3 d-flex align-items-center justify-content-center">
                    <img style="width:100%;max-width:250px;height:250px;object-fit:cover;" src="{{getFurnishingImg($order->furnishing_id)}}">
                </div>
                <div class="card-footer">
                    <p class="m-0"><strong>{{trans('tables.qty')}}</strong> {{$order->qty}}</p>
                    <p class="m-0"><strong>{{trans('tables.total')}}</strong> {{$order->price}} €</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection