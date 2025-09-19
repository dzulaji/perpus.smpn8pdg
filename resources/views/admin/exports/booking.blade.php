<table>
    <thead>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold;">Data Peminjaman</th>
        </tr>
        <tr></tr>
        <tr>
            <th>No.</th>
            <th>Kode</th>
            <th>Judul Buku</th>
            <th>Peminjam</th>
            <th>Tgl Pinjam</th>
            <th>Tgl Kembali</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bookings as $booking)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $booking->code }}</td>
                <td>{{ $booking->book->title }}</td>
                <td>{{ $booking->user->username }}</td>
                <td>{{ $booking->created_at->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->expired_at)->format('d-m-Y') }}</td>
                <td>
                    @if ($booking->status == 'Dikembalikan')
                        @if ($booking->expired_at < now())
                            Dikembalikan Terlambat
                        @else
                            Dikembalikan
                        @endif
                    @elseif($booking->expired_at < now())
                        Terlambat
                    @else
                        {{ $booking->status }}
                    @endif
                </td>
            </tr>
        @endforeach
        <tr></tr>
        <tr>
            <td colspan="7">Di Export Pada Tanggal: {{ $exported_at }}</td>
        </tr>
    </tbody>
</table>
