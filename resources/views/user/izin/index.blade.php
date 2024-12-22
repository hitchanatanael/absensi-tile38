@extends('layout.main')
@php
    use Carbon\Carbon;
@endphp
@section('content')
    <div class="container-fluid">
        <div class="pagetitle headnav">
            <h2>Pengajuan Izin</h2>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Pengajuan Izin</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addIzinModal"><i
                            class="ti ti-plus me-2"></i>Buat Pengajuan</a>
                </div>

                @forelse($izins as $izin)
                    <div class="card">
                        <div class="card-body">
                            <table class="table">

                                <tr>
                                    <td class="fw-bold">Jenis Izin</td>
                                    <td>{{ $izin->jenis_izin }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal</td>
                                    <td>
                                        {{ Carbon::parse($izin->mulai_izin)->translatedFormat('l, d M Y') }} -
                                        {{ Carbon::parse($izin->selesai_izin)->translatedFormat('l, d M Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Jumlah Hari</td>
                                    <td>
                                        {{ Carbon::parse($izin->mulai_izin)->diffInDays(Carbon::parse($izin->selesai_izin)) + 1 }}
                                        hari
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status</td>
                                    <td>
                                        @if ($izin->status == 0)
                                            <span class="badge text-bg-warning">Pending</span>
                                        @elseif ($izin->status == 1)
                                            <span class="badge text-bg-secondary">Diterima</span>
                                        @else
                                            <span class="badge text-bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                @if ($izin->status == 0)
                                    <tr>
                                        <th></th>
                                        <td>
                                            <form action="{{ route('izin.destroy', $izin->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa-solid fa-trash me-2"></i>Hapus Pengajuan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data.</td>
                    </tr>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="addIzinModal" tabindex="-1" aria-labelledby="addIzinModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addIzinModalLabel">Buat Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('izin.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                value="{{ Auth::user()->nama }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_izin" class="form-label">Jenis Sakit</label>
                            <select name="jenis_izin" class="form-select" aria-label="Default select example" required>
                                <option selected>Pilih</option>
                                <option value="1">Izin</option>
                                <option value="2">Sakit</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="mulai_izin" class="form-label">Mulai Izin</label>
                            <input type="date" class="form-control" id="mulai_izin" name="mulai_izin" required>
                        </div>
                        <div class="mb-3">
                            <label for="selesai_izin" class="form-label">Selesai Izin</label>
                            <input type="date" class="form-control" id="selesai_izin" name="selesai_izin" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan">
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
