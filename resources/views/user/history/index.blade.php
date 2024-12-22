@extends('layout.main')
@php
    use Carbon\Carbon;
@endphp
@section('content')
    <div class="container-fluid">
        <div class="pagetitle headnav">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>History</h2>
                <div class="d-flex gap-2">
                    <select class="form-select" id="monthSelect" style="width: 150px;">
                        @foreach (range(1, 12) as $month)
                            <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                                {{ $selectedMonth == $month ? 'selected' : '' }}>
                                {{ Carbon::create()->month($month)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select class="form-select" id="yearSelect" style="width: 100px;">
                        @foreach (range(date('Y'), date('Y') - 2) as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary" id="filterBtn">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </div>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">History</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                @forelse($history->groupBy(function($date) {
                                    return Carbon::parse($date->date ?? $date->tgl_kirim)->format('F Y');
                                }) as $monthYear => $items)
                    <div class="card shadow-lg mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">{{ $monthYear }}</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @foreach ($items as $item)
                                    {{-- Kode item history tetap sama seperti sebelumnya --}}
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card shadow-lg">
                        <div class="card-body text-center py-5">
                            <i class="fa-solid fa-calendar-xmark fa-3x mb-3 text-muted"></i>
                            <h5 class="text-muted">Tidak ada data history untuk periode ini</h5>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .badge {
            font-weight: 500;
            padding: 0.5em 1em;
        }

        .list-group-item {
            border-left: none;
            border-right: none;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .125);
        }
    </style>

    @push('scripts')
        <script>
            document.getElementById('filterBtn').addEventListener('click', function() {
                const month = document.getElementById('monthSelect').value;
                const year = document.getElementById('yearSelect').value;
                window.location.href = `{{ route('history') }}?month=${month}&year=${year}`;
            });
        </script>
    @endpush
@endsection
