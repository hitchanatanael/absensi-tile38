@extends('layout.main')

@section('content')
    <div class="container">
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
                        {{ now()->format('l, d F Y') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-11 col-md-11 col-lg-11 white-box">
                <div class="card overflow-hidden ">
                    <div class="card-body p-4">
                        <div class="row pt-4 mb-4">
                            <div class="col">
                                <label for="startTime" class="form-label text-black">Start Time</label>
                                <input type="text" class="form-control" id="startTime"
                                    value="{{ $absensiHariIni->first()->jam_masuk ?? '09:00' }}" readonly>
                            </div>
                            <div class="col">
                                <label for="endTime" class="form-label text-black">End Time</label>
                                <input type="text" class="form-control" id="endTime"
                                    value="{{ $absensiHariIni->first()->jam_keluar ?? '17:00' }}" readonly>
                            </div>
                        </div>
                        @if ($absensiHariIni->isNotEmpty() && $absensiHariIni->first()->status == 1)
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
                    <h4 class="font-semibold text-dark">Recent attendance</h4>
                    <div class="mt-2">
                        <table class="table">
                            <tbody>
                                @foreach ($absensiHariIni as $absensi)
                                    <tr>
                                        <td>{{ $absensi->tgl_absen }}</td>
                                        <td>{{ $absensi->jam_masuk }}</td>
                                        <td>{{ $absensi->jam_keluar }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script>
        // Get current location
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }

        // Call getLocation on page load
        window.onload = getLocation;
    </script>
@endsection
