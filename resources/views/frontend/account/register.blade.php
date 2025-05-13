@extends('frontend.layouts.master')
@section('title' , 'Register User')
@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item">Register</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="login-form">    
            <form   method="post" name="registerForm" id="registerForm">
                @csrf
                <h4 class="modal-title">Register Now</h4>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" id="email" name="email">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation">
                    <p></p>
                </div>
                <div class="form-group small">
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div> 
                <button type="submit" class="btn btn-dark btn-block btn-lg">Register</button>
            </form>			
            <div class="text-center small">Already have an account? <a href="{{route('frontend.account.login')}}">Login Now</a></div>
        </div>
    </div>
</section>
@endsection
@section('scriptJs')
<script>
    $('#registerForm').submit(function (e) {
    e.preventDefault();
        $("button[type='submit']").prop('disabled' , true);
    $.ajax({
        type: 'POST',
        url: "{{ route('frontend.account.register_post') }}",
        data: $(this).serializeArray(),
        dataType: 'json',
        success: function (response) {
        $("button[type='submit']").prop('disabled' , false);
            const errors = response.errors ?? {};
            if (response.status) {
                // Redirect to login with flash message
                window.location.href = response.redirect;
                return;
            }
            if (errors.name) {
                $('#name').addClass('is-invalid')
                          .parent().find('p')
                          .addClass('invalid-feedback')
                          .html(errors.name[0]);
            } else {
                $('#name').removeClass('is-invalid')
                          .parent().find('p')
                          .removeClass('invalid-feedback')
                          .html('');
            }

            if(errors.phone)
            {
                $('#phone').addClass('is-invalid')
                .parent().find('p').addClass('invalid-feedback')
                .html(errors.phone[0]);
            }
            else{
                $('#phone').removeClass('is-invalid')
                .parent().find('p')
                .removeClass('invalid-feedback').html('');
            }
            if (errors.email) {
                $('#email').addClass('is-invalid')
                           .parent().find('p')
                           .addClass('invalid-feedback')
                           .html(errors.email[0]);
            } else {
                $('#email').removeClass('is-invalid')
                           .parent().find('p')
                           .removeClass('invalid-feedback')
                           .html('');
            }
            if (errors.password) {
                $('#password').addClass('is-invalid')
                              .parent().find('p')
                              .addClass('invalid-feedback')
                              .html(errors.password[0]);
            } else {
                $('#password').removeClass('is-invalid')
                              .parent().find('p')
                              .removeClass('invalid-feedback')
                              .html('');
            }
        },
        error() {
            alert('Something went wrong');
        }
    });
});
</script>
@endsection