@extends('layout.main')

@section('main')
    <div id="content">
        <div class="hero bg-primary" style="padding-left: 24px; padding-right: 24px; padding-top: 20px;">
            <div class="d-sm-flex align-items-center justify-content-between pt-4">
                <h1 class="h4 mb-0 text-white">Absensi</h1>
            </div>
            <div class="text-center">
                <p class="text-4xl font-bold text-white">
                    {{ \Carbon\Carbon::parse($mytime)->translatedFormat('H:i') }}
                </p>
                <p class="text-white">
                    {{ \Carbon\Carbon::parse($mytime)->translatedFormat('d F Y') }}
                </p>
            </div>
            <div class="col-lg-12 pb-4">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row pt-4 mb-4">
                            <div class="col">
                                <label for="startTime" class="form-label text-black">Start Time</label>
                                <input type="text" class="form-control" id="startTime" placeholder="09:00" readonly>
                            </div>
                            <div class="col">
                                <label for="endTime" class="form-label text-black">End Time</label>
                                <input type="text" class="form-control" id="endTime" placeholder="17:00" readonly>
                            </div>
                        </div>
                        @if (session('clockStatus') == 'Clock In' || !session('clockStatus') == 'Clock Out')
                            <button id="btn-clock-in" class="btn btn-primary w-100 mb-4">Clock In</button>
                        @else
                            <button id="btn-clock-out" class="btn btn-danger w-100 mb-4">Clock Out</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid bg-white pt-4">
            <div>
                <h3 class="text-lg font-semibold text-dark">Recent attendance</h3>
                <div class="mt-2">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col" width="50%">Tanggal</th>
                                <th scope="col" width="25%">Masuk</th>
                                <th scope="col" width="25%">Keluar</th>
                            </tr>
                        </thead>
                        @foreach ($model as $absen)
                            <tr>
                                <td class="text-secondary">
                                    {{ \Carbon\Carbon::parse($absen->tgl_absen)->translatedFormat('d F Y') }}
                                </td>
                                <td class="text-light">
                                    <span class="badge rounded-pill bg-success">
                                        {{ $absen->jam_masuk }}
                                    </span>
                                </td>
                                <td class="text-light">
                                    <span class="badge rounded-pill bg-primary">
                                        {{ $absen->jam_keluar }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

<div class="d-flex justify-content-between mb-2">
    <p></p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#btn-clock-in, #btn-clock-out').click(function(event) {
            event.preventDefault(); // Menghentikan default action dari event click

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    var clockStatus = $(this).attr('id') === 'btn-clock-in' ? 'Clock In' :
                        'Clock Out';

                    $.ajax({
                        url: '{{ route('absen') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            latitude: latitude,
                            longitude: longitude,
                            clockStatus: clockStatus
                        },
                        success: function(data) {
                            alert('Anda Berhasil Melakukan Absensi. Koordinat Anda: Latitude: ' +
                                latitude + ', Longitude: ' + longitude);
                            console.log(data);
                        },
                        error: function(error) {
                            alert('Anda belum berada dalam lokasi absensi!! Koordinat Anda: Latitude: ' +
                                latitude + ', Longitude: ' + longitude);
                            console.error('Error:', error);
                        }
                    });
                }, function(error) {
                    alert('Error mendapatkan lokasi: ' + error.message);
                });
            } else {
                alert('Geolocation tidak didukung di perangkat Anda');
            }
        });
    });
</script>
