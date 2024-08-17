@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="pagetitle headnav">
            <h2>Data User</h2>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data User</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="py-3 d-flex flex-row align-items-center justify-content-between">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal"><i
                            class="ti ti-plus me-2"></i>Tambah Data</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center" style="width: 10px">No.</th>
                                    <th class="text-center">Nama Dosen</th>
                                    <th class="text-center">Role</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                                @forelse($users as $user)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}.</td>
                                        <td>{{ $user->nama }}</td>
                                        <td>{{ $user->id_role == 1 ? 'Admin' : 'User' }}</td>
                                        <td>{{ $user->email }}</td>

                                        <td class="text-center">
                                            <div class="d-flex gap-1 d-md-block">
                                                <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editUserModal{{ $user->id }}">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                                <form action="{{ route('akun.user.destroy', $user->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Data -->
                                    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1"
                                        aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">
                                                        Edit Data User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('akun.user.update', $user->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul>
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                        <div class="mb-3">
                                                            <label for="nama{{ $user->id }}"
                                                                class="form-label">Nama</label>
                                                            <select class="form-control" id="nama{{ $user->id }}"
                                                                name="nama" required>
                                                                @foreach ($dosens as $dosen)
                                                                    <option value="{{ $dosen->nama }}"
                                                                        {{ $user->nama == $dosen->nama ? 'selected' : '' }}>
                                                                        {{ $dosen->nama }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="id_role{{ $user->id }}"
                                                                class="form-label">Role</label>
                                                            <select class="form-control" id="id_role{{ $user->id }}"
                                                                name="id_role" required>
                                                                <option value="1"
                                                                    {{ $user->id_role == 1 ? 'selected' : '' }}>Admin
                                                                </option>
                                                                <option value="2"
                                                                    {{ $user->id_role == 2 ? 'selected' : '' }}>User
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="email{{ $user->id }}"
                                                                class="form-label">Email</label>
                                                            <input type="email" class="form-control"
                                                                id="email{{ $user->id }}" name="email"
                                                                value="{{ $user->email }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="password{{ $user->id }}"
                                                                class="form-label">Password</label>
                                                            <input type="password" class="form-control"
                                                                id="password{{ $user->id }}" name="password">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center" style="font-size: 13px">Tidak ada
                                            data.
                                        </td>
                                    </tr>
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('akun.user.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <select class="form-control" id="nama" name="nama" required>
                                @foreach ($dosens as $dosen)
                                    <option value="{{ $dosen->nama }}">{{ $dosen->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="id_role" class="form-label">Role</label>
                            <select class="form-control" id="id_role" name="id_role" required>
                                <option value="1">Admin</option>
                                <option value="2">User</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
