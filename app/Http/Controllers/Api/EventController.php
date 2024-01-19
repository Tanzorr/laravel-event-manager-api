<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use CanLoadRelationships;

    protected array $relationships = [
        'user',
        'attendees',
        'attendees.user',
    ];

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->authorizeResource(Event::class, 'event');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = $this->loadRelationships(Event::query());

        return EventResource::collection(
            $query->latest()->paginate()
        );
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        $include =  request()->query('include');

        if (!$include) {
            return false;
        }

        $include = explode(',', $include);

        $relations = array_map('trim', $include);

        return in_array($relation, $relations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): EventResource
    {
        $event = Event::create([
            ...$request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => $request->user()->id
        ]);

        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): EventResource
    {
        return new EventResource($this->loadRelationships($event));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event): EventResource
    {
//        if(Gate::denies('update-event', $event)){
//            abort(403, 'You cannot update this event');
//        }

            $this->authorize('update', $event);
       $event->update( $request->validate([
            'name'=>'sometimes|string|max:255',
            'description'=>'sometimes|string',
            'start_time'=>'sometimes|date',
            'end_time'=>'sometimes|date|after:start_time',
        ]));

       return new EventResource($this->loadRelationships($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): \Illuminate\Http\JsonResponse
    {
        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully',
        ]);
    }
}
