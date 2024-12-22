@extends('layout/main')
@section('content')
    <div class="container-fluid">
        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            <img src="{{ asset('uploads/' . Auth::user()->foto_user) }}" alt="Profile" width="180px"
                                height="180px" onerror="this.onerror=null; this.src='{{ asset('../uploads/user-1.jpg') }}';"
                                class="rounded-circle mb-4">
                            <h3 class="text-center">{{ Auth::user()->nama }}</h3>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-edit">
                                        Edit Profile
                                    </button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">
                                        Ganti Password
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-edit pt-3" id="profile-edit">
                                    <form action="{{ route('profil.update', ['id' => $user->id]) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row mb-3">
                                            <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">
                                                Foto Profil
                                            </label>
                                            <div class="col-md-8 col-lg-9">
                                                <img src="{{ asset('uploads/' . Auth::user()->foto_user) }}" alt="Profile"
                                                    width="180px" height="180px"
                                                    onerror="this.onerror=null; this.src='{{ asset('../uploads/user-1.jpg') }}';">
                                                <div class="pt-2">
                                                    <input type="file" name="foto_user"
                                                        class="form-control @error('foto_user') is-invalid @enderror">
                                                    <span><i>*ukuran foto maksimal 5mb</i></span>
                                                    @error('foto_user')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="nama" class="col-md-4 col-lg-3 col-form-label">Nama</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="nama" type="text" class="form-control" id="nama"
                                                    value="{{ Auth::user()->dosen->nama }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="nip" class="col-md-4 col-lg-3 col-form-label">NIP</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="nip" class="form-control" id="nip"
                                                    value="{{ Auth::user()->dosen->nip }}" readonly disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="alamat" class="col-md-4 col-lg-3 col-form-label">Alamat</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="alamat" type="text" class="form-control" id="alamat"
                                                    value="{{ Auth::user()->dosen->alamat }}">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="NoHp" class="col-md-4 col-lg-3 col-form-label">No. Hp</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="no_hp" type="text" class="form-control" id="no_hp"
                                                    value="{{ Auth::user()->dosen->no_hp }}">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label for="NoHp" class="col-md-4 col-lg-3 col-form-label"></label>
                                            <div class="col-md-8 col-lg-9">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </div>
                                    </form><!-- End Profile Edit Form -->
                                </div>

                                <div class="tab-pane fade pt-3" id="profile-change-password">
                                    <form action="{{ route('profil.ubahPassword', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="row mb-3">
                                            <label for="pass_sekarang" class="col-md-4 col-lg-3 col-form-label">Password
                                                Sekarang</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="password" type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="pass_sekarang">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="pass_baru" class="col-md-4 col-lg-3 col-form-label">Password
                                                Baru</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="pass_baru" type="password"
                                                    class="form-control @error('pass_baru') is-invalid @enderror"
                                                    id="pass_baru">
                                                @error('pass_baru')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="pass_konf" class="col-md-4 col-lg-3 col-form-label">Konfirmasi
                                                Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="pass_baru_confirmation" type="password"
                                                    class="form-control @error('pass_baru_confirmation') is-invalid @enderror"
                                                    id="pass_konf">
                                                <small id="password-match-message" class="text-danger"></small>
                                                <!-- Area pesan real-time -->
                                                @error('pass_baru_confirmation')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary" id="submit-btn">Ganti
                                                Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div><!-- End Bordered Tabs -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('pass_baru');
            const passwordConfirmation = document.getElementById('pass_konf');
            const message = document.getElementById('password-match-message');
            const submitButton = document.getElementById('submit-btn');

            // Fungsi untuk cek apakah password sesuai
            function checkPasswordMatch() {
                if (password.value === passwordConfirmation.value) {
                    message.textContent = 'Password sudah sesuai';
                    message.classList.remove('text-danger');
                    message.classList.add('text-success');
                    submitButton.disabled = false; // Aktifkan tombol submit
                } else {
                    message.textContent = 'Password tidak sesuai';
                    message.classList.remove('text-success');
                    message.classList.add('text-danger');
                    submitButton.disabled = true; // Nonaktifkan tombol submit
                }
            }

            // Event listener untuk pengecekan real-time saat mengetik
            passwordConfirmation.addEventListener('input', checkPasswordMatch);
            password.addEventListener('input', checkPasswordMatch);
        });
    </script>
@endsection
