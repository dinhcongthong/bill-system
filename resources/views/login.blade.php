@extends('templates.master')
@section('stylesheets')

<link rel="stylesheet" type="text/css" href="{{ asset('css/home/login.css') }}">
@endsection
@section('login')

<div class="container">
    <div class="d-flex justify-content-center h-100">
        <div class="card">
            <div class="card-header">
                <h3>Sign In</h3>
                <div class="d-flex justify-content-end social_icon">
                    <span><a href="https://www.facebook.com/PosteVN/" style="color:inherit;"><i class="fab fa-facebook-square"></i></a></span>
                    <span><a href="https://line.me/R/ti/p/%40rcz6886a" style="color:inherit;"><i class="fab fa-line"></i></a></span>
                </div>
            </div>
            @if (isset($login_error))
            <div class="alert alert-danger mx-2">
                <i class="fas fa-exclamation-triangle"></i> {{ $login_error }}
            </div>
            @endif
            <div class="card-body">
                <form method="POST" action="{{ route('post_login_route') }}">
                    @csrf
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="email" name="email">
                        
                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" class="form-control" placeholder="password" name="password">
                    </div>
                    {{-- <div class="row align-items-center remember">
                        <input type="checkbox">Remember Me
                    </div> --}}
                    <div class="form-group pt-4 text-center">
                        <input type="submit" value="Login" class="btn float-right login_btn">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center links">
                    Don't have an account?<a href="https://poste-vn.com/account/register">Sign Up</a>
                </div>
                <div class="d-flex justify-content-center">
                    <a href="https://poste-vn.com/account/remind-password">Forgot your password?</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $('#master-grand-wrapper').remove();
    </script>
@endsection