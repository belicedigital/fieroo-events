@extends('layouts.app')
@section('title', trans('entities.furnishings').' '.$stand_name)
@section('title_header', trans('entities.furnishings').' '.$stand_name)
@section('buttons')
<div>
    <p class="mb-3 bg-danger text-center p-3 text-lg"><strong>{{trans('generals.total')}}</strong>: <span data-total></span> €</p>
    <form id="payment-form" class="d-flex justify-content-center align-items-center" action="{{route('payment-furnishings')}}" method="POST">
        @csrf
        <input type="hidden" name="stand_type_id" value="{{$stand_type_id}}">
        <input type="hidden" name="event_id" value="{{$event_id}}">
        <input type="hidden" name="type_of_payment" value="furnishing">
        <input type="hidden" name="data">
        <button type="submit" class="btn btn-success text-uppercase"><i class="fab fa-paypal"></i> {{trans('generals.proceed_order')}}</button>
    </form>
    {{--<button type="button" class="btn btn-success text-uppercase" onclick="closeOrder(this)"><i class="fas fa-check mr-1"></i> {{trans('generals.confirm_order')}}</button>--}}
</div>
@endsection
@section('content')

{{--<input type="hidden" name="stand_type_id" value="{{$stand_type_id}}">
<input type="hidden" name="event_id" value="{{$event_id}}">
--}}
<div class="container-fluid">
    @if (Session::has('success'))
    @include('admin.partials.success')
    @endif
    @if ($errors->any())
    @include('admin.partials.errors', ['errors' => $errors])
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card card-tabs">
                <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach($modules as $key => $module)
                        <li class="nav-item">
                            <a class="nav-link {{$key == 0 ? 'active' : ''}}" id="tab-{{$module->id}}" data-toggle="pill" href="#tab--{{$module->id}}" role="tab" aria-controls="tab--{{$module->id}}" aria-selected="{{$key == 0 ? 'true' : 'false'}}">{{$module->code}}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-body table-responsive p-3">
                    <div class="tab-content" id="custom-tabs-one-tabContent">
                        @foreach($modules as $key => $module)
                        <div class="tab-pane fade {{$key == 0 ? 'show active' : ''}}" id="tab--{{$module->id}}" role="tabpanel" aria-labelledby="tab-{{$module->id}}">
                            <table class="table table-hover text-nowrap" data-module-id="{{$module->id}}">
                                <thead>
                                    <tr>
                                        <th>{{trans('tables.description')}}</th>
                                        <th>{{trans('tables.is_supplied')}}</th>
                                        <th>{{trans('tables.price')}}</th>
                                        <th>{{trans('tables.size')}}</th>
                                        <th class="no-sort">{{trans('tables.color')}}</th>
                                        <th class="no-sort">{{trans('tables.qty')}}</th>
                                        <th class="no-sort">{{trans('tables.max_supplied')}}</th>
                                        <th class="no-sort">{{trans('tables.image')}}</th>
                                        <th class="no-sort">{{trans('tables.tot_partial')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($list as $l)
                                    @php
                                    $class = '';
                                    @endphp
                                    {{--<tr data-id="{{$l->id}}" class="{{$l->is_supplied && $l->min > 0 ? 'bg-info' : ''}}">--}}
                                    <tr data-id="{{$l->id}}" class="{{$class}}" data-extra-price="{{$l->extra_price}}" data-max="{{$l->max}}">
                                        <td>{{$l->description}}</td>
                                        <td data-value="{{$l->is_supplied}}" name="is_supplied">
                                            {{$l->is_supplied ? trans('generals.yes') : trans('generals.no')}}
                                        </td>
                                        <td name="price"><span>{{$l->price}}</span> &euro;</td>
                                        <td name="size">{{$l->size}}</td>
                                        <td>
                                            @if(count($l->variants) > 0)
                                            <select name="variant" class="form-control">
                                                <option data-is-variant="false" data-opt-id="{{$l->id}}" value="{{$l->color}}">{{$l->color}}</option>
                                                @foreach($l->variants as $variant)
                                                <option data-is-variant="true" data-opt-id="{{$variant->id}}" value="{{$variant->color}}">{{$variant->color}}</option>
                                                @endforeach
                                            </select>
                                            @else
                                            {{$l->color}}
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" name="qty" class="form-control" min="{{$l->min > 0 ? $l->min : 0}}" value="{{$l->max > 0 ? $l->max : 0}}">
                                        </td>
                                        <td name="qty_max_supplied">
                                            {{$l->is_supplied ? ($l->extra_price ? 'N/A' : $l->max) : 'N/A'}}
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" onclick="assignImg(this)" role="button" data-toggle="modal" data-target="#modalImg"><img src="{{asset('img/furnishings/'.$l->file_path)}}" class="table-img"></a>
                                        </td>
                                        <td class="text-lg text-bold"><span data-total-partial=""></span> €</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalImg" tabindex="-1" role="dialog" aria-labelledby="modalImgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" class="w-100">
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    const assignImg = (el) => {
        let src = $(el).find('img').attr('src')
        $('#modalImg').find('img').attr('src', src)

    }
    const updateData = (row, id, is_variant) => {
        common_request.post('/admin/stands/getData', {
            id: id,
            stand_type_id: $('input[name="stand_type_id"]').val(),
            is_variant: is_variant
        })
        .then(response => {
            let data = response.data
            console.log(data)
            if(data.status) {
                let extra_price = parseInt(data.data.extra_price) ? "{{trans('generals.no')}}" : "{{trans('generals.yes')}}"
                let val_extra_price = parseInt(data.data.extra_price)
                let val_is_supplied = parseInt(data.data.is_supplied)
                let val_min = parseInt(data.data.min)
                let val_max = parseInt(data.data.max)
                if(val_extra_price) {
                    row.removeClass().addClass('bg-primary')
                    row.find('input[name="qty"]').attr('min', '0')
                    row.find('input[name="qty"]').val('0')
                    row.find('td[name="qty_max_supplied"]').text('N/A')
                } else if(val_is_supplied) {
                    row.removeClass().addClass('bg-success')
                    if(val_min > 0) {
                        row.removeClass().addClass('bg-warning')
                    }
                    row.find('input[name="qty"]').attr('min', val_min > 0 ? val_min : 0)
                    row.find('input[name="qty"]').val(val_max > 0 ? val_max : 0)
                    row.find('td[name="qty_max_supplied"]').text(val_max)
                }
                if(is_variant && !val_extra_price && val_is_supplied) {
                    extra_price = "{{trans('generals.yes')}}"
                    row.removeClass().addClass('bg-success')
                    if(val_min > 0) {
                        row.removeClass().addClass('bg-warning')
                    }
                }
                row.find('td[name="is_supplied"]').text(extra_price)
                row.find('td[name="price"] span').text(data.data.price)
                row.find('td[name="size"]').text(data.data.size)
                let path = "{{asset('upload/furnishings')}}" + '/' + data.data.file_path
                row.find('td a > img').attr('src', path)
                row.find('[data-total-partial]').text()
                row.attr({
                    'data-id': data.data.id,
                    'data-extra-price': data.data.extra_price,
                    'data-max': data.data.max
                })
                initSubTotal(row)
            } else {
                toastr.error(data.message)
            }
        })
        .catch(error => {
            toastr.error(error)
            console.log(error)
        })
    }

    const assignSubTotal = (row) => {
        let is_supplied = parseInt(row.find('[name="is_supplied"]').data('value'))
        let price = parseFloat(row.find('[name="price"] > span').text())
        let qty = parseInt(row.find('[name="qty"]').val()) > 0 ? parseInt(row.find('[name="qty"]').val()) : 0
        //let is_variant = parseInt(row.find('option:selected').data('is-variant'))
        let extra_price = parseInt(row.attr('data-extra-price'))
        let max_value = parseInt(row.attr('data-max'))
        let max = parseInt(is_supplied ? (extra_price ? 0 : max_value) : 0)
        let subtotal = 0
        //console.log(price, qty, extra_price, max, subtotal)
        if(extra_price) {
            subtotal = parseFloat(price * qty)
        } else {
            if(is_supplied) {
                if(qty > max) {
                    let diff = parseInt(qty - max)
                    subtotal = parseFloat(price * diff)
                }
            } else {
                subtotal = parseFloat(price * qty)
            }
        }
        console.log(row, subtotal)
        row.find('[data-total-partial]').text(subtotal)
    }

    const getTotalOfPartials = () => {
        let total = 0
        $.each($('[data-total-partial]'), function(index, element) {
            let value = parseInt($(element).text())
            total += value
        });
        return total
    }

    const initSubTotal = (row) => {
        if(row !== undefined) {
            assignSubTotal(row)
        } else {
            $.each($('[data-total-partial]'), function(index, element) {
                let row = $(element).closest('tr')
                assignSubTotal(row)
            });
        }

        $('[data-total]').text(getTotalOfPartials())
    }

    const formatData = () => {
        let obj = []
        $.each($('table').find('tbody > tr'), function(i, e) {
            let qty = parseInt($(e).find('[name="qty"]').val())
            if(qty > 0) {
                let row = {}
                row.id = $(e).attr('data-id')
                row.qty = qty
                row.is_supplied = $(e).find('[name="is_supplied"]').attr('data-value')
                row.price = $(e).find('[data-total-partial]').text()
                //row.module_id = $(e).closest('table').attr('data-module-id')
                obj.push(row)
            }
        })
        return obj
    }

    // const closeOrder = (el) => {
    //     let event_id = $('input[name="event_id"]').val()
    //     let stand_type_id = $('input[name="stand_type_id"]').val()
    //     Swal.fire({
    //         icon: 'warning',
    //         title: "{!! trans('generals.confirm_order') !!}",
    //         //html: "{{trans('messages.default_cart')}}" + "{!! trans('messages.required_furnishings') !!}",
    //         html: "{!! trans('messages.confirm_order') !!}",
    //         showCancelButton: true,
    //         confirmButtonText: "{{ trans('generals.confirm') }}",
    //         cancelButtonText: "{{ trans('generals.cancel') }}",
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $(el).addClass('disabled')
    //             //common_request.post('/admin/cart/confirm')
    //             common_request.post('/admin/paypal/furnishings', {
    //                 event_id: event_id,
    //                 stand_type_id: stand_type_id,
    //                 data: JSON.stringify(formatData())
    //             })
    //             .then(response => {
    //                 let data = response.data
    //                 if(data.status) {
    //                     toastr.success(data.message, '', {
    //                         onShown: function() {
    //                             setTimeout(function(){
    //                                 window.location.reload()
    //                             }, 2000);
    //                         }
    //                     })
    //                 } else {
    //                     $(el).removeClass('disabled')
    //                     toastr.error(data.message)
    //                 }
    //             })
    //             .catch(error => {
    //                 $(el).removeClass('disabled')
    //                 toastr.error(error)
    //                 console.log(error)
    //             })
    //         }
    //     })
    // }

    $(document).ready(function() {

        $('#payment-form button').on('click', function(e) {
            e.preventDefault();
            $('input[name="data"]').val(JSON.stringify(formatData()))
            if($('input[name="data"]').val().length > 0) {
                $('#payment-form').submit()
            }
        })

        initSubTotal()

        $('select[name="variant"]').on('change', function() {
            let $this = $(this)
            let row = $this.closest('tr')
            let id = $this.find('option:selected').data('opt-id')
            let is_variant = $this.find('option:selected').data('is-variant')
            updateData(row, id, is_variant)
            //initSubTotal(row)
            //checkItemInCart(id)
        });

        $('input[type="number"]').on('change', function() {
            let $this = $(this)
            let row = $this.closest('tr')
            initSubTotal(row)
        })

        $('table').DataTable({
            "paging": false,
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
            order: [[1, 'desc']],
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