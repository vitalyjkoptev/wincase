<?php

namespace App\Services\Reports;

use App\Models\ScheduledReport;
use Illuminate\Support\Facades\Mail;

class ScheduledReportsService
{
    public function list(): array
    {
        return ScheduledReport::where('active', true)->orderBy('created_at')->get()->toArray();
    }

    public function create(array $params): ScheduledReport
    {
        return ScheduledReport::create([
            'report_type' => $params['report_type'],
            'format' => $params['format'],
            'frequency' => $params['frequency'],
            'recipients' => $params['recipients'],
            'parameters' => $params['parameters'] ?? [],
            'active' => true,
            'next_run_at' => $this->calcNextRun($params['frequency']),
        ]);
    }

    public function delete(int $id): void
    {
        ScheduledReport::findOrFail($id)->update(['active' => false]);
    }

    public function runDue(): array
    {
        $due = ScheduledReport::where('active', true)
            ->where('next_run_at', '<=', now())
            ->get();

        $results = ['run' => 0, 'sent' => 0, 'failed' => 0];
        $service = app(ReportingService::class);

        foreach ($due as $sr) {
            $results['run']++;
            try {
                $report = $service->generate($sr->report_type, $sr->format, $sr->parameters ?? []);

                foreach ($sr->recipients as $email) {
                    Mail::raw("Scheduled report: {$sr->report_type}", function ($msg) use ($email, $report, $sr) {
                        $msg->to($email)
                            ->subject("WinCase Report: " . ucwords(str_replace('_', ' ', $sr->report_type)))
                            ->attach(storage_path("app/{$report['path']}"));
                    });
                }

                $sr->update([
                    'last_run_at' => now(),
                    'next_run_at' => $this->calcNextRun($sr->frequency),
                ]);
                $results['sent']++;
            } catch (\Exception $e) {
                $results['failed']++;
                \Log::error("Scheduled report failed [{$sr->id}]: {$e->getMessage()}");
            }
        }

        return $results;
    }

    protected function calcNextRun(string $frequency): \DateTime
    {
        return match ($frequency) {
            'daily' => now()->addDay()->setTime(7, 0),
            'weekly' => now()->next('Monday')->setTime(7, 0),
            'monthly' => now()->addMonth()->startOfMonth()->setTime(7, 0),
            default => now()->addDay(),
        };
    }
}
