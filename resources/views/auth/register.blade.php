@extends('layouts.auth')

@section('login')
<div class="login-box">
    <div class="login-box-body">
        <div class="login-logo">
            <a href="{{ url('/') }}">
                <img src="{{ url($setting->path_logo) }}" alt="logo.png" width="100">
            </a>
        </div>

        <form action="{{ route('register') }}" method="post" class="form-login">
            @csrf
            <div class="form-group has-feedback @error('name') has-error @enderror">
                <input type="text" name="name" class="form-control" placeholder="Full Name" required value="{{ old('name') }}" autofocus>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @error('name')
                    <span class="help-block">{{ $message }}</span>
                @else
                    <span class="help-block with-errors"></span>
                @enderror
            </div>

            <div class="form-group has-feedback @error('email') has-error @enderror">
                <input type="email" name="email" class="form-control" placeholder="Email" required value="{{ old('email') }}">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                @error('email')
                    <span class="help-block">{{ $message }}</span>
                @else
                    <span class="help-block with-errors"></span>
                @enderror
            </div>

            <div class="form-group has-feedback @error('password') has-error @enderror">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @error('password')
                    <span class="help-block">{{ $message }}</span>
                @else
                    <span class="help-block with-errors"></span>
                @enderror
            </div>

            <div class="form-group has-feedback">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            
            <div class="form-group @error('role') has-error @enderror">
                <select name="role" class="form-control" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Cashier</option>
                    <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Manager</option>
                </select>
                @error('role')
                    <span class="help-block">{{ $message }}</span>
                @else
                    <span class="help-block with-errors"></span>
                @enderror
            </div>

            {{-- Google reCAPTCHA --}}
            <div class="form-group @error('g-recaptcha-response') has-error @enderror">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                @error('g-recaptcha-response')
                    <span class="help-block" style="color:#dd4b39;">{{ $message }}</span>
                @enderror
            </div>

            <div class="row">
                <div class="col-xs-8">
                    <a href="{{ route('login') }}">Already have an account? Login</a>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-success btn-block btn-flat">Register</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
