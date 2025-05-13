@extends('admin.layouts.app')
@section('title', 'Add CoupanCode')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Category</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('admin.discount_code')}}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="POST"  id="addDiscountCoupanForm" name="addDiscountCoupanForm">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code">code</label>
                                    <input type="text" name="code" id="code" class="form-control"
                                        placeholder="code">
                                    <div id="code_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="name">
                                        <div id="name_error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                               <div class="mb-3">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" placeholder="Description..." class="form-control"></textarea>
                                        <div id="description_error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="Status">Max Uses</label>
                                <input type="number" name="max_uses" id="max_uses" placeholder="max_uses" class="form-control">
                                <div id="max_uses_error"></div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="max_uses_user">Max Uses User</label>
                                <input type="text" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Max Uses User">
                                <div id="max_uses_user_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="percent">Percent</option>
                                    <option value="fixed">Fixed</option>
                                </select>
                                <div id="type_error"></div>
                            </div>
                        </div>
                          <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="discount_amount">Discount Amount</label>
                                <input type="text" name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount amount">
                                <div id="discount_amount_error"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="min_amount">min_amount</label>
                                <input type="text" name="min_amount" id="min_amount" class="form-control" placeholder="Min Amount">
                                <div id="min_amount_error"></div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="">Usage Count</label>
                                <select name="usage_count" id="usage_count" class="form-control">
                                    <option value="">Select a Usage Account</option>
                                    <option value="1">Active</option>
                                    <option value="0">Block</option>
                                </select>
                                <div id="usage_count_error"></div>
                            </div>
                        </div>
                         <div class="row mt-2">
                            <div class="col-md-6">
                                 <label for="starts_at">Starts_at</label>
                                <input type="text" name="starts_at" id="starts_at" class="form-control" placeholder="Starts At">
                                <div id="starts_at_error"></div>
                            </div>
                            <div class="col-md-6">
                                 <label for="expires_at">Expires_at</label>
                                <input type="text" name="expires_at" id="expires_at" class="form-control" placeholder="Expires At">
                                <div id="expires_at_error"></div>
                            </div>
                        </div>
                        <div class="mt-4 mb-2">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <a href="#" class="btn btn-danger ml-1">Cancel</a>
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
        $('#addDiscountCoupanForm').submit(function(e){
            e.preventDefault();
            var element = $(this);
            $("button[type='submit']").prop('disabled' , true);
            $.ajax({
                type: "POST",
                url: '{{ route('admin.store.discount_code') }}',
                data: element.serializeArray(),
                dataType: "json",
                success: function(response) {
                    if (response.status === true) {
                        window.location.href = response.redirect;
                    }
                    else
                    {
                        var errors = response['errors'];
                          if (errors['code']) {
                        $('#code').addClass('is-invalid');
                        $('#code_error').addClass('invalid-feedback').html(errors['code'][0]);
                    } else {
                        $('#code').removeClass('is-invalid');
                        $('#code_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['name']) {
                        $('#name').addClass('is-invalid');
                        $('#name_error').addClass('invalid-feedback').html(errors['name'][0]);
                    } else {
                        $('#name').removeClass('is-invalid');
                        $('#name_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['description']) {
                        $('#description').addClass('is-invalid');
                        $('#description_error').addClass('invalid-feedback').html(errors['description'][0]);
                    } else {
                        $('#description').removeClass('is-invalid');
                        $('#description_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['max_uses']) {
                        $('#max_uses').addClass('is-invalid');
                        $('#max_uses_error').addClass('invalid-feedback').html(errors['max_uses'][0]);
                    } else {
                        $('#max_uses').removeClass('is-invalid');
                        $('#max_uses_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['max_uses_user']) {
                        $('#max_uses_user').addClass('is-invalid');
                        $('#max_uses_user_error').addClass('invalid-feedback').html(errors['max_uses_user'][0]);
                    } else {
                        $('#max_uses_user').removeClass('is-invalid');
                        $('#max_uses_user_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['type']) {
                        $('#type').addClass('is-invalid');
                        $('#type_error').addClass('invalid-feedback').html(errors['type'][0]);
                    } else {
                        $('#type').removeClass('is-invalid');
                        $('#type_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['discount_amount']) {
                        $('#discount_amount').addClass('is-invalid');
                        $('#discount_amount_error').addClass('invalid-feedback').html(errors['discount_amount'][0]);
                    } else {
                        $('#discount_amount').removeClass('is-invalid');
                        $('#discount_amount_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['min_amount']) {
                        $('#min_amount').addClass('is-invalid');
                        $('#min_amount_error').addClass('invalid-feedback').html(errors['min_amount'][0]);
                    } else {
                        $('#min_amount').removeClass('is-invalid');
                        $('#min_amount_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['usage_count']) {
                        $('#usage_count').addClass('is-invalid');
                        $('#usage_count_error').addClass('invalid-feedback').html(errors['usage_count'][0]);
                    } else {
                        $('#usage_count').removeClass('is-invalid');
                        $('#usage_count_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['starts_at']) {
                        $('#starts_at').addClass('is-invalid');
                        $('#starts_at_error').addClass('invalid-feedback').html(errors['starts_at'][0]);
                    } else {
                        $('#starts_at').removeClass('is-invalid');
                        $('#starts_at_error').removeClass('invalid-feedback').html("");
                    }
                    if (errors['expires_at']) {
                        $('#expires_at').addClass('is-invalid');
                        $('#expires_at_error').addClass('invalid-feedback').html(errors['expires_at'][0]);
                    } else {
                        $('#expires_at').removeClass('is-invalid');
                        $('#expires_at_error').removeClass('invalid-feedback').html("");
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
