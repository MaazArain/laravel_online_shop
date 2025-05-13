@extends('admin.layouts.app')
@section('title', 'Add SubCategories')
<meta name="csrf-token" content="{{ csrf_token() }}"> 

@section('content')
   	<!-- Content Header (Page header) -->
       <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Sub Category</h1>
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
            <form action="" method="POST" name="subCategoryForm" id="subCategoryForm">
                @csrf
                <div class="card">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Please Select the Category</option>
                                        @if($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                 <option value="{{ $category->id }}">{{ $category->name }}</option>   
                                            @endforeach
                                        @endif
                                    </select>
                                   <div id="category_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name">	
                                    <div id="name_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Slug</label>
                                    <input type="text" name="slug" id="slug" readonly  class="form-control" placeholder="Slug">	
                                   <div id="slug_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Please Select Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                    <div id="status_error"></div>
                                </div>
                            </div>                              
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="showHome">showHome</label>
                                    <select name="showHome" id="showHome" class="form-control">
                                        <option value="">Please Select Show Status</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                    <div id="showHome_error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 mb-2">
                            <button type="submit" class="btn btn-primary">Create</button>
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
        $('#subCategoryForm').submit(function(e){
            e.preventDefault();
            var element = $(this);
            $.ajax({
                type: "POST",
                url: '{{ route('admin.store.sub_categories') }}',
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        window.location.href = response.redirect;
                    }
                    else
                    {
                        var errors = response['errors'];
                    if (errors['name']) {
                        $('#name').addClass('is-invalid');
                        $('#name_error').addClass('invalid-feedback').html(errors['name'][0]);
                    } else {
                        $('#name').removeClass('is-invalid');
                        $('#name_error').removeClass('invalid-feedback').html("");
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
                    if (errors['category']) {
                        $('#category').addClass('is-invalid');
                        $('#category_error').addClass('invalid-feedback').html(errors['category'][0]);
                    } else {
                        $('#category').removeClass('is-invalid');
                        $('#category_error').removeClass('invalid-feedback').html("");
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
