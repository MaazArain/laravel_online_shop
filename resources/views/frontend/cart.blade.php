@extends('frontend.layouts.master')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('frontend.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">Cart</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-9 pt-4">
        <div class="container">
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                @if (Cart::count() > 0)
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table" id="cart">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>

                                    @foreach ($cartContent as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <!-- Fix image path -->
                                                    <img src="{{ asset('uploads/product/small/' . $item->options->productImage->image) }}"
                                                        width="50" height="50" alt="{{ $item->name }}">
                                                    <h2>{{ $item->name }}</h2>
                                                </div>
                                            </td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>
                                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub"
                                                            data-id="{{ $item->rowId }}">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text"
                                                        class="form-control form-control-sm border-0 text-center"
                                                        value="{{ $item->qty }}">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add"
                                                            data-id="{{ $item->rowId }}">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                ${{ number_format($item->price * $item->qty) }}
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger remove-item"
                                                   onclick="deleteItem('{{$item->rowId}}')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach

                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card cart-summery">

                        <div class="card-body">
                              <div class="sub-title">
                            <h2 class="bg-white">Cart Summery</h3>
                            </div>
                            <div class="d-flex justify-content-between pb-2">
                                <div>Subtotal</div>
                                <div>${{ Cart::subTotal() }}</div>
                            </div>

                            <div class="d-flex justify-content-between summery-end">
                                <div>Total</div>
                                <div>${{ Cart::subTotal() }}</div>
                            </div>
                            <div class="pt-5">
                                <a href="{{route('frontend.checkout')}}" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="input-group apply-coupan mt-4">
                    <input type="text" placeholder="Coupon Code" class="form-control">
                    <button class="btn btn-dark" type="button" id="button-addon2">Apply Coupon</button>
                </div>  --}}
                </div>
                @else
                    <div class="card">
                        <div class="card-body">
                            <h2>Your Cart Is Empty!</h2>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
@section('scriptJs')
    <script>
        $('.add').click(function() {
            var qtyElement = $(this).parent().prev(); // Qty Input
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue < 10) {
                var rowId = $(this).data('id');
                qtyElement.val(qtyValue + 1);
                var newQty = qtyElement.val();
                updateCart(rowId, newQty)
            }
        });
        $('.sub').click(function() {
            var qtyElement = $(this).parent().next();
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue > 1) {
                qtyElement.val(qtyValue - 1);

                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty)
            }
        });

        function updateCart(rowId, qty) {
            $.ajax({
                type: "POST",
                url: "{{ route('frontend.updateCart') }}",
                data: {
                    rowId: rowId,
                    qty: qty
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = "{{ route('frontend.cart') }}";
                    } else {
                        console.log('Invalid Error!');
                    }
                }
            });
        }


        function deleteItem(rowId) {
           if(confirm('Are You Sure You want to delete this item from Cart.'))
           {
            $.ajax({
                type: "POST",
                url: "{{ route('frontend.deleteItem.cart') }}",
                data: {
                    rowId: rowId,
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = "{{ route('frontend.cart') }}";
                    } else {
                        console.log('Invalid Error!');
                    }
                }
            });
           }
        }
    </script>
@endsection
