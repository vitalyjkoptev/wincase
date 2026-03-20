<?php

namespace App\Services\Core;

use App\Models\Task;
use App\Models\Document;
use App\Models\CaseModel;
use App\Models\CalendarEvent;
use Illuminate\Support\Facades\Storage;

// =====================================================
// TASKS SERVICE
// =====================================================

class TasksService
{
    public function list(array $filters = []): array
    {
        $query = Task::with(['assignee', 'case']);

        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['assigned_to'])) $query->where('assigned_to', $filters['assigned_to']);
        if (!empty($filters['case_id'])) $query->where('case_id', $filters['case_id']);
        if (!empty($filters['priority'])) $query->where('priority', $filters['priority']);
        if (!empty($filters['due_before'])) $query->where('due_date', '<=', $filters['due_before']);
        if (!empty($filters['overdue'])) {
            $query->where('status', '!=', 'completed')
                  ->where('due_date', '<', now()->toDateString());
        }

        return $query->orderBy('due_date')->orderByDesc('priority')->get()->toArray();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): Task
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task->fresh();
    }

    public function complete(int $id): Task
    {
        $task = Task::findOrFail($id);
        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        return $task;
    }

    public function getMyTasks(int $userId): array
    {
        return Task::where('assigned_to', $userId)
            ->where('status', '!=', 'completed')
            ->orderBy('due_date')
            ->with('case')
            ->get()
            ->toArray();
    }

    public function getOverdue(): array
    {
        return Task::where('status', '!=', 'completed')
            ->where('due_date', '<', now()->toDateString())
            ->with(['assignee', 'case'])
            ->orderBy('due_date')
            ->get()
            ->toArray();
    }

    public function getStatistics(): array
    {
        return [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'overdue' => Task::where('status', '!=', 'completed')
                ->where('due_date', '<', now()->toDateString())->count(),
            'due_today' => Task::where('due_date', now()->toDateString())
                ->where('status', '!=', 'completed')->count(),
            'due_this_week' => Task::whereBetween('due_date', [
                now()->toDateString(),
                now()->addDays(7)->toDateString()
            ])->where('status', '!=', 'completed')->count(),
        ];
    }
}

// =====================================================
// DOCUMENTS SERVICE
// =====================================================

class DocumentsService
{
    protected string $disk = 'private';

    public function listByClient(int $clientId): array
    {
        return Document::where('client_id', $clientId)
            ->orderByDesc('created_at')
            ->get()
            ->toArray();
    }

    public function listByCase(int $caseId): array
    {
        return Document::where('case_id', $caseId)
            ->orderByDesc('created_at')
            ->get()
            ->toArray();
    }

    public function upload(array $data, $file): Document
    {
        $path = $file->store(
            "documents/{$data['client_id']}/" . now()->format('Y/m'),
            $this->disk
        );

        return Document::create([
            'client_id' => $data['client_id'],
            'case_id' => $data['case_id'] ?? null,
            'type' => $data['type'] ?? 'other',
            'name' => $data['name'] ?? $file->getClientOriginalName(),
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_by' => auth()->id(),
            'expires_at' => $data['expires_at'] ?? null,
        ]);
    }

    public function getDownloadUrl(int $id): string
    {
        $doc = Document::findOrFail($id);
        return Storage::disk($this->disk)->temporaryUrl($doc->file_path, now()->addMinutes(30));
    }

    public function delete(int $id): void
    {
        $doc = Document::findOrFail($id);
        Storage::disk($this->disk)->delete($doc->file_path);
        $doc->delete();
    }

    public function getExpiringDocuments(int $days = 30): array
    {
        return Document::whereNotNull('expires_at')
            ->where('expires_at', '<=', now()->addDays($days)->toDateString())
            ->where('expires_at', '>=', now()->toDateString())
            ->with('client')
            ->orderBy('expires_at')
            ->get()
            ->toArray();
    }

    public function getDocumentTypes(): array
    {
        return [
            'passport' => 'Passport',
            'visa' => 'Visa',
            'residence_card' => 'Karta Pobytu',
            'work_permit' => 'Work Permit (Zezwolenie)',
            'pesel' => 'PESEL Certificate',
            'meldunek' => 'Meldunek (Registration)',
            'contract' => 'Employment Contract',
            'tax_document' => 'Tax Document',
            'diploma' => 'Diploma / Education',
            'marriage_cert' => 'Marriage Certificate',
            'birth_cert' => 'Birth Certificate',
            'bank_statement' => 'Bank Statement',
            'insurance' => 'Insurance Document',
            'photo' => 'Photo (3.5x4.5)',
            'power_of_attorney' => 'Power of Attorney',
            'application_form' => 'Application Form',
            'other' => 'Other',
        ];
    }
}

// =====================================================
// CALENDAR SERVICE
// =====================================================

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

// ---------------------------------------------------------------
// Аннотация (RU):
// TasksService — задачи: list (filters), create, update, complete, getMyTasks, getOverdue.
// Statistics: pending, in_progress, completed, overdue, due_today, due_this_week.
//
// DocumentsService — документы клиентов.
// upload() → Storage private disk, organized by client_id/year/month.
// getDownloadUrl() → temporaryUrl (30 min). getExpiringDocuments() → дедлайны.
// 17 типов документов: passport, visa, karta pobytu, PESEL, meldunek, etc.
//
// CalendarService — события: consultations, meetings, deadlines, court hearings.
// getTodaySchedule(), getUpcoming(), 7 типов событий.
// Файл: app/Services/Core/TasksDocumentsCalendar.php (split into 3 files in production)
// ---------------------------------------------------------------
