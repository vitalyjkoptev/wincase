<?php

namespace App\Services\Core;

use App\Models\CalendarEvent;

class CalendarService
{
    public function getEvents(array $filters = []): array
    {
        $query = CalendarEvent::with('assignee');

        if (!empty($filters['start'])) $query->where('start_at', '>=', $filters['start']);
        if (!empty($filters['end'])) $query->where('start_at', '<=', $filters['end']);
        if (!empty($filters['user_id'])) $query->where('user_id', $filters['user_id']);
        if (!empty($filters['type'])) $query->where('type', $filters['type']);
        if (!empty($filters['client_id'])) $query->where('client_id', $filters['client_id']);

        return $query->orderBy('start_at')->get()->toArray();
    }

    public function create(array $data): CalendarEvent
    {
        return CalendarEvent::create($data);
    }

    public function update(int $id, array $data): CalendarEvent
    {
        $event = CalendarEvent::findOrFail($id);
        $event->update($data);
        return $event->fresh();
    }

    public function delete(int $id): void
    {
        CalendarEvent::findOrFail($id)->delete();
    }

    public function getTodaySchedule(int $userId): array
    {
        return CalendarEvent::where('user_id', $userId)
            ->whereDate('start_at', now()->toDateString())
            ->orderBy('start_at')
            ->get()
            ->toArray();
    }

    public function getUpcoming(int $userId, int $days = 7): array
    {
        return CalendarEvent::where('user_id', $userId)
            ->whereBetween('start_at', [now(), now()->addDays($days)])
            ->orderBy('start_at')
            ->get()
            ->toArray();
    }

    public function getEventTypes(): array
    {
        return [
            'consultation' => 'Client Consultation',
            'meeting' => 'Internal Meeting',
            'deadline' => 'Document Deadline',
            'court' => 'Court / Urząd Hearing',
            'reminder' => 'Reminder',
            'follow_up' => 'Follow-up Call',
            'appointment' => 'Appointment (Urząd)',
        ];
    }
}
