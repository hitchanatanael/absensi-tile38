@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="pagetitle pb-2 headnav">
            <h2>Profil</h2>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Absen Dosen</li>
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
                            <div class="row mb-3">
                                <label for="foto_user" class="col-md-4 col-lg-3 col-form-label">Foto Profil</label>
                                <div class="col-md-8 col-lg-9">
                                    <img src="{{ asset('uploads/' . Auth::user()->foto_user) }}" alt="Profile"
                                        width="180px" height="180px"
                                        onerror="this.onerror=null; this.src='{{ asset('../assets/images/profile/user-1.jpg') }}';">
                                    <div class="pt-2">
                                        <input type="file" name="foto_user" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama" class="col-md-4 col-lg-3 col-form-label">Nama Lengkap</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="nama" type="text" class="form-control" id="nama"
                                        value="{{ old('nama', $user->nama) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="email" type="email" class="form-control" id="email"
                                        value="{{ old('email', $user->email) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="password" type="password" class="form-control" id="password">
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
