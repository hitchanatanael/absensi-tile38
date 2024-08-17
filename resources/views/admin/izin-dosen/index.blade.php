@extends('layout.main')
@php
    use Carbon\Carbon;
@endphp
@section('content')
    <div class="container-fluid">
        <div class="pagetitle pb-2 headnav">
            <h2>Absen Dosen</h2>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Absen Dosen</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th class="text-center" style="font-size: 14px">Nama</th>
                                    <th class="text-center" style="font-size: 14px">Jenis Izin</th>
                                    <th class="text-center" style="font-size: 14px">Tanggal</th>
                                    <th class="text-center" style="font-size: 14px">Jumlah Hari</th>
                                    <th class="text-center" style="font-size: 14px">Aksi</th>
                                </tr>
                            </thead>
                            @foreach ($izins as $izin)
                                <tr>
                                    <td class="text-center">{{ $izin->users->nama }}</td>
                                    <td class="text-center">{{ $izin->jenis_izin }}</td>
                                    <td class="text-center">
                                        {{ Carbon::parse($izin->mulai_izin)->translatedFormat('l, d M Y') }} -
                                        {{ Carbon::parse($izin->selesai_izin)->translatedFormat('l, d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($izin->mulai_izin)->diffInDays(\Carbon\Carbon::parse($izin->selesai_izin)) + 1 }}
                                        hari
                                    </td>
                                    <td class="text-center">
                                        <div class="d-grid gap-2 d-md-block">
                                            <a class="btn btn-primary" href="{{ route('izin.setuju', $izin->id) }}"><i
                                                    class="fa-solid fa-circle-check"></i>
                                            </a>

                                            <a class="btn btn-danger" href="{{ route('izin.tolak', $izin->id) }}"><i
                                                    class="fa-solid fa-circle-xmark"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalLabel">Lokasi Absen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
