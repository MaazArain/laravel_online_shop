@extends('frontend.layouts.master')
@section('content')
<section class="section-1">
    <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="false">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <!-- <img src="images/carousel-1.jpg" class="d-block w-100" alt=""> -->

                <picture>
                    <source media="(max-width: 799px)" srcset="{{asset('frontend-assets/images/carousel-1-m.jpg')}}" />
                    <source media="(min-width: 800px)" srcset="{{asset('frontend-assets/images/carousel-1.jpg')}}" />
                    <img src="images/carousel-1.jpg" alt="" />
                </picture>

                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3">
                        <h1 class="display-4 text-white mb-3">Kids Fashion</h1>
                        <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                        <a class="btn btn-outline-light py-2 px-4 mt-3" href="#">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                
                <picture>
                    <source media="(max-width: 799px)" srcset="{{asset('frontend-assets/images/carousel-2-m.jpg')}}" />
                    <source media="(min-width: 800px)" srcset="{{asset('frontend-assets/images/carousel-2.jpg')}}" />
                    <img src="{{asset('frontend-assets/images/carousel-2.jpg')}}" alt="" />
                </picture>

                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3">
                        <h1 class="display-4 text-white mb-3">Womens Fashion</h1>
                        <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                        <a class="btn btn-outline-light py-2 px-4 mt-3" href="#">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <!-- <img src="images/carousel-3.jpg" class="d-block w-100" alt=""> -->

                <picture>
                    <source media="(max-width: 799px)" srcset="{{asset('frontend-assets/images/carousel-3-m.jpg')}}" />
                    <source media="(min-width: 800px)" srcset="{{asset('frontend-assets/images/carousel-3.jpg')}}" />
                    <img src="{{asset('frontend-assets/images/carousel-2.jpg')}}" alt="" />
                </picture>

                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3">
                        <h1 class="display-4 text-white mb-3">Shop Online at Flat 70% off on Branded Clothes</h1>
                        <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                        <a class="btn btn-outline-light py-2 px-4 mt-3" href="#">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>
<section class="section-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="box shadow-lg">
                    <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">Quality Product</h5>
                </div>                    
            </div>
            <div class="col-lg-3 ">
                <div class="box shadow-lg">
                    <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">Free Shipping</h2>
                </div>                    
            </div>
            <div class="col-lg-3">
                <div class="box shadow-lg">
                    <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">14-Day Return</h2>
                </div>                    
            </div>
            <div class="col-lg-3 ">
                <div class="box shadow-lg">
                    <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">24/7 Support</h5>
                </div>                    
            </div>
        </div>
    </div>
</section>
<section class="section-3">
    <div class="container">
        <div class="section-title">
            <h2>Categories</h2>
        </div>           
        <div class="row pb-3">
           @if(!empty(getCategories()))
                @foreach (getCategories() as $category)
                <div class="col-lg-3">
                    <div class="cat-card">
                        <div class="left">
                            @if(!empty($category->image))
                            <img src="{{asset('uploads/category/thumb/' . $category->image)}}" alt="" class="img-fluid">
                            @endif
                        </div>
                        <div class="right">
                            <div class="cat-data">
                                <h2>{{ $category->name }}</h2>
                                <p>100 Products</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
           @endif
        </div>
    </div>
</section>

<section class="section-4 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Featured Products</h2>
        </div>    
        @if(session('cart_message'))
        <div class="alert alert-success">
            {{ session('cart_message') }}
        </div>
        @endif
        <!-- Displaying any other possible alert message from AJAX -->
        <div id="cartMessage"></div>
        <div class="row pb-3">
           @if(!empty($featured_products))
            @foreach ($featured_products as $product)
            <div class="col-md-3">
                <div class="card product-card">
                    <div class="product-image position-relative">
                     @php
                         $FeaturedProductImage = $product->product_images->first();
                     @endphp

                        @if($FeaturedProductImage && !empty($FeaturedProductImage->image) && file_exists(public_path('uploads/product/large/' . $FeaturedProductImage->image)))
                        <a href="{{route('frontend.product' , $product->slug)}}" class="product-img"><img class="card-img-top" src="{{asset('uploads/product/large/' . $FeaturedProductImage->image)}}" alt=""></a>  
                        @else
                        <img src="{{ asset('frontend-assets/images/offer-1.jpg') }}"
                            class="img-thumbnail">  
                        @endif
                        <a class="whishlist" href="222"><i class="far fa-heart"></i></a>                            

                        <div class="product-action">
                            <a class="btn btn-dark" href="javascript:void(0);"  onclick="addToCart({{$product->id}})">
                                <i class="fa fa-shopping-cart"></i> Add To Cart
                            </a>                            
                        </div>
                    </div>                        
                    <div class="card-body text-center mt-3">
                        <a class="h6 link" href="product.php">{{ $product->title }}</a>
                        <div class="price mt-2">
                            <span class="h5"><strong>${{$product->price}}</strong></span>
                            @if($product->compare_price > 0)
                            <span class="h6 text-underline"><del>${{$product->compare_price}}</del></span>
                            @else
                            <span class="text-danger">Compare Price Empty!</span>
                            @endif
                        </div>
                    </div>                        
                </div>                                               
            </div>  
           
            @endforeach
           @endif             
        </div>
    </div>
</section>

<section class="section-4 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Latest Products</h2>
        </div>    
        @if(session('cart_message'))
            <div class="alert alert-success">
                {{ session('cart_message') }}
            </div>
        @endif

    <!-- Displaying any other possible alert message from AJAX -->
    <div id="cartMessage"></div>
        <div class="row pb-3">
           @if(!empty($latest_products))
               @foreach ($latest_products as $latest_product)
               <div class="col-md-3">
                <div class="card product-card">
                    <div class="product-image position-relative">
                        @php
                        $LatestproductImage = $latest_product->product_images->first();
                     @endphp

                        @if($LatestproductImage && !empty($LatestproductImage->image) && file_exists(public_path('uploads/product/large/'  . $LatestproductImage->image)))
                        <a href="{{route('frontend.product' , $product->slug)}}" class="product-img"><img class="card-img-top" src="{{asset('uploads/product/large/' . $LatestproductImage->image)}}" alt=""></a>
                        @else
                        <img src="{{ asset('frontend-assets/images/offer-1.jpg') }}"
                            class="img-thumbnail">  
                        @endif
                       
                        <a class="whishlist" href="222"><i class="far fa-heart"></i></a>                            

                        <div class="product-action">
                            <a class="btn btn-dark" href="javascript:void(0);"  onclick="addToCart({{$product->id}})">
                                <i class="fa fa-shopping-cart"></i> Add To Cart
                            </a>                            
                        </div>
                    </div>                        
                    <div class="card-body text-center mt-3">
                        <a class="h6 link" href="product.php">{{$latest_product->title}}</a>
                        <div class="price mt-2">
                            <span class="h5"><strong>${{$latest_product->price}}</strong></span>
                            <span class="h6 text-underline"><del>${{$latest_product->compare_price}}</del></span>
                        </div>
                    </div>                        
                </div>                                               
            </div>
               @endforeach
           @endif                
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