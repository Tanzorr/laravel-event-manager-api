<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    use CanLoadRelationships;

    private array $relationships = [
        'user',
        'event',
    ];

    private function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->authorizeResource(Attendee::class, 'attendee');

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $attendees = $this->loadRelationships(
            $event->attendees()->latest()
        );

        return AttendeeResource::collection(
            $attendees->paginate(13)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event): AttendeeResource
    {
        $attendee = $event->attendees()->create([
            'user_id' => 1
        ]);

        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event, Attendee $attendee): AttendeeResource
    {
        return new AttendeeResource($attendee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, Attendee $attendee): \Illuminate\Http\Response
    {
        $this->authorize('delete-attendee', [$event, $attendee]);
        $attendee->delete();

        return response()->noContent();
    }
}
