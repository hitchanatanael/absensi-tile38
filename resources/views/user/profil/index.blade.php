@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="pagetitle pb-2 headnav">
            <h2>Profil</h2>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Profil</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('profil.update', ['id' => $user->id]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="foto_user" class="col-md-4 col-lg-3 col-form-label">Foto Profil</label>
                                <div class="col-md-8 col-lg-9">
                                    <img src="{{ asset('uploads/' . Auth::user()->foto_user) }}" alt="Profile"
                                        width="180px" height="180px"
                                        onerror="this.onerror=null; this.src='{{ asset('../uploads/user-1.jpg') }}';">
                                    <div class="pt-2">
                                        <input type="file" name="foto_user"
                                            class="form-control @error('foto_user') is-invalid @enderror">
                                        @error('foto_user')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama" class="col-md-4 col-lg-3 col-form-label">Nama Lengkap</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="nama" type="text"
                                        class="form-control @error('nama') is-invalid @enderror" id="nama"
                                        value="{{ old('nama', $user->nama) }}">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" id="email"
                                        value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" id="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8 col-lg-9 offset-md-4 offset-lg-3">
                                    <button type="submit" class="btn btn-primary">Ubah Profil</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
