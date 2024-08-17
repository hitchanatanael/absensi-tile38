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
                                <h4 class="fw-semibold mb-3">{{ $jmlIzin }}</h4>
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
        var mapAttend = L.map('mapAttend').setView([0.4778934185410452, 101.37961044457528], 19);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 30,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(mapAttend);

        var polygon = L.polygon([
            [0.4780762218230241, 101.37931691924238],
            [0.47810196876374406, 101.37953320106662],
            [0.4781032561107826, 101.37971343592015],
            [0.4780273026353688, 101.37986534815384],
            [0.4780273026353688, 101.37986534815384],
            [0.47771447729551286, 101.37996576471511],
            [0.4776565466754453, 101.37996705210692],
            [0.47756900707088334, 101.37991813121809],
            [0.4775303866567629, 101.3796374798033],
            [0.47770675321286177, 101.37932721837686],
            [0.47787797037613744, 101.37930404532428],
            [0.4780762218230241, 101.37931691924238]
        ]).addTo(mapAttend);

        polygon.bindPopup("Lokasi Absen");
    </script>
@endsection
