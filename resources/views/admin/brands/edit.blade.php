@extends('admin.layouts.app')
@section('title', 'Edit Brand')
<meta name="csrf-token" content="{{ csrf_token() }}"> 

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.brands')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="{{route('admin.update.brands' , $brandsEdit->id)}}" method="POST" id="EditBrandsForm" name="EditBrandsForm">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" value="{{$brandsEdit->name}}">
                                    <div id="name_error"></div>    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        placeholder="Slug" readonly value="{{$brandsEdit->slug}}">
                                        <div id="slug_error"></div>
                                        
                                </div>       
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label for="Status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Please Select Status</option>
                                    <option value="1" {{$brandsEdit->status == 1 ? 'selected' : ''   }}>Active</option>
                                    <option value="0" {{$brandsEdit->status == 0 ? 'selected' : ''   }}>Block</option>
                                </select>
                                <div id="status_error"></div>
                            </div>
                        </div>
                        <div class="mt-4 mb-2">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{route('admin.brands')}}" class="btn btn-danger ml-1">Cancel</a>
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
        $('#EditBrandsForm').submit(function(e){
            e.preventDefault();
            var element = $(this);
            $.ajax({
                type: "POST",
                url: '{{ route("admin.update.brands", $brandsEdit->id) }}',
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
