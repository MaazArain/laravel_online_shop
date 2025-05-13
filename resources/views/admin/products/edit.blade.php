@extends('admin.layouts.app')
@section('title', 'Edit Product')
@section('customCSS')
<style>
    .btn-primary {
        margin-bottom: 20px;
    }
    .btn-danger{
        margin-bottom: 18px;
    }
</style>
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.products')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <form action="" method="POST" id="EditProductForm" name="EditProductForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control"
                                                placeholder="Title" value="{{$product->title}}">
                                            <div id="title_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input type="text" name="slug" id="slug" readonly class="form-control"
                                                placeholder="Slug" value="{{$product->slug}}">
                                            <div id="slug_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote"
                                                placeholder="Description">{{$product->description }}</textarea>
                                            <div id="description_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="short_description">Short Description</label>
                                            <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote"
                                                placeholder="Short Description">{{$product->short_description }}</textarea>
                                            <div id="short_description_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="shipping_returns">Shipping Returns</label>
                                            <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote"
                                                placeholder="shipping_returns">{{$product->shipping_returns }}</textarea>
                                            <div id="shipping_returns_error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                            </div>
                            <div id="image_error"></div>
                        </div>
                        <div class="row" id="product-gallery">
                           @if(!empty($productImages))
                                @foreach ($productImages as $image)
                                <div class="col-md-3  ml-4" id="image-row-{{$image->id}}"><div class="card ml-4" style="width: 12rem;">
                                    <input type="hidden" name="image_array[]" value="{{$image->id}}">
                                    <img src="{{asset('uploads/product/small/' . $image->image)}}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <a href="javascript:void(0)"  onclick="deleteImage({{$image->id}})"  class="btn btn-danger">Delete</a>
                                    </div>
                                    </div></div>                                    
                                @endforeach         
                           @endif     
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" class="form-control"
                                                placeholder="Price" value="{{$product->price}}">
                                            <div id="price_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price" class="form-control"
                                                placeholder="Compare Price" value="{{$product->compare_price}}">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at
                                                price. Enter a lower value into Price.
                                            </p>
                                            <div id="compare_price-error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" class="form-control"
                                                placeholder="sku" value="{{$product->sku}}">
                                            <div id="sku_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control"
                                                placeholder="Barcode" value="{{$product->barcode}}">
                                            <div id="barcode_error"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value="No">
                                                <input class="custom-control-input" type="checkbox" id="track_qty"
                                                    name="track_qty" value="Yes" {{($product->track_qty == 'Yes') ? 'checked' : ''}}>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                <div id="track_qty-error"></div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty"
                                                class="form-control" placeholder="Qty" value="{{$product->qty}}">
                                             <div id="qty_error"></div>   
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Related Products</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="related_products[]" id="related_products" class="form-control related-product" multiple>
                                               @if(!empty($relatedProducts))
                                               @foreach($relatedProducts as $related)
                                                    <option value="{{ $related->id }}" selected>{{ $related->title }}</option>
                                                @endforeach
                                                  @else
                                                  <span class="text-danger">No Related Product Here</span> 
                                               @endif
                                            </select>
                                            
                                            <div id="related_products-error"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{$product->status == '1' ? 'selected' : ''  }}>Active</option>
                                        <option value="0" {{$product->status == '0' ? 'selected' : ''  }}>Block</option>
                                    </select>
                                    <div id="status_error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        @if (!empty($categories))
                                            @foreach ($categories as $category)
                                                <option {{$product->category_id == $category->id ? 'selected' : ''}}   value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @else
                                            <span class="text-danger">Not Category Record!!</span>
                                        @endif
                                    </select>
                                    <div id="category_error"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="category">Sub category</label>
                                    <select name="sub_category_id" id="sub_category" class="form-control">
                                        @foreach ($subCategories as $subCategory)
                                            <option value="{{ $subCategory->id }}" {{ isset($product) && $product->sub_category_id == $subCategory->id ? 'selected' : '' }}>
                                                {{ $subCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h2 class="h4 mb-3">Product brand</h2>
                                    <div class="mb-3">
                                        <select name="brand_id" id="brand_id" class="form-control">
                                            @if (!empty($brands))
                                                @foreach ($brands as $brand)
                                                    <option {{$product->brand_id == $brand->id ? 'selected' : ''}}   value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @endforeach
                                            @else
                                                <span class="text-danger">No Brands Record</span>
                                            @endif
                                        </select>
                                        <div id="brand_id-error"></div>
                                    </div>
                                </div>
                            </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option value="No" {{$product->is_featured == 'No' ? 'selected' : ''}}>No</option>
                                        <option value="Yes" {{$product->is_featured == 'Yes' ? 'selected' : ''}}>Yes</option>
                                    </select>
                                    <div id="is_featured-error"></div>
                                </div>
                            </div>
                        </div>
                      
                    </div>
                </div>
                <div class="" style="">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{route('admin.products')}}" class="btn btn-danger ml-1">Cancel</a>
                </div>
            </div>
        </form>
    </section>
@endsection
@section('customJs')
<script>
  $('.related-product').select2({
     ajax: {
         url: "{{route('admin.getProducts')}}",
         dataType: 'json',
         tags: true,
         multiple: true,
         minimumInputLength: 3,
         processResults: function (data) {
             return {
                 results: data.tags
             };
         }
     }
 }); 

</script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                height: 300
            });
            $('#title').change(function() {
                element = $(this);
                $.ajax({
                    type: "GET",
                    url: "{{ route('admin.getSlug') }}",
                    data: {
                        title: element.val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response["status"] == true) {
                            $('#slug').val(response["slug"]);
                        }
                    }
                });
            });

            $('#category').change(function() {
            var category_id = $(this).val();
            $.ajax({
                url: "{{ route('admin.product_subCategories.index') }}",
                type: "GET",
                data: {
                    category_id: category_id
                },
                dataType: "json",
                success: function(response) {
                    console.log("Response:", response);
                    if (response.status) {
                        var options = '<option value="">Select Subcategory</option>';
                        $.each(response.subCategories, function(index, subCategory) {
                            options += '<option    value="' + subCategory.id + '">' + subCategory
                                .name + '</option>';
                        });
                        $('#sub_category').html(options);
                    } else {
                        console.log(response.message);
                        $('#sub_category').html('<option value="">No Subcategories Available</option>');
                    }
                },
                error: function(xhr) {
                    console.log("AJAX Error:", xhr.responseText);
                }
            });
        });

        });
    </script>
  <script>
        $('#EditProductForm').submit(function(e){
            e.preventDefault();
            var element = $(this);
            $.ajax({
                type: "POST",
                url: '{{ route("admin.update.products", $product->id) }}',
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                    window.location.href = response.redirect;
                    }
                    else
                    {
                        var errors = response['errors'];
                    if (errors['title']) {
                        $('#title').addClass('is-invalid');
                        $('#title_error').addClass('invalid-feedback').html(errors['title'][0]);
                    } else {
                        $('#title').removeClass('is-invalid');
                        $('#title_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['slug']) {
                        $('#slug').addClass('is-invalid');
                        $('#slug_error').addClass('invalid-feedback').html(errors['slug'][0]);
                    } else {
                        $('#slug').removeClass('is-invalid');
                        $('#slug_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['price']) {
                        $('#price').addClass('is-invalid');
                        $('#price_error').addClass('invalid-feedback').html(errors['price'][0]);
                    } else {
                        $('#price').removeClass('is-invalid');
                        $('#price_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['sku']) {
                        $('#sku').addClass('is-invalid');
                        $('#sku_error').addClass('invalid-feedback').html(errors['sku'][0]);
                    } else {
                        $('#sku').removeClass('is-invalid');
                        $('#sku_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['track_qty']) {
                        $('#track_qty').addClass('is-invalid');
                        $('#track_qty-error').addClass('invalid-feedback').html(errors['track_qty'][0]);
                    } else {
                        $('#track_qty').removeClass('is-invalid');
                        $('#track_qty-error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['category']) {
                        $('#category').addClass('is-invalid');
                        $('#category_error').addClass('invalid-feedback').html(errors['category'][0]);
                    } else {
                        $('#category').removeClass('is-invalid');
                        $('#category_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['status']) {
                        $('#status').addClass('is-invalid');
                        $('#status_error').addClass('invalid-feedback').html(errors['status'][0]);
                    } else {
                        $('#status').removeClass('is-invalid');
                        $('#status_error').removeClass('invalid-feedback').html("");
                    }

                    }
                },
                error: function(jqXHR, exception) {
                    alert('something went Wrong');
                }
            });
        });


        Dropzone.autoDiscover = false;    
const dropzone = $("#image").dropzone({ 
    url:  "{{ route('product-images.update') }}",
    maxFiles: 10,
    paramName: 'image',
    params:{'product_id' : '{{ $product->id }}'},
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg,image/png,image/gif",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }, success: function(file, response){
        var html = `<div class="col-md-3  ml-4" id="image-row-${response.image_id}"><div class="card ml-4" style="width: 15rem;">
            <input type="hidden" name="image_array[]" value="${response.image_id}">
            <img src="${response.ImagePath}" class="card-img-top" alt="...">
            <div class="card-body">
                <a href="javascript:void(0)"  onclick="deleteImage(${response.image_id})"  class="btn btn-danger">Delete</a>
            </div>
            </div></div>`;
            $('#product-gallery').append(html);
    }
});

    function deleteImage(id)
    {
        $("#image-row-" + id).remove();
       if(confirm("Are You Sure You Want to Delete Image?"))
       {
        $.ajax({
            type: "delete",
            url: '{{ route("product-images.destroy") }}',
            data: {id:id},
            dataType: "dataType",
            success: function (response) {
                if(response.status == true)
                {
                    alert(response.message);   
                }
                else{
                    alert(response.message);
                }
            }
        });
       }
    }
      
  </script>
@endsection
