@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="row mt-5">
    <div class="col-md-5 mx-auto">
        <div class="card">
            <div class="card-body">
                <div class="card-title text-center"><h2>Login</h2></div>
                <div id="formAlert" class="alert alert-danger invisible" role="alert"></div>
                <form id="formLogin">
                    <div class="form-group">
                        <label for="">Email / Username</label>
                        <input type="email" name="email" class="form-control" autocomplete="email">
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control" autocomplete="password">
                    </div>
                    <button type="submit" id="btnSubmit" class="btn btn-primary float-left">Login</button>
                    <a href="{{ route('register') }}" class="text-primary float-right">Register</a>
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
        url: "/login/auth",
        method: "post",
        headers: {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content") 
        },
        contentType: "application/x-www-form-urlencoded; charset=UTF-8;",
        data: $("#formLogin").serialize(),
        processData: true,
        dataType: "json",
        success: function (data, textStatus, jqxhr) {
            if (textStatus == "success") {
                window.location.href = "/space";
            }
        },
        error: function (jqxhr, textStatus, error) {
            var errors = jqxhr.responseJSON;
            $('#formAlert').html("");

            $('#formAlert').append("<ul><li>"+errors.message+"</li></ul>");

            $('#formAlert').removeClass("invisible");
        }
    })
});
</script>
@endpush