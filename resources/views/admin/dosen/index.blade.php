@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="pagetitle pb-3">
            <h2>Data Dosen</h2>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Dosen</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Data</h6>
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDosenModal"><i
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
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center" style="width: 10px;">No.</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">NIK</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">No. Hp</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                                @forelse($dosens as $dosen)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $dosen->nama }}</td>
                                        <td>{{ $dosen->nik }}</td>
                                        <td>{{ $dosen->alamat }}</td>
                                        <td>{{ $dosen->no_hp }}</td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editDosenModal{{ $dosen->id }}">Edit</a>
                                            <form action="{{ route('data.dosen.destroy', $dosen->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Data -->
                                    <div class="modal fade" id="editDosenModal{{ $dosen->id }}" tabindex="-1"
                                        aria-labelledby="editDosenModalLabel{{ $dosen->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editDosenModalLabel{{ $dosen->id }}">
                                                        Edit Data Dosen</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('data.dosen.update', $dosen->id) }}" method="POST">
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
                                                            <label for="nama{{ $dosen->id }}"
                                                                class="form-label">Nama</label>
                                                            <input type="text" class="form-control"
                                                                id="nama{{ $dosen->id }}" name="nama"
                                                                value="{{ $dosen->nama }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="nik{{ $dosen->id }}"
                                                                class="form-label">NIK</label>
                                                            <input type="text" class="form-control"
                                                                id="nik{{ $dosen->id }}" name="nik"
                                                                value="{{ $dosen->nik }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="alamat{{ $dosen->id }}"
                                                                class="form-label">Alamat</label>
                                                            <input type="text" class="form-control"
                                                                id="alamat{{ $dosen->id }}" name="alamat"
                                                                value="{{ $dosen->alamat }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="no_hp{{ $dosen->id }}" class="form-label">No.
                                                                HP</label>
                                                            <input type="text" class="form-control"
                                                                id="no_hp{{ $dosen->id }}" name="no_hp"
                                                                value="{{ $dosen->no_hp }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary">Ubah</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data.</td>
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
    <div class="modal fade" id="addDosenModal" tabindex="-1" aria-labelledby="addDosenModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDosenModalLabel">Tambah Data Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('data.dosen.store') }}" method="POST">
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
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat">
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
