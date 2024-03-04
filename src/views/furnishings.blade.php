@extends('layouts.app')
@section('title', trans('entities.furnishings') . ' ' . $stand_name)
@section('title_header', trans('entities.furnishings') . ' ' . $stand_name)
@section('buttons')
    <div>
        <p class="mb-3 bg-danger text-center p-3 text-lg">
            <strong>{{ trans('generals.total') }}</strong>: <span data-total></span>€
            <br>
            <strong>{{ trans('generals.tax') }}</strong>: <span data-tax="{{ $iva }}">{{ $iva }}</span>%
            <br>
            <strong>{{ trans('generals.total_tax') }}</strong>: <span data-total-tax></span>€
        </p>
        <button type="button" class="btn btn-success text-uppercase" data-toggle="modal" data-target="#modalPayment">
            <i class="fab fa-cc-stripe"></i> {{ trans('generals.proceed_order') }}
        </button>
    </div>
@endsection
@section('content')
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
                    <div class="card-header p-0">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($modules as $key => $module)
                                <li class="nav-item">
                                    <a class="nav-link {{ $key == 0 ? 'active' : '' }}" id="tab-{{ $module->id }}"
                                        data-toggle="pill" href="#tab--{{ $module->id }}" role="tab"
                                        aria-controls="tab--{{ $module->id }}"
                                        aria-selected="{{ $key == 0 ? 'true' : 'false' }}">{{ $module->code }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body table-responsive p-3">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            @foreach ($modules as $key => $module)
                                <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}"
                                    id="tab--{{ $module->id }}" role="tabpanel"
                                    aria-labelledby="tab-{{ $module->id }}">
                                    <table class="table table-hover text-nowrap" data-module-id="{{ $module->id }}">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('tables.description') }}</th>
                                                <th>{{ trans('tables.is_supplied') }}</th>
                                                <th>{{ trans('tables.price') }}</th>
                                                <th>{{ trans('tables.size') }}</th>
                                                <th class="no-sort">{{ trans('tables.color') }}</th>
                                                <th class="no-sort">{{ trans('tables.qty') }}</th>
                                                <th class="no-sort">{{ trans('tables.max_supplied') }}</th>
                                                <th class="no-sort">{{ trans('tables.image') }}</th>
                                                <th class="no-sort">{{ trans('tables.tot_partial') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($list as $l)
                                                @php
                                                    $class = '';
                                                @endphp
                                                {{-- <tr data-id="{{$l->id}}" class="{{$l->is_supplied && $l->min > 0 ? 'bg-info' : ''}}"> --}}
                                                <tr data-id="{{ $l->id }}" class="{{ $class }}"
                                                    data-extra-price="{{ $l->extra_price }}"
                                                    data-max="{{ $l->max }}">
                                                    <td>{{ $l->description }}</td>
                                                    <td data-value="{{ $l->is_supplied }}" name="is_supplied">
                                                        {{ $l->is_supplied ? trans('generals.yes') : trans('generals.no') }}
                                                    </td>
                                                    <td name="price"><span>{{ $l->price }}</span> &euro;</td>
                                                    <td name="size">{{ $l->size }}</td>
                                                    <td>
                                                        @if (count($l->variants) > 0)
                                                            <select name="variant" class="form-control">
                                                                <option data-is-variant="false"
                                                                    data-opt-id="{{ $l->id }}"
                                                                    value="{{ $l->color }}">{{ $l->color }}
                                                                </option>
                                                                @foreach ($l->variants as $variant)
                                                                    <option data-is-variant="true"
                                                                        data-opt-id="{{ $variant->id }}"
                                                                        value="{{ $variant->color }}">
                                                                        {{ $variant->color }}</option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            {{ $l->color }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="number" name="qty" class="form-control"
                                                            min="{{ $l->min > 0 ? $l->min : 0 }}"
                                                            value="{{ $l->max > 0 ? $l->max : 0 }}">
                                                    </td>
                                                    <td name="qty_max_supplied">
                                                        {{ $l->is_supplied ? ($l->extra_price ? 'N/A' : $l->max) : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0);" onclick="assignImg(this)"
                                                            role="button" data-toggle="modal" data-target="#modalImg"><img
                                                                src="{{ asset('img/furnishings/' . $l->file_path) }}"
                                                                class="table-img"></a>
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
    <div class="modal fade" id="modalPayment" tabindex="-1" role="dialog" aria-labelledby="modalPaymentLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="stripePayment" class="d-flex flex-column" action="{{ route('payment-furnishingss') }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="stand_type_id" value="{{ $stand_type_id }}">
                        <input type="hidden" name="event_id" value="{{ $event_id }}">
                        <input type="hidden" name="type_of_payment" value="furnishing">
                        <input type="hidden" id="paymentMethodId" name="paymentMethodId">
                        <input type="hidden" name="data">
                        <div class="form-group">
                            <h3>{{ trans('generals.stripe_confirm_payment') }}</h3>
                            <p class="lead">{{ trans('generals.stripe_confirm_payment_text') }}</p>
                        </div>
                        <div class="form-group">
                            <input id="card-holder-name" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <div id="card-element" class="form-control"></div>
                        </div>
                        <button id="card-button" class="btn btn-lg btn-block btn-success"><i
                                class="fab fa-cc-stripe"></i>
                            {{ trans('generals.stripe_payment_btn') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const initStripe = () => {
            const stripe = Stripe("{{ env('STRIPE_KEY') }}");

            const elements = stripe.elements();
            const cardElement = elements.create('card');

            cardElement.mount('#card-element');

            const cardHolderName = document.getElementById('card-holder-name');
            const cardButton = document.getElementById('card-button');

            cardButton.addEventListener('click', async (e) => {
                e.preventDefault()
                const {
                    paymentMethod,
                    error
                } = await stripe.createPaymentMethod(
                    'card', cardElement, {
                        billing_details: {
                            name: cardHolderName.value
                        }
                    }
                );

                if (error) {
                    toastr.error(error.message)
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: "{{ trans('generals.stripe_card_verified') }}",
                        confirmButtonText: "{{ trans('generals.stripe_confirm_payment') }}",
                        allowOutsideClick: false,
                    }).then(() => {
                        $('input[name="data"]').val(JSON.stringify(formatData()))
                        if ($('input[name="data"]').val().length > 0) {
                            $('#paymentMethodId').val(paymentMethod.id)
                            $('#stripePayment').trigger('submit')
                        }
                        $('#modalPayment').hide()
                    })
                }
            });
        }
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
                    if (data.status) {
                        let extra_price = parseInt(data.data.extra_price) ? "{{ trans('generals.no') }}" :
                            "{{ trans('generals.yes') }}"
                        let val_extra_price = parseInt(data.data.extra_price)
                        let val_is_supplied = parseInt(data.data.is_supplied)
                        let val_min = parseInt(data.data.min)
                        let val_max = parseInt(data.data.max)
                        if (val_extra_price) {
                            row.find('input[name="qty"]').attr('min', '0')
                            row.find('input[name="qty"]').val('0')
                            row.find('td[name="qty_max_supplied"]').text('N/A')
                        } else if (val_is_supplied) {
                            row.find('input[name="qty"]').attr('min', val_min > 0 ? val_min : 0)
                            row.find('input[name="qty"]').val(val_max > 0 ? val_max : 0)
                            row.find('td[name="qty_max_supplied"]').text(val_max)
                        }
                        if (is_variant && !val_extra_price && val_is_supplied) {
                            extra_price = "{{ trans('generals.yes') }}"
                        } else if (!is_variant && !val_is_supplied) {
                            extra_price = "{{ trans('generals.no') }}"
                            row.find('input[name="qty"]').attr('min', val_min > 0 ? val_min : 0)
                            row.find('input[name="qty"]').val(val_max > 0 ? val_max : 0)
                            row.find('td[name="qty_max_supplied"]').text(val_max)
                        }
                        console.log(val_is_supplied)
                        row.find('td[name="is_supplied"]').text(extra_price)
                        row.find('td[name="price"] span').text(data.data.price)
                        row.find('td[name="size"]').text(data.data.size)
                        let path = "{{ asset('img/furnishings') }}" + '/' + data.data.file_path
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
            if (extra_price) {
                subtotal = parseFloat(price * qty)
            } else {
                if (is_supplied) {
                    if (qty > max) {
                        let diff = parseInt(qty - max)
                        subtotal = parseFloat(price * diff)
                    }
                } else {
                    subtotal = parseFloat(price * qty)
                }
            }
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

        const getTotalWithTax = () => {
            let total = 0
            const iva = 0.22
            $.each($('[data-total-partial]'), function(index, element) {
                let value = parseInt($(element).text())
                total += value
            });
            return total + (total * iva)
        }

        const initSubTotal = (row) => {
            if (row !== undefined) {
                assignSubTotal(row)
            } else {
                $.each($('[data-total-partial]'), function(index, element) {
                    let row = $(element).closest('tr')
                    assignSubTotal(row)
                });
            }

            $('[data-total]').text(getTotalOfPartials())
            $('[data-total-tax]').text(getTotalWithTax())
        }

        const formatData = () => {
            let obj = []
            $.each($('table').find('tbody > tr'), function(i, e) {
                let qty = parseInt($(e).find('[name="qty"]').val())
                if (qty > 0) {
                    let row = {}
                    row.id = $(e).attr('data-id')
                    row.qty = qty
                    row.is_supplied = $(e).find('[name="is_supplied"]').attr('data-value')
                    row.price = $(e).find('[data-total-partial]').text()
                    obj.push(row)
                }
            })
            return obj
        }

        $(document).ready(function() {
            $('#modalPayment').on('show.bs.modal', function() {
                initStripe()
            })

            initSubTotal()

            $('select[name="variant"]').on('change', function() {
                let $this = $(this)
                let row = $this.closest('tr')
                let id = $this.find('option:selected').data('opt-id')
                let is_variant = $this.find('option:selected').data('is-variant')
                updateData(row, id, is_variant)
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
                order: [
                    [1, 'desc']
                ],
                "oLanguage": {
                    "sSearch": "{{ trans('generals.search') }}",
                    "oPaginate": {
                        "sFirst": "{{ trans('generals.start') }}", // This is the link to the first page
                        "sPrevious": "«", // This is the link to the previous page
                        "sNext": "»", // This is the link to the next page
                        "sLast": "{{ trans('generals.end') }}" // This is the link to the last page
                    }
                }
            });
        });
    </script>
@endsection
