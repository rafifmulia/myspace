@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="row mt-5">
    <div class="col-md-5 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="card-title text-center"><h2>Register</h2></div>
                <div id="formAlert" class="alert alert-danger invisible" role="alert"></div>
                <form id="formRegister">
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" autocomplete="email">
                    </div>
                    <div class="form-group">
                        <label for="">Username</label>
                        <input type="username" name="username" class="form-control" autocomplete="username">
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control" autocomplete="current-password">
                    </div>
                    <button id="btnSubmit" type="submit" class="btn btn-primary float-left">Register</button>
                    <a href="{{ route('login') }}" class="text-primary float-right">Login</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$('#btnSubmit').on('click', function (e) {
    e.preventDefault();

    $.ajax({
        url: "/register/auth",
        method: "post",
        headers: {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content") 
        },
        contentType: "application/x-www-form-urlencoded; charset=UTF-8;",
        data: $("#formRegister").serialize(),
        processData: true,
        dataType: "json",
        success: function (data, textStatus, jqxhr) {
            if (textStatus == "success") {
                window.location.href = "/login";
            }
        },
        error: function (jqxhr, textStatus, error) {
            var errors = jqxhr.responseJSON;
            $('#formAlert').html("");

            $('#formAlert').append("<ol>");
            for (msg in errors.errors) {
                $('#formAlert').append("<li>"+msg+": "+errors.errors[msg]+"</li>");
            }
            $('#formAlert').append("</ol>");

            $('#formAlert').removeClass("invisible");
        }
    })
});
</script>
@endpush