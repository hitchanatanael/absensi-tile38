@extends('layout.main')
@php
    use Carbon\Carbon;
@endphp
@section('content')
    <div>
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-12 col-lg-12 blue-box">
                <div class="text-center py-2">
                    <h1 class="h4 title-abs text-white">Absensi</h1>
                </div>
                <div class="text-center">
                    <p class="text-4xl font-bold text-white">
                        {{ now()->format('H:i') }}
                    </p>
                    <p class="text-white">
                        {{ Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-11 col-md-11 col-lg-11 white-box">
                <div class="card overflow-hidden">
                    <div class="card-body p-4">
                        <div class="row pt-4 mb-4">
                            <div class="col">
                                <label for="startTime" class="form-label text-black">Start Time</label>
                                <input type="text" class="form-control" id="startTime" value="09:00" readonly>
                            </div>
                            <div class="col">
                                <label for="endTime" class="form-label text-black">End Time</label>
                                <input type="text" class="form-control" id="endTime" value="17:00" readonly>
                            </div>
                        </div>
                        @if ($absensiHariIni->where('status', 1)->isNotEmpty())
                            <form action="{{ route('absensi.clock-out') }}" method="POST" id="absensiForm">
                                @csrf
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                                <button type="submit" class="btn btn-danger w-100 mb-4">Clock Out</button>
                            </form>
                        @else
                            <form action="{{ route('absensi.clock-in') }}" method="POST" id="absensiForm">
                                @csrf
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                                <button type="submit" class="btn btn-primary w-100 mb-4">Clock In</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="container-fluid bg-white pt-2">
                <div>
                    <h5 class="font-semibold text-dark">
                        <i class="fa-solid fa-street-view me-2"></i>Attendance History
                    </h5>
                    <div class="mt-2">
                        <table class="table">
                            <tbody>
                                @foreach ($absensiHariIni as $absensi)
                                    <tr>
                                        <td>
                                            <p class="fw-bold">
                                                {{ Carbon::parse($absensi->tgl_absen)->translatedFormat('l, d F Y') }}
                                            </p>
                                            <span class="fw-bold {{ $absensi->is_late == 1 ? 'text-danger' : '' }}">
                                                <i class="fa-solid fa-clock me-1"></i>
                                                {{ $absensi->jam_masuk ? Carbon::parse($absensi->jam_masuk)->format('H:i') : '' }}
                                            </span>

                                            -

                                            <span class="fw-bold">
                                                @if ($absensi->jam_keluar)
                                                    <i class="fa-solid fa-clock me-1"></i>
                                                    {{ Carbon::parse($absensi->jam_keluar)->format('H:i') }}
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        $('#latitude').val(position.coords.latitude);
                        $('#longitude').val(position.coords.longitude);
                    }, showError, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }

            function showError(error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert("Anda menolak permintaan Geolokasi.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Informasi lokasi tidak tersedia.");
                        break;
                    case error.TIMEOUT:
                        alert("Permintaan untuk mendapatkan lokasi Anda telah habis waktunya.");
                        break;
                    case error.UNKNOWN_ERROR:
                        alert("Terjadi kesalahan yang tidak diketahui.");
                        break;
                }
            }

            $('#absensiForm').submit(function(event) {
                event.preventDefault();

                getLocation();

                setTimeout(function() {
                    $.ajax({
                        url: '{{ $absensiHariIni->where('status', 1)->isNotEmpty() ? route('absensi.clock-out') : route('absensi.clock-in') }}',
                        type: 'POST',
                        data: {
                            latitude: $('#latitude').val(),
                            longitude: $('#longitude').val(),
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan pada server.'
                            });
                        }
                    });
                }, 1000);
            });

            document.addEventListener('DOMContentLoaded', function() {
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: '{{ session('success') }}'
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: '{{ session('error') }}'
                    });
                @endif
            });

            getLocation();
        });
    </script>
@endsection
