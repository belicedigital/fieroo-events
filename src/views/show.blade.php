@extends('layouts.app')
@section('title', trans('generals.event_subscription', ['event' => $event->title]))
@section('title_header', trans('generals.event_subscription', ['event' => $event->title]))
@section('buttons')
<a href="{{url('admin/dashboard')}}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="{{trans('generals.back')}}"><i class="fas fa-chevron-left"></i></a>
@endsection
@section('content')
<div class="container">
    @if ($errors->any())
    @include('admin.partials.errors', ['errors' => $errors])
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card bs-stepper">
                <div class="card-header">
                    <div class="bs-stepper-header" role="tablist">
                        <div class="step" data-target="#stand-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="stand-part" id="stand-part-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">{{trans('generals.exhibitor_solution')}}</span>
                            </button>
                        </div>
                        {{--<div class="line"></div>
                        <div class="step" data-target="#furnishings-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="furnishings-part" id="furnishings-part-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Arredi</span>
                            </button>
                        </div>--}}
                        <div class="line"></div>
                        <div class="step" data-target="#checkout-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="checkout-part" id="checkout-part-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">{{trans('generals.checkout')}}</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="bs-stepper-content pb-0">
                        <div id="stand-part" class="content col-xs-12 col-sm-12 col-md-6 offset-md-3" role="tabpanel" aria-labelledby="stand-part-trigger">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{trans('entities.stand_type')}}</strong>
                                        <select name="stand_type" class="form-control">
                                            <option value="" selected disabled>{{trans('forms.select_choice')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>{{trans('forms.exhibitor_form.n_modules')}}</strong>
                                        <select name="n_modules" class="form-control">
                                            <option value="" selected disabled>{{trans('forms.select_choice')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-primary" onclick="stepper.next()">{{trans('generals.next')}} »</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--<div id="furnishings-part" class="content" role="tabpanel" aria-labelledby="furnishings-part-trigger">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div id="furnishings-area">
                                        <table class="table table-hover text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>{{trans('tables.description')}}</th>
                                                    <th>{{trans('tables.is_supplied')}}</th>
                                                    <th>{{trans('tables.price')}}</th>
                                                    <th>{{trans('tables.size')}}</th>
                                                    <th class="no-sort">{{trans('tables.color')}}</th>
                                                    <th class="no-sort">{{trans('tables.min_supplied')}}</th>
                                                    <th class="no-sort">{{trans('tables.max_supplied')}}</th>
                                                    <th class="no-sort">{{trans('tables.image')}}</th>
                                                    <th class="no-sort">{{trans('tables.actions')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-primary" onclick="stepper.to(1)">«</button>
                                        <button class="btn btn-primary" onclick="stepper.next()">»</button>
                                    </div>
                                </div>
                            </div>
                        </div>--}}
                        <div id="checkout-part" class="content col-xs-12 col-sm-12 col-md-6 offset-md-3" role="tabpanel" aria-labelledby="checkout-part-trigger">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <form class="d-flex py-5 justify-content-center align-items-center" action="{{route('payment')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="stand_selected">
                                        <input type="hidden" name="modules_selected">
                                        <input type="hidden" name="event_id" value="{{$event->id}}">
                                        <input type="hidden" name="type_of_payment" value="subscription">
                                        <button type="submit" class="btn btn-lg btn-block btn-success"><i class="fab fa-paypal"></i> {{trans('generals.paypal_payment_btn')}}</button>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-primary" onclick="stepper.to(1)">« {{trans('generals.back')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <h3 class="mb-2">{{trans('generals.details_price_checkout')}}</h3>
                    <p class="m-0" id="price">{{trans('generals.stand_price_checkout')}} <span></span> €</p>
                    <p class="m-0" id="size">{{trans('generals.stand_size_checkout')}} <span></span></p>
                    <p class="m-0" id="total">{{trans('generals.subtotal_price_checkout')}} <span></span> €</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    const getStands = () => {
        let base_url = '/admin/stands/getSelectList'
        common_request.post(base_url)
        .then(response => {
            let data = response.data
            if(data.status) {
                $.each(data.data, function(index, value){
                    let option = document.createElement('option')
                    option.value = value.stand_type_id
                    option.text = value.name
                    option.modules = value.max_number_modules
                    option.price = value.price
                    option.size = value.size
                    $('select[name="stand_type"]').append(option)
                })
                $('select[name="stand_type"]').select2({
                    theme: "bootstrap4"
                })
            } else {
                toastr.error(data.message)
            }
        })
        .catch(error => {
            toastr.error(error)
            console.log(error)
        })
    }
    const removeOptions = () => {
        $.each($('select[name="n_modules"]').find('option'), (index, value) => {
            if(index > 0) {
                $(value).remove()
            }
        })
    }
    const initModules = () => {
        removeOptions()
        let selected = $('select[name="stand_type"]').find(':selected');
        let n_modules = selected[0].modules
        for(let i = 0; i < n_modules; i++) {
            let opt = document.createElement('option')
            opt.text = i + 1;
            opt.value = i + 1;
            $('select[name="n_modules"]').append(opt)
        }
        
        $('input[name="modules_selected"]').val('')
    }
    const resetCalcs = () => {
        $('#price').find('span').text('')
        $('#size').find('span').text('')
        $('#total').find('span').text('')
    }
    const checkHasErrorFirstStep = () => {
        console.log($('select[name="stand_type"]').val(), $('select[name="n_modules"]').val())
        if($('select[name="stand_type"]').val() == '' || $('select[name="stand_type"]').val() === null
         || $('select[name="n_modules"]').val() == '' || $('select[name="n_modules"]').val() === null) {
            return true
        }
        return false
    }
    const stepperEl = document.querySelector('.bs-stepper')
    const stepper = new Stepper(stepperEl)
    stepperEl.addEventListener('show.bs-stepper', function (event) {
        if(event.detail.indexStep === 1 && checkHasErrorFirstStep()) {
            event.preventDefault()
            toastr.error("{{trans('messages.first_step_error')}}")
        }
    })
    /*
    stepperEl.addEventListener('shown.bs-stepper', function (event) {
        if(event.detail.indexStep === 1) {
            event.preventDefault()
            loadFurnishings()
        }
    })
    const loadFurnishings = () => {
        let n_modules = parseInt($('select[name="n_modules"]').val())
        let base_url = '/admin/stands/getFurnishingsList'
        common_request.post(base_url, {
            stand_type_id: $('select[name="stand_type"]').val()
        })
        .then(response => {
            let data = response.data
            if(data.status) {
                $.each(data.data, function(index, element) {
                    console.log(element)
                    //let tr = document.createElement('tr')
                    let is_supplied = element.is_supplied ? "{{trans('generals.yes')}}" : "{{trans('generals.no')}}"
                    let row = `<tr data-id="${element.id}">
                                <td>${element.description}</td>
                                <td name="is_supplied">${is_supplied}</td>
                                <td name="price"><span>${element.price}</span> &euro;</td>
                                <td name="size">${element.size}</td>
                                <td>
                                </td>
                                <td></td>
                                <td name="qty_max_supplied">
                                </td>
                                <td></td>
                                <td>
                                </td>
                            </tr>`
                    $('table').find('tbody').append(row)
                })
                // inject data from server to datatable into id="furnishings-area"
            } else {
                toastr.error(data.message)
            }
        })
        .catch(error => {
            toastr.error(error)
            console.log(error)
        })
    }
    */
    const assignCalcs = () => {
        let tot = $('#price').find('span').text() * $('select[name="n_modules"]').val()
        tot = parseFloat(tot).toFixed(2)
        $('#total').find('span').text(tot)
    }
    $(document).ready(function(){
        getStands();
        $('select[name="stand_type"]').on('change', function(e) {
            resetCalcs()
            let selected = $('select[name="stand_type"]').find(':selected');
            let price = selected[0].price
            let size = selected[0].size
            $('#price').find('span').text(price)
            $('#size').find('span').text(size)
            $('input[name="stand_selected"]').val($('select[name="stand_type"]').val())
            initModules()
            let selected_module = $('select[name="n_modules"]').find(':selected');
            $('input[name="modules_selected"]').val(selected_module[0].value)
            if(selected_module[0].value.length > 0) {
                assignCalcs()
                /*
                let tot = $('#price').find('span').text() * $('select[name="n_modules"]').val()
                tot = parseFloat(tot).toFixed(2)
                $('#total').find('span').text(tot)
                */
            }
        })
        $('select[name="n_modules"]').on('change', function(e) {
            $('#total').find('span').text('')
            assignCalcs()
            /*
            let tot = $('#price').find('span').text() * $(this).val()
            tot = parseFloat(tot).toFixed(2)
            $('#total').find('span').text(tot)
            */
            $('input[name="modules_selected"]').val($('select[name="n_modules"]').val())
        })

        /*
        $('table').DataTable({
            "paging": true,
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
        */
    });
</script>
@endsection
