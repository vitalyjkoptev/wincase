<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use App\Models\JobParserSource;
use App\Models\ParsedJob;
use App\Models\Seeker;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JobsPortalController extends Controller
{
    // =====================================================
    // DASHBOARD STATS
    // =====================================================

    public function dashboard(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'vacancies' => [
                    'total' => Vacancy::count(),
                    'active' => Vacancy::where('status', 'active')->count(),
                    'draft' => Vacancy::where('status', 'draft')->count(),
                    'closed' => Vacancy::where('status', 'closed')->count(),
                ],
                'employers' => [
                    'total' => Employer::count(),
                    'active' => Employer::where('status', 'active')->count(),
                    'pending' => Employer::where('status', 'pending')->count(),
                ],
                'seekers' => [
                    'total' => Seeker::count(),
                    'active' => Seeker::where('status', 'active')->count(),
                    'pending' => Seeker::where('status', 'pending')->count(),
                ],
                'parsed_jobs' => [
                    'total' => ParsedJob::count(),
                    'new' => ParsedJob::where('status', 'new')->count(),
                    'approved' => ParsedJob::where('status', 'approved')->count(),
                    'published' => ParsedJob::where('status', 'published')->count(),
                ],
            ],
        ]);
    }

    // =====================================================
    // VACANCIES (LISTINGS)
    // =====================================================

    public function vacancies(Request $request): JsonResponse
    {
        $query = Vacancy::with('employer:id,company_name,city');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%")
                  ->orWhere('category', 'like', "%{$s}%");
            });
        }

        $paginated = $query->orderByDesc('created_at')
            ->paginate((int) $request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $paginated->items(),
                'meta' => [
                    'total' => $paginated->total(),
                    'per_page' => $paginated->perPage(),
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                ],
            ],
        ]);
    }

    public function vacancyShow(int $id): JsonResponse
    {
        $vacancy = Vacancy::with('employer')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $vacancy,
        ]);
    }

    public function vacancyStore(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
        ]);

        $vacancy = Vacancy::create($request->all());

        return response()->json(['success' => true, 'data' => $vacancy], 201);
    }

    public function vacancyUpdate(Request $request, int $id): JsonResponse
    {
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->update($request->all());

        return response()->json(['success' => true, 'data' => $vacancy->fresh()]);
    }

    public function vacancyDestroy(int $id): JsonResponse
    {
        Vacancy::findOrFail($id)->update(['status' => 'archived']);

        return response()->json(['success' => true, 'message' => 'Vacancy archived']);
    }

    // =====================================================
    // EMPLOYERS
    // =====================================================

    public function employers(Request $request): JsonResponse
    {
        $query = Employer::withCount('vacancies');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('company_name', 'like', "%{$s}%")
                  ->orWhere('contact_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $paginated = $query->orderByDesc('created_at')
            ->paginate((int) $request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $paginated->items(),
                'meta' => [
                    'total' => $paginated->total(),
                    'per_page' => $paginated->perPage(),
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                ],
            ],
        ]);
    }

    public function employerShow(int $id): JsonResponse
    {
        $employer = Employer::with(['vacancies' => fn ($q) => $q->latest()->limit(10)])
            ->withCount('vacancies')
            ->findOrFail($id);

        return response()->json(['success' => true, 'data' => $employer]);
    }

    public function employerUpdate(Request $request, int $id): JsonResponse
    {
        $employer = Employer::findOrFail($id);
        $employer->update($request->only([
            'company_name', 'nip', 'contact_name', 'email', 'phone',
            'city', 'industry', 'website', 'description', 'status',
        ]));

        return response()->json(['success' => true, 'data' => $employer->fresh()]);
    }

    // =====================================================
    // SEEKERS (CANDIDATES)
    // =====================================================

    public function seekers(Request $request): JsonResponse
    {
        $query = Seeker::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $paginated = $query->orderByDesc('created_at')
            ->paginate((int) $request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $paginated->items(),
                'meta' => [
                    'total' => $paginated->total(),
                    'per_page' => $paginated->perPage(),
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                ],
            ],
        ]);
    }

    public function seekerShow(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Seeker::findOrFail($id),
        ]);
    }

    public function seekerUpdate(Request $request, int $id): JsonResponse
    {
        $seeker = Seeker::findOrFail($id);
        $seeker->update($request->all());

        return response()->json(['success' => true, 'data' => $seeker->fresh()]);
    }

    // =====================================================
    // PARSED JOBS (from n8n)
    // =====================================================

    public function parsedJobs(Request $request): JsonResponse
    {
        $query = ParsedJob::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $paginated = $query->orderByDesc('created_at')
            ->paginate((int) $request->get('per_page', 25));

        return response()->json([
            'success' => true,
            'data' => [
                'data' => $paginated->items(),
                'meta' => [
                    'total' => $paginated->total(),
                    'per_page' => $paginated->perPage(),
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                ],
            ],
        ]);
    }

    public function parsedJobApprove(int $id): JsonResponse
    {
        $parsed = ParsedJob::findOrFail($id);

        // Create vacancy from parsed job
        $vacancy = Vacancy::create([
            'title' => $parsed->title,
            'city' => $parsed->city,
            'description' => $parsed->description,
            'source' => $parsed->source,
            'source_url' => $parsed->source_url,
            'salary_from' => null,
            'salary_to' => null,
            'status' => 'active',
        ]);

        $parsed->update(['status' => 'published', 'vacancy_id' => $vacancy->id]);

        return response()->json(['success' => true, 'data' => $vacancy]);
    }

    public function parsedJobReject(int $id): JsonResponse
    {
        ParsedJob::findOrFail($id)->update(['status' => 'rejected']);

        return response()->json(['success' => true, 'message' => 'Rejected']);
    }

    // =====================================================
    // PARSER SOURCES
    // =====================================================

    public function parserSources(Request $request): JsonResponse
    {
        $query = JobParserSource::query();

        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }

        return response()->json([
            'success' => true,
            'data' => $query->orderBy('name')->get(),
        ]);
    }

    public function parserSourceShow(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => JobParserSource::findOrFail($id),
        ]);
    }

    public function parserSourceStore(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'domain' => 'required|string|max:255|unique:job_parser_sources,domain',
            'parse_url' => 'required|url|max:500',
        ]);

        $source = JobParserSource::create($request->all());

        return response()->json(['success' => true, 'data' => $source], 201);
    }

    public function parserSourceUpdate(Request $request, int $id): JsonResponse
    {
        $source = JobParserSource::findOrFail($id);
        $source->update($request->only([
            'name', 'domain', 'parse_url', 'parse_type', 'selectors',
            'category', 'is_active',
        ]));

        return response()->json(['success' => true, 'data' => $source->fresh()]);
    }

    public function parserSourceDestroy(int $id): JsonResponse
    {
        JobParserSource::findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Source deleted']);
    }

    public function parserSourceToggle(int $id): JsonResponse
    {
        $source = JobParserSource::findOrFail($id);
        $source->update(['is_active' => !$source->is_active]);

        return response()->json([
            'success' => true,
            'data' => $source->fresh(),
            'message' => $source->is_active ? 'Activated' : 'Deactivated',
        ]);
    }

    public function parserRun(Request $request): JsonResponse
    {
        $sourceId = $request->input('source_id');
        $sources = $sourceId
            ? JobParserSource::where('id', $sourceId)->active()->get()
            : JobParserSource::active()->get();

        if ($sources->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No active sources']);
        }

        $results = [];
        foreach ($sources as $source) {
            try {
                $response = Http::timeout(30)
                    ->withUserAgent('WinCaseJobs Parser/1.0')
                    ->get($source->parse_url);

                if (!$response->successful()) {
                    $source->update(['last_error' => 'HTTP ' . $response->status()]);
                    $results[] = ['source' => $source->domain, 'status' => 'error', 'message' => 'HTTP ' . $response->status()];
                    continue;
                }

                $html = $response->body();
                $parsed = $this->parseHtml($html, $source);

                $newCount = 0;
                foreach ($parsed as $job) {
                    // Skip duplicates by source_url
                    if (empty($job['source_url'])) continue;
                    if (ParsedJob::where('source_url', $job['source_url'])->exists()) continue;

                    ParsedJob::create([
                        'source' => $source->domain,
                        'source_url' => $job['source_url'],
                        'title' => $job['title'] ?? 'Untitled',
                        'company' => $job['company'] ?? $source->name,
                        'city' => $job['city'] ?? null,
                        'salary' => $job['salary'] ?? null,
                        'description' => $job['description'] ?? null,
                        'parsed_data' => json_encode($job),
                        'status' => 'new',
                    ]);
                    $newCount++;
                }

                $source->update([
                    'last_parsed_at' => now(),
                    'total_parsed' => $source->total_parsed + $newCount,
                    'last_error' => null,
                ]);

                $results[] = ['source' => $source->domain, 'status' => 'ok', 'new_jobs' => $newCount, 'total_found' => count($parsed)];

            } catch (\Exception $e) {
                $source->update(['last_error' => $e->getMessage()]);
                $results[] = ['source' => $source->domain, 'status' => 'error', 'message' => $e->getMessage()];
            }
        }

        return response()->json(['success' => true, 'data' => $results]);
    }

    private function parseHtml(string $html, JobParserSource $source): array
    {
        $jobs = [];
        $selectors = $source->selectors ?? [];

        // Use DOMDocument for basic parsing
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NOERROR);
        $xpath = new \DOMXPath($doc);

        // Find all links with job-like patterns
        $links = $xpath->query('//a[contains(@href, "job") or contains(@href, "vacan") or contains(@href, "prac") or contains(@href, "ofert") or contains(@href, "work") or contains(@href, "zatrudn")]');

        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $title = trim($link->textContent);

            if (empty($title) || strlen($title) < 5 || strlen($title) > 300) continue;

            // Build absolute URL
            if (str_starts_with($href, '/')) {
                $href = rtrim($source->parse_url, '/') . $href;
            } elseif (!str_starts_with($href, 'http')) {
                $href = rtrim($source->parse_url, '/') . '/' . $href;
            }

            // Skip nav/footer links
            if (preg_match('/(login|register|signup|contact|about|priv|terms|cookie)/i', $href)) continue;

            $jobs[] = [
                'title' => $title,
                'source_url' => $href,
                'company' => $source->name,
            ];
        }

        // Also scan for article/h2/h3 headings that might be job posts
        $articles = $xpath->query('//article | //div[contains(@class,"job")] | //div[contains(@class,"vacan")] | //div[contains(@class,"offer")]');
        foreach ($articles as $article) {
            $headings = $xpath->query('.//h2 | .//h3', $article);
            $linkNodes = $xpath->query('.//a[contains(@href, "http")]', $article);

            if ($headings->length > 0) {
                $title = trim($headings->item(0)->textContent);
                $href = $linkNodes->length > 0 ? $linkNodes->item(0)->getAttribute('href') : '';

                if (empty($title) || strlen($title) < 5) continue;
                if (empty($href)) continue;

                // Check for duplicate
                $exists = false;
                foreach ($jobs as $j) {
                    if ($j['source_url'] === $href) { $exists = true; break; }
                }
                if ($exists) continue;

                // Try to find city/salary in article text
                $text = $article->textContent;
                $city = null;
                $salary = null;

                // Polish cities
                if (preg_match('/(Warszawa|Kraków|Gdańsk|Wrocław|Poznań|Łódź|Katowice|Lublin|Szczecin|Rzeszów|Bydgoszcz|Białystok|Warsaw|Krakow|Gdansk)/i', $text, $m)) {
                    $city = $m[1];
                }
                // Salary pattern
                if (preg_match('/(\d[\d\s]*(?:PLN|zł|EUR|USD))/i', $text, $m)) {
                    $salary = trim($m[1]);
                }

                $jobs[] = [
                    'title' => $title,
                    'source_url' => $href,
                    'company' => $source->name,
                    'city' => $city,
                    'salary' => $salary,
                ];
            }
        }

        return $jobs;
    }
}
