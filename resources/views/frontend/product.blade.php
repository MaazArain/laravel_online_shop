@extends('frontend.layouts.master')
<style>
    .object-fit-cover {
        object-fit: cover;
    }
</style>
@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{route('frontend.home')}}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{route('frontend.shop')}}">Shop</a></li>
                <li class="breadcrumb-item">{{$product->title}}</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-7 pt-3 mb-3">
  
    <div class="container">
        <div id="cartMessage"></div>
        <div class="row ">
            <div class="col-md-5">
                <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner bg-light">
                        @if($product->product_images)
                            @foreach ($product->product_images as $key => $productImage)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img class="w-100 h-100" src="{{ asset('uploads/product/large/' . $productImage->image) }}" alt="Image">
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-7">
                <div class="bg-light right">
                    <h1>{{$product->title ?? ''}}</h1>
                    <div class="d-flex mb-3">
                        <div class="text-primary mr-2">
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star-half-alt"></small>
                            <small class="far fa-star"></small>
                        </div>
                        <small class="pt-1">(99 Reviews)</small>
                    </div>
                    @if($product->compare_price > 0)
                      <h2 class="price text-secondary"><del>${{$product->compare_price ?? ''}}</del></h2>
                    @endif
                    <h2 class="price ">${{$product->price ?? ''}}</h2>

                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Perferendis officiis dolor aut nihil iste porro ullam repellendus inventore voluptatem nam veritatis exercitationem doloribus voluptates dolorem nobis voluptatum qui, minus facere.</p>
                    <a href="javascript:void(0);"  onclick="addToCart({{$product->id}})" class="btn btn-dark"><i class="fas fa-shopping-cart"></i> &nbsp;ADD TO CART</a>
                </div>
            </div>

            <div class="col-md-12 mt-5">
                <div class="bg-light">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping & Returns</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <p>
                                {!!$product->description ?? ''!!}
                            </p>
                        </div>
                        <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                        <p>{!! $product->shipping_returns ?? '' !!}</p>
                        </div>
                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                            <p>{!! $product->shipping_returns ?? '' !!}</p>
                        </div>
                    </div>
                </div>
            </div> 
        </div>           
    </div>
</section>
{{-- this is related products here Code --}}
<section class="pt-5 section-8">
    <div class="container">
        <div class="section-title">
            <h2>Related Products</h2>
        </div> 
        <!-- Displaying any other possible alert message from AJAX -->
        <div id="cartMessage"></div>
        <div class="col-md-12">
            <div id="related-products" class="row">
                @if(!empty($relatedProducts))
                    @foreach ($relatedProducts as $relatedProduct)  
                        <div class="col-md-3 mb-4">
                            <div class="card product-card border-0 shadow-sm h-100">
                                <div class="product-image position-relative">

                                    <a href="{{ route('frontend.product', $relatedProduct->slug) }}" class="d-block overflow-hidden" style="height: 200px;">
                                        @if($relatedProduct->product_images && count($relatedProduct->product_images))
                                            <img class="w-100 h-100 object-fit-cover" src="{{ asset('uploads/product/large/' . $relatedProduct->product_images[0]->image) }}" alt="Product Image">
                                        @endif
                                    </a>

                                    {{-- Add to Cart Button - Centered Over Image --}}
                                    <div class="product-action">
                                        <a class="btn btn-dark btn-sm" href="javascript:void(0);"  onclick="addToCart({{$product->id}})">
                                            <i class="fa fa-shopping-cart"></i> Add To Cart
                                        </a>
                                    </div>

                                    {{-- Wishlist Icon - Top Right --}}
                                    <a class="position-absolute top-0 end-0 p-2" href="#">
                                        <i class="far fa-heart text-danger"></i>
                                    </a>
                                </div>                        

                                <div class="card-body text-center mt-2">
                                    <a class="h6 d-block mb-1 text-dark text-decoration-none" href="{{ route('frontend.product', $relatedProduct->slug) }}">
                                        {{ $relatedProduct->title ?? '' }}
                                    </a>
                                    <div class="price">
                                        <span class="h5 text-success"><strong>${{ $relatedProduct->price ?? '0.00' }}</strong></span>
                                        @if($relatedProduct->compare_price)
                                            <span class="h6 text-muted text-decoration-line-through ms-2">${{ $relatedProduct->compare_price }}</span>
                                        @endif
                                    </div>
                                </div>                        
                            </div>
                        </div>
                    @endforeach
                @endif 
            </div>
        </div>
    </div>
</section>
@endsection
@section('scriptJs')
<script>
    $(document).ready(function() {
        var cartMessage = localStorage.getItem('cartMessage');        
        if (cartMessage) {
            $('#cartMessage').html('<div class="alert alert-success">' + cartMessage + '</div>');            
            localStorage.removeItem('cartMessage');
        }
    });
</script>
@endsection