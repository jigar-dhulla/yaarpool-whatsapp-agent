<?php

namespace App\Http\Controllers;

use App\Enums\RideType;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class RidesController extends Controller
{
    public function index(Request $request): View
    {
        $type = RideType::tryFrom((string) $request->query('type'));
        $search = trim((string) $request->query('search'));
        $now = Carbon::now();

        $query = Ride::query()
            ->where(function ($q) use ($now) {
                $q->where('departs_at', '>=', $now)
                    ->orWhereNotNull('recurrence_days');
            });

        if ($type !== null) {
            $query->where('type', $type);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('from_location', 'like', "%{$search}%")
                    ->orWhere('to_location', 'like', "%{$search}%")
                    ->orWhere('chat_jid', 'like', "%{$search}%")
                    ->orWhere('sender_name', 'like', "%{$search}%");
            });
        }

        $rides = $query->get()
            ->sortBy(fn (Ride $ride): Carbon => $ride->nextOccurrence($now))
            ->values();

        return view('rides.index', [
            'rides' => $rides,
            'type' => $type,
            'search' => $search,
        ]);
    }
}
