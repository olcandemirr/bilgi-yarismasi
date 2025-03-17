<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20'
        ]);

        $participant = $event->participants()->create($validated);

        return redirect()->back()
            ->with('success', 'Katılımcı başarıyla eklendi.');
    }

    public function checkIn(Request $request, Event $event, string $qrCode)
    {
        if ($event->qr_code !== $qrCode) {
            return redirect()->back()
                ->with('error', 'Geçersiz QR kod.');
        }

        $participant = Participant::where('event_id', $event->id)
            ->where('email', $request->email)
            ->first();

        if (!$participant) {
            return redirect()->back()
                ->with('error', 'Katılımcı bulunamadı.');
        }

        if ($participant->checked_in) {
            return redirect()->back()
                ->with('error', 'Katılımcı zaten check-in yapmış.');
        }

        $participant->update([
            'checked_in' => true,
            'checked_in_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Check-in başarıyla tamamlandı.');
    }

    public function destroy(Event $event, Participant $participant)
    {
        if ($participant->event_id !== $event->id) {
            return redirect()->back()
                ->with('error', 'Bu katılımcı bu etkinliğe ait değil.');
        }

        $participant->delete();

        return redirect()->back()
            ->with('success', 'Katılımcı başarıyla silindi.');
    }
} 