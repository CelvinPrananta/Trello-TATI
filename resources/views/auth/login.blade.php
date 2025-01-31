@extends('layouts.app')
@section('content')
    <div class="main-wrapper">
        <div class="account-content">
            <div class="container">         
                {{-- message --}}
                {!! Toastr::message() !!}
                <!-- /Account Logo -->
                <div class="account-box">
                    <div class="account-wrapper">
                        <h3 class="account-title">Loghub</h3>
                        <h3 class="account-title2">Aplikasi Manajemen Tugas</h3><br>
                        <!-- Account Form -->
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label>Username / ID Employee / E-mail</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('username_employee_id_atau_email') is-invalid @enderror" name="username_employee_id_atau_email" value="{{ old('username_employee_id_atau_email') }}" placeholder="Masukkan Username, ID Employee atau Email">
                                    <div class="input-group-append">
                                        <button type="button" class="form-control-text" disabled>
                                            <i class="fa solid fa-address-card"></i>
                                        </button>
                                    </div>
                                    @error('username_employee_id_atau_email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Kata Sandi</label>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="passwordInput1" name="password" placeholder="Masukkan Kata Sandi">
                                    <div class="input-group-append">
                                        <button type="button" id="tampilkanPassword1" class="btn btn-outline-secondary">
                                            <i id="icon1" class="fa fa-eye-slash"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label></label>
                                    </div>
                                    <div class="col-auto">
                                        <a class="text-muted" href="{{ route('lupa-kata-sandi') }}">Lupa Kata Sandi?
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary account-btn" style="border-radius: 20px" type="submit">Masuk</button>
                            </div>
                            <div class="account-footer">
                                <p>Belum memiliki akun? <a href="{{ route('daftar') }}">Daftar</a></p><br>
                                <a style="color: #8e8e8e;"><strong>Copyright &copy;2023 - <script>document.write(new Date().getFullYear())</script> PT. Tatacipta Teknologi Indonesia</strong></a><br>
                                <p style="color: #8e8e8e;">All rights reserved.</p>
                            </div>
                        </form>
                        <!-- /Account Form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('script')
        <script src="{{ asset('assets/js/lihatkatasandi.js') }}"></script>

    @endsection
@endsection