<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return inertia('Events/Index', ['events' => $events]);
    }

    public function create()
    {
        return inertia('Events/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:0'
        ]);

        $validated['qr_code'] = Str::random(20);
        $validated['is_active'] = true;

        $event = Event::create($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Etkinlik başarıyla oluşturuldu.');
    }

    public function show(Event $event)
    {
        $event->load('participants');
        return inertia('Events/Show', ['event' => $event]);
    }

    public function edit(Event $event)
    {
        return inertia('Events/Edit', ['event' => $event]);
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:0',
            'is_active' => 'required|boolean'
        ]);

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Etkinlik başarıyla güncellendi.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')
            ->with('success', 'Etkinlik başarıyla silindi.');
    }
} 