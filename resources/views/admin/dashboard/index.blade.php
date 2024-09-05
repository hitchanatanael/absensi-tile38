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
        var mapAttend = L.map('mapAttend').setView([0.4732347824651372, 101.37993796526224], 14.5);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 30,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(mapAttend);

        fetch('/geojson/unri')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text(); // Mengambil respons sebagai teks untuk debugging
            })
            .then(text => {
                console.log(text); // Lihat isi teks dari respons
                try {
                    const data = JSON.parse(text); // Parsing manual JSON
                    var geojsonLayer = L.geoJSON(data).addTo(mapAttend);
                    geojsonLayer.bindPopup("Lokasi Absen");
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                }
            })
            .catch(error => console.error('Error loading GeoJSON:', error));

        polygon.bindPopup("Lokasi Absen");
    </script>
@endsection
