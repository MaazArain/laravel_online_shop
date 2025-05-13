@extends('frontend.layouts.master')
@section('title', 'Checkout')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
       @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <section class="section-9 pt-4">
        <div class="container">
            <form action="{{ route('frontend.processCheckout') }}" name="orderForm" id="orderForm" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Shipping Address</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder="First Name"
                                                value="{{ !empty($customerAddress) ? $customerAddress->first_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="Last Name"
                                                value="{{ !empty($customerAddress) ? $customerAddress->last_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Email"
                                                value="{{ !empty($customerAddress) ? $customerAddress->email : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                           <select name="country" id="country" class="form-control">
                                            <option value="">Please Select a Country</option>
                                            @foreach ($countries as $country)
                                                <option
                                                    value="{{ $country->id }}"
                                                    {{ !empty($customerAddress) && $customerAddress->country_id == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name ?? '' }}
                                                </option>
                                            @endforeach

                                            {{-- Add "Rest of World" option manually --}}
                                            <option
                                                value="{{$country->id}}"
                                                {{ !empty($customerAddress) && $customerAddress->country_id == 'rest_of_world' ? 'selected' : '' }}>
                                                Rest of World
                                            </option>
                                        </select>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">{{ !empty($customerAddress) ? $customerAddress->address : '' }}</textarea>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="apartment" id="apartment" class="form-control"
                                                placeholder="Apartment, suite, unit, etc. (optional)"
                                                value="{{ !empty($customerAddress) ? $customerAddress->apartment : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="City"
                                                value="{{ !empty($customerAddress) ? $customerAddress->city : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="state" id="state" class="form-control"
                                                placeholder="State"
                                                value="{{ !empty($customerAddress) ? $customerAddress->state : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control"
                                                placeholder="Zip"
                                                value="{{ !empty($customerAddress) ? $customerAddress->zip : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                                placeholder="Mobile No."
                                                value="{{ !empty($customerAddress) ? $customerAddress->mobile : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="notes" id="notes" cols="30" rows="2" placeholder="Order Notes (optional)"
                                                class="form-control">{{ !empty($customerAddress) ? $customerAddress->orders->first()->notes : '' }}</textarea>
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Order Summery</h3>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $item)
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                        <div class="h6">${{ $item->price * $item->qty }}</div>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    <div class="h6"><strong>${{ Cart::subtotal() }}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div class="h6"><strong>Shipping</strong></div>
                                    <div class="h6"><strong id="shippingAmount">${{ number_format($totalShippingCharge, 2) }}</strong>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong id="grandTotal">${{ $grandTotal }}</strong></div>
                                </div>
                            </div>
                        </div>
                        <div class="card payment-form">
                            <h3 class="card-title h5 mb-3">Payment Details</h3>
                            <div class="">
                                <input type="radio" checked name="payment_method" value="cash_on_delivery"
                                    id="payment_method_one">
                                <label for="payment_method_one" class="form-check-label">Cash On Delivery</label>
                            </div>
                            <div class="">
                                <input type="radio" name="payment_method" value="stripe" id="payment_method_two">
                                <label for="payment_method_two" class="form-check-label">Stripe</label>
                            </div>
                            <div class="card-body p-0 d-none" id="card-payment-form">
                                <div class="mb-3">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number"
                                        placeholder="Valid Card Number" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">CVV Code</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="123"
                                            class="form-control">
                                    </div>
                                </div>

                            </div>
                            <div class="pt-4">
                                <button type="submit" class="btn-dark w-100">Pay Now</button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection


