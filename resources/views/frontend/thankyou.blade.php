@extends('frontend.layouts.master')
@section('title' , 'Thanks')
@section('content')
<section class="container py-5">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <h1 class="text-center">Thank You!</h1>
    <p class="text-center">Your Order Id is:-<strong>{{$order_id}}</strong></p>
</section>
@endsection
