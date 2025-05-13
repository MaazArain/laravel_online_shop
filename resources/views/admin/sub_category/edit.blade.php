@extends('admin.layouts.app')
@section('title', 'Edit SubCategories')
<meta name="csrf-token" content="{{ csrf_token() }}"> 

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update SubCategory</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.sub_categories')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="{{route('admin.update.sub_categories' , $subCategories->id)}}" method="POST" name="EditsubCategoryForm" id="EditsubCategoryForm">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        @if($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                 <option value="{{ $category->id }}" {{$subCategories->category_id == $category->id ? 'selected' : ''}}>{{ $category->name }}</option>   
                                            @endforeach
                                        @endif
                                    </select>
                                   <div id="category_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $subCategories->name }}">	
                                    <div id="name_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Slug</label>
                                    <input type="text" name="slug" id="slug"   class="form-control" placeholder="Slug" value="{{$subCategories->slug}}">	
                                   <div id="slug_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1" {{$subCategories->status == 1 ? 'selected' : ''}}>Active</option>
                                        <option value="0" {{$subCategories->status == 0 ? 'selected' : ''}}>Block</option>
                                    </select>
                                    <div id="status_error"></div>
                                </div>
                            </div>                                  
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="showHome">showHome</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option value="Yes" {{ old('showHome', $subCategories->showHome) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ old('showHome', $subCategories->showHome) == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                    <div id="showHome_error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 mb-2">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{route('admin.sub_categories')}}" class="btn btn-danger ml-1">Cancel</a>
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
        $('#EditsubCategoryForm').submit(function(e){
            e.preventDefault();
            var element = $(this);
            
            $.ajax({
                type: "POST",
                url: '{{ route("admin.update.sub_categories", $subCategories->id) }}',
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        window.location.href = response.redirect;
                    }
                    else
                    {
                        if(response['notFound'] == true)
                        {
                            window.location.href = "{{ route('admin.sub_categories')   }}";
                            return false;   
                        }

                        var errors = response['errors'];
                    if (errors['name']) {
                        $('#name').addClass('is-invalid');
                        $('#name_error').addClass('invalid-feedback').html(errors['name'][0]);
                    } else {
                        $('#name').removeClass('is-invalid');
                        $('#name_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['category']) {
                        $('#category').addClass('is-invalid');
                        $('#category_error').addClass('invalid-feedback').html(errors['category'][0]);
                    } else {
                        $('#category').removeClass('is-invalid');
                        $('#category_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['slug']) {
                        $('#slug').addClass('is-invalid');
                        $('#slug_error').addClass('invalid-feedback').html(errors['slug'][0]);
                    } else {
                        $('#slug').removeClass('is-invalid');
                        $('#slug_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['status']) {
                        $('#status').addClass('is-invalid');
                        $('#status_error').addClass('invalid-feedback').html(errors['status'][0]);
                    } else {
                        $('#status').removeClass('is-invalid');
                        $('#status_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['showHome']) {
                        $('#showHome').addClass('is-invalid');
                        $('#showHome_error').addClass('invalid-feedback').html(errors['showHome'][0]);
                    } else {
                        $('#showHome').removeClass('is-invalid');
                        $('#showHome_error').removeClass('invalid-feedback').html("");
                    }
                    }
                },
                error: function(jqXHR, exception) {
                    alert('something went Wrong');
                }
            });
        });

        $('#name').change(function(){
            element = $(this);
            $.ajax({
                type: "GET",
                url: "{{route('admin.getSlug')}}",
                data: {title: element.val()},
                dataType: 'json',
                success: function (response) {
                    if(response["status"] == true)
                    {
                        $('#slug').val(response["slug"]);   
                    }
                }
            });            
        });
    </script>
@endsection
