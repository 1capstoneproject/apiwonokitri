@extends('layouts.auth')


@section('main')
    <a href="/" class="text-nowrap logo-img text-center d-block py-3 w-100">
        <img src="{{ asset("/logo.png") }}" width="110" alt="">
    </a>
    <p class="text-center">Nengndi</p>
    <form method="POST" enctype="multipart/form-data">
        @csrf
        @method("POST")
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email')}}" id="email" aria-describedby="email">
            @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror" id="password">
            @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <!--
        <div class="d-flex align-items-center justify-content-end mb-4">
            <a class="text-primary fw-bold" href="/auth/forget_password">Forget Password</a>
        </div>
        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
        <div class="d-flex align-items-center justify-content-center">
            <p class="fs-4 mb-0 fw-bold">Baru di Nengndi</p>
            <a class="text-primary fw-bold ms-2" href="/auth/register">Buat Akun</a>
        </div>
        -->
    </form>
@endsection
