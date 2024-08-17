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
                                    <th class="text-center" style="font-size: 14px">No.</th>
                                    <th class="text-center" style="font-size: 14px">Nama</th>
                                    <th class="text-center" style="font-size: 14px">Masuk</th>
                                    <th class="text-center" style="font-size: 14px">Keluar</th>
                                    <th class="text-center" style="font-size: 14px">Aksi</th>
                                </tr>
                            </thead>

                            @foreach ($absensis as $absen)
                                @php
                                    $koorMasuk = json_decode($absen->koor_masuk, true);
                                    $koorKeluar = json_decode($absen->koor_keluar, true);
                                @endphp
                                <tbody>
                                    <tr>
                                        <td class="text-center" style="width: 80px; font-size: 14px">
                                            {{ $loop->iteration }}.
                                        </td>

                                        <td style="font-size: 14px">{{ $absen->users->nama }}</td>

                                        <td class="text-center" style="width: 120px; font-size: 14px">
                                            @if ($absen->is_late == 1)
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#mapModal" data-lat="{{ $koorMasuk['lat'] }}"
                                                    data-lng="{{ $koorMasuk['lng'] }}">
                                                    {{ Carbon::parse($absen->jam_masuk)->format('H:i') }}
                                                </button>
                                            @else
                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#mapModal" data-lat="{{ $koorMasuk['lat'] }}"
                                                    data-lng="{{ $koorMasuk['lng'] }}">
                                                    {{ Carbon::parse($absen->jam_masuk)->format('H:i') }}
                                                </button>
                                            @endif
                                        </td>

                                        <td class="text-center" style="width: 120px; font-size: 14px">
                                            @if ($koorKeluar)
                                                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#mapModal" data-lat="{{ $koorKeluar['lat'] ?? '' }}"
                                                    data-lng="{{ $koorKeluar['lng'] ?? '' }}">
                                                    {{ Carbon::parse($absen->jam_keluar)->format('H:i') }}
                                                </button>
                                            @endif
                                        </td>

                                        <td class="text-center" style="width: 120px; font-size: 14px">
                                            <form action="{{ route('absensi.dosen.destroy', $absen->id) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var mapModal = document.getElementById('mapModal');

        mapModal.addEventListener('shown.bs.modal', function(event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var lat = button.getAttribute('data-lat');
            var lng = button.getAttribute('data-lng');

            var map = L.map('map').setView([lat, lng], 20);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            L.marker([lat, lng]).addTo(map);

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
                [0.46995761521579044, 101.3858240257524]
            ]).addTo(map);
        });
    });
</script>
