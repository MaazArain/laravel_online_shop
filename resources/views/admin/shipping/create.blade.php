@extends('admin.layouts.app')
@section('title', 'Add Shipping')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
   	<!-- Content Header (Page header) -->
       <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Shipping</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.shipping')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="POST" name="shippingForm" id="shippingForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name">Country</label>
                                <div class="mb-3">
                                    <select name="country" id="country" class="form-control">
                                        <option value="">Please Select the Country</option>
                                        @if($countries->isNotEmpty())
                                            @foreach ($countries as $country)
                                                 <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                            <option value="rest_of_world">Rest of the World</option>
                                        @endif
                                    </select>
                                   <div id="country_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="name">Amount</label>
                                <div class="mb-3">
                                    <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                                    <div id="amount_error"></div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
@section('customJs')
    <script>
        $('#shippingForm').submit(function(e){
            e.preventDefault();
            var element = $(this);
            $.ajax({
                type: "POST",
                url: '{{ route('admin.store.shipping') }}',
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        window.location.href = response.redirect;
                    }
                    else
                    {
                        var errors = response['errors'];
                    if (errors['country']) {
                        $('#country').addClass('is-invalid');
                        $('#country_error').addClass('invalid-feedback').html(errors['country'][0]);
                    } else {
                        $('#country').removeClass('is-invalid');
                        $('#country_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['amount']) {
                        $('#amount').addClass('is-invalid');
                        $('#amount_error').addClass('invalid-feedback').html(errors['amount'][0]);
                    } else {
                        $('#amount').removeClass('is-invalid');
                        $('#amount_error').removeClass('invalid-feedback').html("");
                    }
                    }
                },
                error: function(jqXHR, exception) {
                    alert('something went Wrong');
                }
            });
        });
    </script>
@endsection
