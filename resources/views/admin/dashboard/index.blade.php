@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="pagetitle pb-2 headnav">
            <h2>Dashboard</h2>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card overflow-hidden">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-9 fw-semibold">Jumlah Data Dosen</h5>
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="fw-semibold mb-3">{{ $jmlDosen }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card overflow-hidden">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-9 fw-semibold">Jumlah Kehadiran</h5>
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="fw-semibold mb-3">{{ $jmlHadir }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card overflow-hidden">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-9 fw-semibold">Jumlah Izin</h5>
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="fw-semibold mb-3">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card overflow-hidden">
                    <div class="card-body p-4">
                        <div id="mapAttend"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        var mapAttend = L.map('mapAttend').setView([0.4698860725642899, 101.38592574731616], 19);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 30,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(mapAttend);

        var polygon = L.polygon([
            [0.46995761521579044, 101.3858240257524],
            [0.4699737079282085, 101.38593064355403],
            [0.46991268972682326, 101.38603659080346],
            [0.46977858378783105, 101.38604463742999],
            [0.46974840995120126, 101.38593399631509],
            [0.46976517319379313, 101.38585151839307],
            [0.46979668808975295, 101.385800556425],
            [0.46979668808975295, 101.385800556425],
            [0.46994152250335974, 101.38583274293114],
            [0.46995761521579044, 101.3858240257524],
        ]).addTo(mapAttend);

        polygon.bindPopup("Lokasi Absen");
    </script>
@endsection
