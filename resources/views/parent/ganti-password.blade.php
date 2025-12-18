@extends('layouts.Parent')

@section('title', 'Ganti Password')

@section('content')
    <div class="card">
        <div class="card-header">Ganti Password</div>
        <div class="card-body">

            {{-- ALERT --}}
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('Parent.ganti-password.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Password Lama</label>
                    <input type="password" name="password_lama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button class="btn btn-primary">Ubah Password</button>
            </form>

        </div>
    </div>
@endsection
