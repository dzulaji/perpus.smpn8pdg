@extends('admin.layouts.main')

@section('style')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <style>
        /* Kelas untuk memotong teks menjadi satu baris dengan elipsis (...) */
        .truncate-1-line {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('main-content')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Peminjaman</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('admin.booking.export') }}" class="btn btn-success">Export Excel</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="myTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode</th>
                                <th>Judul Buku</th>
                                <th>Peminjam</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $booking->code }}</td>
                                    <td style="max-width: 300px;" title="{{ $booking->book->title }}">
                                        <div class="truncate-1-line">
                                            {{ $booking->book->title }}
                                        </div>
                                    </td>
                                    <td>{{ $booking->user->username }}</td>
                                    <td>{{ $booking->created_at->format('d-m-Y') }}</td>
                                    <td>{{ date('d-m-Y', strtotime($booking->expired_at)) }}</td>
                                    <td>
                                        @if ($booking->status == 'Dikembalikan')
                                            @if ($booking->expired_at < now())
                                                <p class="badge text-bg-danger">{{ $booking->status }} terlambat</p>
                                            @else
                                                <p class="badge text-bg-secondary">{{ $booking->status }}</p>
                                            @endif
                                        @elseif($booking->expired_at < now() && $booking->status != 'Dikembalikan')
                                            {{-- Tambahan kondisi untuk memastikan status belum dikembalikan --}}
                                            <p class="badge text-bg-danger">Terlambat</p>
                                        @else
                                            <p
                                                class="badge {{ $booking->status == 'Diajukan' ? 'text-bg-warning' : '' }} {{ $booking->status == 'Disetujui' ? 'text-bg-success' : '' }} {{ $booking->status == 'Ditolak' ? 'text-bg-dark' : '' }} mb-0">
                                                {{ $booking->status }}</p>
                                        @endif
                                    </td>
                                    <td class="d-flex flex-row align-items-start gap-1">
                                        <a href="/admin/booking/{{ $booking->id }}" class="btn btn-info">Proses
                                            Peminjaman</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script>
        // Pastikan jQuery sudah dimuat sebelum skrip DataTables
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    // 'pdf', // Anda bisa mengaktifkan kembali jika diperlukan
                    // 'excel', // Anda bisa mengaktifkan kembali jika diperlukan
                    'print'
                ]
            });
        });
    </script>
@endsection
