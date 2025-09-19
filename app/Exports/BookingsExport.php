<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;

class BookingsExport implements FromView
{
    public function view(): View
    {
        return view('admin.exports.booking', [
            'bookings' => Booking::with(['book', 'user'])->get(),
            'exported_at' => Carbon::now()
                ->setTimezone(config('app.timezone'))
                ->translatedFormat('d F Y H:i'),
        ]);
    }
}