{{-- this is an Script Code here!! --}}
@section('scriptJs')
    <script>
        $(document).ready(function() {
            $('#payment_method_one').click(function() {
                if ($(this).is(":checked")) {
                    $("#card-payment-form").addClass('d-none');
                }
            });
            $('#payment_method_two').click(function() {
                if ($(this).is(":checked")) {
                    $("#card-payment-form").removeClass('d-none');
                }
            });
        });
    </script>
    <script>
        $('#orderForm').submit(function(e) {
            e.preventDefault();
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: "{{ route('frontend.processCheckout') }}",
                data: $(this).serializeArray(),
                dataType: "json",
                success: function(response) {
                    $("button[type='submit']").prop('disabled', false);
                    const errors = response.errors ?? {};

                    if (response.status) {
                        window.location.href = response.redirect;
                        return;
                    }

                    if (errors.first_name) {
                        $('#first_name').addClass('is-invalid')
                            .parent().find('p')
                            .addClass('invalid-feedback')
                            .html(errors.first_name[0]);
                    } else {
                        $('#first_name').removeClass('is-invalid')
                            .parent().find('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }

                    if (errors.last_name) {
                        $('#last_name').addClass('is-invalid')
                            .parent().find('p')
                            .addClass('invalid-feedback')
                            .html(errors.last_name[0]);
                    } else {
                        $('#last_name').removeClass('is-invalid')
                            .parent().find('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }
                    if (errors.email) {
                        $('#email').addClass('is-invalid')
                            .parent().find('p')
                            .addClass('invalid-feedback')
                            .html(errors.email[0]);
                    } else {
                        $('#email').removeClass('is-invalid')
                            .parent().find('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }
                    if (errors.country) {
                        $('#country').addClass('is-invalid')
                            .parent().find('p')
                            .addClass('invalid-feedback')
                            .html(errors.country[0]);
                    } else {
                        $('#country').removeClass('is-invalid')
                            .parent().find('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }
                    if (errors.address) {
                        $('#address').addClass('is-invalid')
                            .parent().find('p')
                            .addClass('invalid-feedback')
                            .html(errors.address[0]);
                    } else {
                        $('#address').removeClass('is-invalid')
                            .parent().find('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }
                    if (errors.apartment) {
                        $('#apartment').addClass('is-invalid')
                            .parent().find('p')
                            .addClass('invalid-feedback')
                            .html(errors.apartment[0]);
                    } else {
                        $('#apartment').removeClass('is-invalid')
                            .parent().find('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }
                    if (errors.city) {
                        $('#city').addClass('is-invalid')
                            .parent().find('p')
                            .addClass('invalid-feedback')
                            .html(errors.city[0]);
                    } else {
                        $('#city').removeClass('is-invalid')
                            .parent().find('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }
                    if (errors.state) {
                        $('#state').addClass('is-invalid')
                            .parent().find('p')
                            .addClass('invalid-feedback')
                            .html(errors.state[0]);
                    } else {
                        $('#state').removeClass('is-invalid')
                            .parent().find('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }
                    if (errors.zip) {
                        $('#zip').addClass('is-invalid')
                            .parent().find('p')
                            .addClass('invalid-feedback')
                            .html(errors.zip[0]);
                    } else {
                        $('#zip').removeClass('is-invalid')
                            .parent().find('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }
                    if (errors.mobile) {
                        $('#mobile').addClass('is-invalid')
                            .parent().find('p')
                            .addClass('invalid-feedback')
                            .html(errors.mobile[0]);
                    } else {
                        $('#mobile').removeClass('is-invalid')
                            .parent().find('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }
                    if (errors.notes) {
                        $('#notes').addClass('is-invalid')
                            .parent().find('p')
                            .addClass('invalid-feedback')
                            .html(errors.notes[0]);
                    } else {
                        $('#notes').removeClass('is-invalid')
                            .parent().find('p')
                            .removeClass('invalid-feedback')
                            .html('');
                    }
                },
                error() {
                    console.log('Something went Wrong');
                }
            });
        });

        $('#country').change(function(){
            $.ajax({
                type: "POST",
                url: "{{route('frontend.getOrderBySummary')}}",
                data: {country_id: $(this).val()},
                dataType: "json",
                success: function (response) {

                    if(response.status == true)
                    {
                        $('#shippingAmount').html(response.shippingCharge);
                        $('#grandTotal').html(response.grandTotal);


                    }
                }
            });
        });
    </script>
@endsection
