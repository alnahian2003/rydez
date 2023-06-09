<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\TripAcceptedEvent;
use App\Events\TripEndedEvent;
use App\Events\TripLocationUpdatedEvent;
use App\Events\TripStartedEvent;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TripController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'destination_name' => 'required',
        ]);

        return $request->user()->trips()->create($request->only([
            'origin',
            'destination',
            'destination_name',
        ]));
    }

    public function show(Request $request, Trip $trip)
    {
        if ($request->user()->id === $trip->user->id) {
            return $trip;
        }

        if ($request->user()->driver && $trip->driver && $request->user()->driver->id === $trip->driver->id) {
            return $trip;
        }

        return response()->json(['message' => 'You are not authorized to view this trip.'], Response::HTTP_FORBIDDEN);
    }

    public function accept(Request $request, Trip $trip)
    {
        $request->validate(['driver_location' => 'required']);

        $trip->update([
            'driver_id' => $request->user()->id,
            'driver_location' => $request->driver_location,
        ]);

        $trip->load('driver.user');

        event(new TripAcceptedEvent($trip, $request->user()));

        return $trip;
    }

    public function start(Request $request, Trip $trip)
    {
        $trip->update(['is_started' => true]);

        $trip->load('driver.user');

        event(new TripStartedEvent($trip, $request->user()));

        return $trip;
    }

    public function end(Request $request, Trip $trip)
    {
        $trip->update(['is_complete' => true]);

        $trip->load('driver.user');
        event(new TripEndedEvent($trip, $request->user()));

        return $trip;
    }

    public function location(Request $request, Trip $trip)
    {
        $request->validate(['driver_location' => 'required']);

        $trip->update(['driver_location' => $request->driver_location]);

        $trip->load('driver.user');

        event(new TripLocationUpdatedEvent($trip, $request->user()));

        return $trip;
    }
}
