@extends('layouts.app')
@section('title', trans('generals.event_subscription', ['event' => $event->title]))
@section('title_header', trans('generals.event_subscription', ['event' => $event->title]))
@section('buttons')
    <a href="{{ url('admin/dashboard') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom"
        title="{{ trans('generals.back') }}"><i class="fas fa-chevron-left"></i></a>
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
                                <button type="button" class="step-trigger" role="tab" aria-controls="stand-part"
                                    id="stand-part-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">{{ trans('generals.exhibitor_solution') }}</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#checkout-part">
                                <button type="button" class="step-trigger" role="tab" aria-controls="checkout-part"
                                    id="checkout-part-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">{{ trans('generals.checkout') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="bs-stepper-content pb-0">
                            <div id="stand-part" class="content col-xs-12 col-sm-12 col-md-6 offset-md-3" role="tabpanel"
                                aria-labelledby="stand-part-trigger">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>{{ trans('entities.stand_type') }}</strong>
                                            <select name="stand_type" class="form-control">
                                                <option value="" selected disabled>{{ trans('forms.select_choice') }}
                                                </option>
                                            </select>
                                            <small class="form-text text-muted" id="stand_description"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <strong>{{ trans('forms.exhibitor_form.n_modules') }}</strong>
                                            <select name="n_modules" class="form-control">
                                                <option value="" selected disabled>{{ trans('forms.select_choice') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <button class="btn btn-primary"
                                                onclick="stepper.next()">{{ trans('generals.next') }} »</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="checkout-part" class="content col-xs-12 col-sm-12 col-md-6 offset-md-3" role="tabpanel"
                                aria-labelledby="checkout-part-trigger">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        {{-- <form id="stripePayment" class="d-flex flex-column py-5"
                                            action="{{ route('stripe-payment') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="stand_selected">
                                            <input type="hidden" name="modules_selected">
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                            <input type="hidden" name="type_of_payment" value="subscription">
                                            <input type="hidden" id="paymentMethodId" name="paymentMethodId">
                                            <div class="form-group">
                                                <input id="card-holder-name" type="text" class="form-control" required>
                                            </div>
                                            <div class="form-group">
                                                <div id="card-element" class="form-control"></div>
                                            </div>
                                            <button id="card-button" class="btn btn-lg btn-block btn-success"><i
                                                    class="fab fa-cc-stripe"></i>
                                                {{ trans('generals.stripe_payment_btn') }}</button>
                                        </form> --}}
                                        <form class="d-flex py-5 justify-content-center align-items-center"
                                            action="{{ route('payment') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="stand_selected">
                                            <input type="hidden" name="modules_selected">
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                            <input type="hidden" name="type_of_payment" value="subscription">
                                            <button type="submit" class="btn btn-lg btn-block btn-success"><i
                                                    class="fab fa-paypal"></i>
                                                {{ trans('generals.paypal_payment_btn') }}</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <button class="btn btn-primary" onclick="stepper.to(1)">«
                                                {{ trans('generals.back') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <h3 class="mb-2">{{ trans('generals.details_price_checkout') }}</h3>
                        <p class="m-0" id="price">{{ trans('generals.stand_price_checkout') }} <span></span>
                            €</p>
                        <p class="m-0" id="size">{{ trans('generals.stand_size_checkout') }} <span></span>
                        </p>
                        <p class="m-0" id="total">{{ trans('generals.subtotal_price_checkout') }}
                            <span></span> €
                        </p>
                        <p class="m-0" id="tax">{{ trans('generals.tax') }}
                            (<span>{{ $iva }}</span>%): <span id="tot-tax"></span> €</p>
                        <p class="m-0" id="total-tax">{{ trans('generals.total_tax') }} <span></span> €</p>
                    </div>
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
                        $('#paymentMethodId').val(paymentMethod.id)
                        $('#stripePayment').trigger('submit')
                    })
                }
            });
        }

        const getStands = () => {
            let event_id = {{ $event->id }}
            let base_url = '/admin/events/' + event_id + '/stands/getSelectList'
            common_request.post(base_url)
                .then(response => {
                    let data = response.data
                    if (data.status) {
                        $.each(data.data, function(index, value) {
                            let option = document.createElement('option')
                            option.value = value.stand_type_id
                            option.text = value.name
                            option.modules = value.max_number_modules
                            option.price = value.price
                            option.size = value.size
                            option.description = value.description
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
                if (index > 0) {
                    $(value).remove()
                }
            })
        }
        const initModules = () => {
            removeOptions()
            let selected = $('select[name="stand_type"]').find(':selected');
            let n_modules = selected[0].modules
            for (let i = 0; i < n_modules; i++) {
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
            $('#total-tax').find('span').text('')
        }
        const checkHasErrorFirstStep = () => {
            console.log($('select[name="stand_type"]').val(), $('select[name="n_modules"]').val())
            if ($('select[name="stand_type"]').val() == '' || $('select[name="stand_type"]').val() === null ||
                $('select[name="n_modules"]').val() == '' || $('select[name="n_modules"]').val() === null) {
                return true
            }
            return false
        }
        const stepperEl = document.querySelector('.bs-stepper')
        const stepper = new Stepper(stepperEl)
        stepperEl.addEventListener('show.bs-stepper', function(event) {
            if (event.detail.indexStep === 1 && checkHasErrorFirstStep()) {
                event.preventDefault()
                toastr.error("{{ trans('messages.first_step_error') }}")
            }
        })
        const assignCalcs = () => {
            const pricePerModule = parseFloat($('#price').find('span').text());
            const numberOfModules = parseInt($('select[name="n_modules"]').val());
            const ivaText = parseInt($('#tax > span').text());
            const iva = ivaText / 100;

            const subTotal = pricePerModule * numberOfModules;

            const totalWithoutTax = subTotal;
            const totalWithTax = subTotal + (subTotal * iva);

            $('#total').find('span').text(totalWithoutTax.toFixed(2));
            $('#total-tax').find('span').text(totalWithTax.toFixed(2));
            $('#tot-tax').text((totalWithTax - totalWithoutTax).toFixed(2));
        };
        $(document).ready(function() {
            //initStripe();
            getStands();
            $('select[name="stand_type"]').on('change', function(e) {
                resetCalcs()
                let selected = $('select[name="stand_type"]').find(':selected');
                let price = selected[0].price
                let size = selected[0].size
                let description = selected[0].description
                $('#stand_description').html(description)
                $('#price').find('span').text(price)
                $('#size').find('span').text(size)
                $('input[name="stand_selected"]').val($('select[name="stand_type"]').val())
                initModules()
                let selected_module = $('select[name="n_modules"]').find(':selected');
                $('input[name="modules_selected"]').val(selected_module[0].value)
                if (selected_module[0].value.length > 0) {
                    assignCalcs()
                }
            })
            $('select[name="n_modules"]').on('change', function(e) {
                $('#total').find('span').text('')
                $('#total-tax').find('span').text('')
                assignCalcs()
                $('input[name="modules_selected"]').val($('select[name="n_modules"]').val())
            })
        });
    </script>
@endsection
