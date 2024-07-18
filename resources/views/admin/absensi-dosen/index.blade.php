@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="pagetitle pb-3">
            <h2>Absensi Dosen</h2>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Absensi Dosen</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Data</h6>
                        <a href="#" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Tambah Data
                        </a>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center" style="width: 10px;">No.</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">NIP</th>
                                    <th class="text-center">Jabatan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
