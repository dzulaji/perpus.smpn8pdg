@extends('layouts.main')

@section('style')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        /* General Styles */
        html,
        body,
        .intro {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
            padding: 12px;
            text-align: left;
        }

        thead th {
            background-color: #002d72;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .table-scroll {
            border-radius: 0.5rem;
            overflow: auto;
            max-height: 700px;
        }

        .card {
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {

            table th,
            table td {
                font-size: 0.9rem;
            }
        }
    </style>
@endsection

@section('main-content')
    <div class="container mt-5">
        <section class="intro">
            <div class="bg-image h-100">
                <div class="mask d-flex align-items-center h-100">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true">
                                            <table class="table table-striped mb-0">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Code</th>
                                                        <th scope="col">Judul Buku</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if ($bookings->isEmpty())
                                                        <tr>
                                                            <td colspan="5" class="text-center">Tidak ada data yang
                                                                tersedia</td>
                                                        </tr>
                                                    @else
                                                        @foreach ($bookings as $booking)
                                                            <tr>
                                                                <th scope="row">{{ $loop->iteration }}</th>
                                                                <td>{{ $booking->code }}</td>
                                                                <td>{{ $booking->book->title }}</td>
                                                                <td>
                                                                    @if ($booking->status == 'Dikembalikan')
                                                                        @if ($booking->expired_at < now())
                                                                            <p class="badge text-bg-danger mb-0">
                                                                                {{ $booking->status }} terlambat</p>
                                                                        @else
                                                                            <p class="badge text-bg-secondary mb-0">
                                                                                {{ $booking->status }}</p>
                                                                        @endif
                                                                    @elseif($booking->expired_at < now())
                                                                        <p class="badge text-bg-danger mb-0">Terlambat</p>
                                                                    @else
                                                                        <p
                                                                            class="badge {{ $booking->status == 'Diajukan' ? 'text-bg-warning' : '' }} {{ $booking->status == 'Disetujui' ? 'text-bg-success' : '' }} {{ $booking->status == 'Ditolak' ? 'text-bg-dark' : '' }} mb-0">
                                                                            {{ $booking->status }}</p>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <a href="/booking/{{ $booking->id }}"
                                                                        class="btn btn-info badge"><i
                                                                            class="bi bi-eye"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        let table = new DataTable('#myTable');
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
