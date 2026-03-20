<?php

// =====================================================
// FILE: app/Http/Controllers/Api/V1/ReportController.php
// =====================================================

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Reports\ReportingService;
use App\Services\Reports\ScheduledReportsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function __construct(
        protected ReportingService $reports,
        protected ScheduledReportsService $scheduled,
    ) {}

    // GET /reports/types — available report types
    public function types(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => ReportingService::getAvailableReports()]);
    }

    // POST /reports/generate — generate report on-demand
    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string',
            'format' => 'required|in:pdf,xlsx,json',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $result = $this->reports->generate(
            $request->input('type'),
            $request->input('format'),
            $request->except(['type', 'format'])
        );

        return response()->json(['success' => true, 'data' => $result]);
    }

    // GET /reports/download — download generated file
    public function download(Request $request)
    {
        $path = $request->input('path');
        if (!$path || !Storage::disk('local')->exists($path)) {
            return response()->json(['success' => false, 'message' => 'File not found.'], 404);
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $mime = match ($ext) {
            'pdf' => 'application/pdf',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            default => 'application/octet-stream',
        };

        return Storage::disk('local')->download($path, basename($path), ['Content-Type' => $mime]);
    }

    // GET /reports/history — generated reports history
    public function history(Request $request): JsonResponse
    {
        $reports = \App\Models\GeneratedReport::orderByDesc('created_at')
            ->limit((int) $request->get('limit', 30))
            ->get();

        return response()->json(['success' => true, 'data' => $reports]);
    }

    // GET /reports/scheduled — list scheduled reports
    public function scheduledList(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->scheduled->list()]);
    }

    // POST /reports/scheduled — create scheduled report
    public function scheduledCreate(Request $request): JsonResponse
    {
        $request->validate([
            'report_type' => 'required|string',
            'format' => 'required|in:pdf,xlsx',
            'frequency' => 'required|in:daily,weekly,monthly',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
        ]);

        $scheduled = $this->scheduled->create($request->all());
        return response()->json(['success' => true, 'data' => $scheduled], 201);
    }

    // DELETE /reports/scheduled/{id}
    public function scheduledDelete(int $id): JsonResponse
    {
        $this->scheduled->delete($id);
        return response()->json(['success' => true, 'message' => 'Scheduled report deleted.']);
    }

    // POST /reports/quick/{type} — quick JSON data without file
    public function quick(string $type, Request $request): JsonResponse
    {
        $result = $this->reports->generate($type, 'json', $request->all());
        return response()->json(['success' => true, 'data' => $result['data']]);
    }
}
// Extracted: ScheduledReportsService → app/Services/Reports/
// Extracted: ScheduledReport, GeneratedReport → app/Models/
