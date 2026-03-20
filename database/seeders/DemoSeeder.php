<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * DemoSeeder — Realistic data for WinCase CRM Immigration Bureau.
 * Clients: Ukrainian, Belarusian, Georgian, Indian, Bangladeshi, Vietnamese.
 * Cases: work_permit, temp_residence, permanent_residence, eu_blue_card, etc.
 * Run: php artisan db:seed --class=DemoSeeder
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Staff user IDs (must exist in users table)
        $bossIds = DB::table('users')->where('role', 'boss')->pluck('id')->toArray();
        $staffIds = DB::table('users')->where('role', 'staff')->pluck('id')->toArray();
        $allStaff = array_merge($bossIds, $staffIds);
        if (empty($allStaff)) {
            $allStaff = [1];
        }

        // ============================================================
        // 1. CLIENTS (30)
        // ============================================================
        $clientNames = [
            ['Oleksandr', 'Kovalenko', 'UA', 'ukr'],
            ['Iryna', 'Shevchenko', 'UA', 'ukr'],
            ['Dmytro', 'Bondarenko', 'UA', 'ukr'],
            ['Tetiana', 'Melnyk', 'UA', 'ukr'],
            ['Andrii', 'Tkachenko', 'UA', 'ukr'],
            ['Olena', 'Kravchenko', 'UA', 'ukr'],
            ['Viktor', 'Sydorenko', 'UA', 'ukr'],
            ['Nataliia', 'Lysenko', 'UA', 'ukr'],
            ['Sergii', 'Moroz', 'UA', 'ukr'],
            ['Yuliia', 'Polishchuk', 'UA', 'ukr'],
            ['Pavel', 'Kuznetsov', 'BY', 'rus'],
            ['Aliaksandr', 'Ivanov', 'BY', 'rus'],
            ['Katsiaryna', 'Novikova', 'BY', 'rus'],
            ['Giorgi', 'Beridze', 'GE', 'geo'],
            ['Nino', 'Kapanadze', 'GE', 'geo'],
            ['Rajesh', 'Sharma', 'IN', 'eng'],
            ['Priya', 'Patel', 'IN', 'eng'],
            ['Amit', 'Kumar', 'IN', 'eng'],
            ['Mohammed', 'Rahman', 'BD', 'ben'],
            ['Fatima', 'Akhter', 'BD', 'ben'],
            ['Nguyen', 'Van Minh', 'VN', 'vie'],
            ['Tran', 'Thi Lan', 'VN', 'vie'],
            ['Carlos', 'Silva', 'BR', 'por'],
            ['Maria', 'Santos', 'BR', 'por'],
            ['Artem', 'Volkov', 'RU', 'rus'],
            ['Li', 'Wei', 'CN', 'zho'],
            ['Emre', 'Yilmaz', 'TR', 'tur'],
            ['Oksana', 'Hrytsenko', 'UA', 'ukr'],
            ['Mikhail', 'Petrov', 'BY', 'rus'],
            ['Deepak', 'Singh', 'IN', 'eng'],
        ];

        $clientStatuses = ['active', 'active', 'active', 'active', 'active', 'active', 'active', 'archived', 'archived', 'active'];
        $voivodeships = ['mazowieckie', 'malopolskie', 'dolnoslaskie', 'wielkopolskie', 'slaskie', 'pomorskie', 'lodzkie', 'lubelskie'];
        $cities = ['Warszawa', 'Krakow', 'Wroclaw', 'Poznan', 'Katowice', 'Gdansk', 'Lodz', 'Lublin'];

        $clientIds = [];
        foreach ($clientNames as $i => $c) {
            $createdAt = $now->copy()->subDays(rand(10, 180));
            $clientIds[] = DB::table('clients')->insertGetId([
                'first_name' => $c[0],
                'last_name' => $c[1],
                'name' => $c[0] . ' ' . $c[1],
                'email' => strtolower($c[0]) . '.' . strtolower($c[1]) . '@gmail.com',
                'phone' => '+48 ' . rand(500, 799) . ' ' . rand(100, 999) . ' ' . rand(100, 999),
                'nationality' => $c[2],
                'preferred_language' => $c[3],
                'pesel' => $c[2] === 'UA' ? (string)rand(10000000000, 99999999999) : null,
                'passport_number' => strtoupper(substr($c[2], 0, 2)) . rand(100000, 999999),
                'passport_expiry' => $now->copy()->addMonths(rand(6, 48))->format('Y-m-d'),
                'date_of_birth' => Carbon::create(rand(1975, 2000), rand(1, 12), rand(1, 28))->format('Y-m-d'),
                'address' => 'ul. ' . ['Marszalkowska', 'Nowy Swiat', 'Pulawska', 'Grzybowska', 'Zelazna', 'Prosta', 'Towarowa', 'Chmielna'][rand(0, 7)] . ' ' . rand(1, 120),
                'city' => $cities[$i % count($cities)],
                'postal_code' => sprintf('%02d-%03d', rand(0, 99), rand(1, 999)),
                'country' => 'PL',
                'voivodeship' => $voivodeships[$i % count($voivodeships)],
                'status' => $clientStatuses[$i % count($clientStatuses)],
                'assigned_to' => $allStaff[array_rand($allStaff)],
                'gdpr_consent' => true,
                'notes' => null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // ============================================================
        // 2. LEADS (50)
        // ============================================================
        $leadSources = ['website', 'facebook', 'google_ads', 'referral', 'phone', 'walk_in', 'instagram', 'tiktok', 'linkedin', 'partner'];
        $leadStatuses = ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost', 'new', 'contacted', 'qualified'];
        $serviceTypes = ['work_permit', 'temp_residence', 'permanent_residence', 'eu_blue_card', 'family_reunification', 'citizenship', 'business_registration', 'visa', 'karta_pobytu_exchange', 'karta_polaka'];
        $leadPriorities = ['low', 'medium', 'medium', 'high', 'high', 'medium', 'low', 'urgent', 'medium', 'high'];

        $leadFirstNames = ['Oleksii', 'Katya', 'Bogdan', 'Svitlana', 'Roman', 'Daria', 'Artem', 'Inna', 'Maksym', 'Valentyna', 'Pavel', 'Elena', 'Davit', 'Tamar', 'Ravi', 'Sunita', 'Abdul', 'Nasreen', 'Hung', 'Linh', 'Marco', 'Ana', 'Ivan', 'Oleg', 'Serhii', 'Hanna', 'Volodymyr', 'Larysa', 'Taras', 'Mykola', 'Zhanna', 'Ruslan', 'Yana', 'Petro', 'Sofia', 'Kostiantyn', 'Liudmyla', 'Dmytro', 'Alla', 'Vasyl', 'Nadiya', 'Yevhen', 'Maryna', 'Anton', 'Oksana', 'Denis', 'Vira', 'Zakhar', 'Tamara', 'Bohdan'];
        $leadLastNames = ['Boyko', 'Savchenko', 'Rudenko', 'Koval', 'Marchenko', 'Tkach', 'Zhuk', 'Didenko', 'Ostapenko', 'Havryliuk', 'Novak', 'Kozlov', 'Lomidze', 'Gelashvili', 'Agarwal', 'Mishra', 'Hossain', 'Begum', 'Tran', 'Le', 'Rossi', 'Fernandes', 'Popov', 'Sokolov', 'Fedorov', 'Smirnova', 'Zaitsev', 'Romanova', 'Kuzmin', 'Grigoriev', 'Pavlova', 'Makarov', 'Stepanova', 'Nikolaev', 'Egorova', 'Andreev', 'Petrova', 'Ivanchuk', 'Mikhailova', 'Baranov', 'Grigorieva', 'Kulik', 'Yakovleva', 'Belov', 'Zinchenko', 'Frolov', 'Guseva', 'Vlasov', 'Koroleva', 'Litvin'];
        $nationalities = ['UA', 'UA', 'UA', 'UA', 'UA', 'UA', 'BY', 'BY', 'GE', 'GE', 'IN', 'IN', 'BD', 'BD', 'VN', 'VN', 'BR', 'RU', 'UA', 'UA'];

        $leadIds = [];
        for ($i = 0; $i < 50; $i++) {
            $createdAt = $now->copy()->subDays(rand(1, 120));
            $source = $leadSources[array_rand($leadSources)];
            $status = $leadStatuses[array_rand($leadStatuses)];
            $cid = ($status === 'won' && !empty($clientIds)) ? $clientIds[array_rand($clientIds)] : null;
            $leadIds[] = DB::table('leads')->insertGetId([
                'first_name' => $leadFirstNames[$i],
                'last_name' => $leadLastNames[$i],
                'name' => $leadFirstNames[$i] . ' ' . $leadLastNames[$i],
                'email' => strtolower($leadFirstNames[$i]) . '.' . strtolower($leadLastNames[$i]) . '@gmail.com',
                'phone' => '+48 ' . rand(500, 799) . ' ' . rand(100, 999) . ' ' . rand(100, 999),
                'source' => $source,
                'status' => $status,
                'priority' => $leadPriorities[array_rand($leadPriorities)],
                'service_type' => $serviceTypes[array_rand($serviceTypes)],
                'nationality' => $nationalities[$i % count($nationalities)],
                'notes' => $i % 3 === 0 ? 'Interested in quick processing. Deadline approaching.' : null,
                'message' => $i % 4 === 0 ? 'I need help with legalization in Poland. Please contact me.' : null,
                'assigned_to' => $allStaff[array_rand($allStaff)],
                'client_id' => $cid,
                'estimated_value' => rand(1, 8) * 500,
                'first_contact_at' => $createdAt->copy()->addHours(rand(1, 48)),
                'utm_source' => in_array($source, ['google_ads', 'facebook']) ? $source : null,
                'utm_medium' => in_array($source, ['google_ads', 'facebook']) ? 'cpc' : null,
                'utm_campaign' => $source === 'google_ads' ? ['legalization_pl', 'work_permit_2026', 'karta_pobytu'][rand(0, 2)] : null,
                'landing_page' => $i % 5 === 0 ? 'https://wincase.eu/legalization' : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // ============================================================
        // 3. CASES (25)
        // ============================================================
        $caseStatuses = ['new', 'documents_collecting', 'documents_review', 'submission_preparation', 'submitted_to_office', 'waiting_for_decision', 'additional_documents_requested', 'positive_decision', 'negative_decision', 'positive_decision'];
        $caseServiceTypes = ['work_permit', 'temp_residence', 'temp_residence', 'permanent_residence', 'eu_blue_card', 'family_reunification', 'citizenship', 'work_permit', 'karta_pobytu_exchange', 'temp_residence'];
        $offices = ['Mazowiecki Urzad Wojewodzki', 'Malopolski Urzad Wojewodzki', 'Dolnoslaski Urzad Wojewodzki', 'Urzad do Spraw Cudzoziemcow'];
        $casePriorities = ['low', 'medium', 'medium', 'high', 'urgent'];

        $caseIds = [];
        for ($i = 0; $i < 25; $i++) {
            $cIdx = $i % count($clientIds);
            $createdAt = $now->copy()->subDays(rand(5, 150));
            $status = $caseStatuses[$i % count($caseStatuses)];
            $progress = match ($status) {
                'new' => rand(0, 5),
                'documents_collecting' => rand(10, 30),
                'documents_review' => rand(30, 50),
                'submission_preparation' => rand(50, 65),
                'submitted_to_office' => rand(65, 75),
                'waiting_for_decision' => rand(75, 85),
                'additional_documents_requested' => rand(60, 70),
                'positive_decision' => 100,
                'negative_decision' => 100,
                default => 0,
            };
            $fee = rand(2, 10) * 500;
            $isPaid = in_array($status, ['submitted_to_office', 'waiting_for_decision', 'positive_decision']) ? 1 : ($i % 3 === 0 ? 1 : 0);

            $caseIds[] = DB::table('cases')->insertGetId([
                'case_number' => sprintf('WC-%d-%04d', $now->year, $i + 1),
                'client_id' => $clientIds[$cIdx],
                'assigned_to' => $allStaff[array_rand($allStaff)],
                'service_type' => $caseServiceTypes[$i % count($caseServiceTypes)],
                'type' => $caseServiceTypes[$i % count($caseServiceTypes)],
                'status' => $status,
                'priority' => $casePriorities[array_rand($casePriorities)],
                'description' => null,
                'fee' => $fee,
                'amount' => $fee,
                'currency' => 'PLN',
                'is_paid' => $isPaid,
                'paid' => $isPaid,
                'voivodeship' => $voivodeships[array_rand($voivodeships)],
                'office_name' => $offices[array_rand($offices)],
                'submission_date' => in_array($status, ['submitted_to_office', 'waiting_for_decision', 'positive_decision', 'negative_decision']) ? $createdAt->copy()->addDays(rand(10, 40))->format('Y-m-d') : null,
                'decision_date' => in_array($status, ['positive_decision', 'negative_decision']) ? $createdAt->copy()->addDays(rand(60, 120))->format('Y-m-d') : null,
                'deadline' => $createdAt->copy()->addDays(rand(30, 180))->format('Y-m-d'),
                'progress_percentage' => $progress,
                'notes' => $i % 4 === 0 ? 'Client responsive, documents mostly complete.' : null,
                'created_at' => $createdAt,
                'updated_at' => $now->copy()->subDays(rand(0, 10)),
            ]);
        }

        // ============================================================
        // 4. TASKS (80)
        // ============================================================
        $taskTypes = ['call', 'meeting', 'document', 'deadline', 'follow_up', 'other'];
        $taskStatuses = ['pending', 'in_progress', 'completed', 'completed', 'pending', 'in_progress', 'completed', 'pending'];
        $taskTitles = [
            'call' => ['Call client to confirm documents', 'Follow-up call about missing passport copy', 'Call Urzad for status update', 'Reminder call about payment'],
            'meeting' => ['Client meeting at office', 'Consultation with new client', 'Document review meeting', 'Pre-submission briefing'],
            'document' => ['Prepare application form', 'Translate documents to Polish', 'Verify passport copies', 'Collect employer certificate', 'Prepare power of attorney', 'Request apostille'],
            'deadline' => ['Submission deadline approaching', 'Appeal deadline', 'Document expiry check', 'Payment due date'],
            'follow_up' => ['Follow up on submitted case', 'Check decision status at Urzad', 'Verify client satisfaction', 'Send invoice reminder'],
            'other' => ['Update CRM records', 'Archive completed case', 'Internal review', 'Prepare monthly report'],
        ];

        for ($i = 0; $i < 80; $i++) {
            $type = $taskTypes[array_rand($taskTypes)];
            $titles = $taskTitles[$type];
            $status = $taskStatuses[array_rand($taskStatuses)];
            $createdAt = $now->copy()->subDays(rand(0, 60));
            $dueDate = $createdAt->copy()->addDays(rand(1, 14));

            DB::table('tasks')->insert([
                'title' => $titles[array_rand($titles)],
                'description' => $i % 5 === 0 ? 'Priority task, needs attention this week.' : null,
                'type' => $type,
                'assigned_to' => $allStaff[array_rand($allStaff)],
                'created_by' => $bossIds[array_rand($bossIds)] ?? $allStaff[0],
                'case_id' => !empty($caseIds) && $i % 2 === 0 ? $caseIds[array_rand($caseIds)] : null,
                'client_id' => $i % 3 === 0 ? $clientIds[array_rand($clientIds)] : null,
                'lead_id' => $i % 7 === 0 ? $leadIds[array_rand($leadIds)] : null,
                'status' => $status,
                'priority' => ['low', 'medium', 'medium', 'high', 'urgent'][rand(0, 4)],
                'due_date' => $dueDate,
                'completed_at' => $status === 'completed' ? $dueDate->copy()->subDays(rand(0, 3)) : null,
                'reminder_at' => $i % 4 === 0 ? $dueDate->copy()->subHours(rand(1, 24)) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // ============================================================
        // 5. INVOICES (15)
        // ============================================================
        $invoiceIds = [];
        for ($i = 0; $i < 15; $i++) {
            $createdAt = $now->copy()->subDays(rand(5, 90));
            $net = rand(2, 10) * 500;
            $vat = round($net * 0.23, 2);
            $gross = $net + $vat;
            $status = ['draft', 'sent', 'paid', 'paid', 'paid', 'overdue', 'sent', 'paid'][rand(0, 7)];

            $invoiceIds[] = DB::table('invoices')->insertGetId([
                'invoice_number' => sprintf('FV/%d/%02d/%03d', $now->year, $createdAt->month, $i + 1),
                'client_id' => $clientIds[array_rand($clientIds)],
                'case_id' => !empty($caseIds) ? $caseIds[array_rand($caseIds)] : null,
                'net_amount' => $net,
                'vat_rate' => 23,
                'vat_amount' => $vat,
                'gross_amount' => $gross,
                'amount' => $gross,
                'total' => $gross,
                'total_amount' => $gross,
                'tax_rate' => 23,
                'tax_amount' => $vat,
                'currency' => 'PLN',
                'status' => $status,
                'issue_date' => $createdAt->format('Y-m-d'),
                'due_date' => $createdAt->copy()->addDays(14)->format('Y-m-d'),
                'paid_date' => $status === 'paid' ? $createdAt->copy()->addDays(rand(1, 14))->format('Y-m-d') : null,
                'payment_method' => $status === 'paid' ? ['transfer', 'cash', 'card'][rand(0, 2)] : null,
                'bank_account' => 'PL61 1090 1014 0000 0712 1981 2874',
                'notes' => null,
                'created_by' => $bossIds[array_rand($bossIds)] ?? $allStaff[0],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        // ============================================================
        // 6. PAYMENTS (12)
        // ============================================================
        $paidInvoices = DB::table('invoices')->where('status', 'paid')->pluck('id')->toArray();
        for ($i = 0; $i < min(12, count($paidInvoices)); $i++) {
            $inv = DB::table('invoices')->find($paidInvoices[$i]);
            DB::table('payments')->insert([
                'invoice_id' => $inv->id,
                'client_id' => $inv->client_id,
                'case_id' => $inv->case_id,
                'amount' => $inv->gross_amount,
                'currency' => 'PLN',
                'method' => ['transfer', 'cash', 'card'][rand(0, 2)],
                'payment_method' => ['transfer', 'cash', 'card'][rand(0, 2)],
                'status' => 'completed',
                'reference' => 'PAY-' . strtoupper(Str::random(8)),
                'paid_date' => $inv->paid_date,
                'notes' => null,
                'created_at' => $inv->paid_date ?? $now,
                'updated_at' => $inv->paid_date ?? $now,
            ]);
        }

        // ============================================================
        // 7. CALENDAR EVENTS (20)
        // ============================================================
        $eventTypes = ['meeting', 'hearing', 'deadline', 'call', 'consultation', 'internal'];
        $eventColors = ['#015EA7', '#28a745', '#dc3545', '#ffc107', '#6f42c1', '#20c997'];
        for ($i = 0; $i < 20; $i++) {
            $type = $eventTypes[$i % count($eventTypes)];
            $startAt = $now->copy()->addDays(rand(-30, 30))->setHour(rand(8, 17))->setMinute([0, 15, 30, 45][rand(0, 3)]);
            DB::table('calendar_events')->insert([
                'title' => match ($type) {
                    'meeting' => 'Meeting with ' . $clientNames[array_rand($clientNames)][0],
                    'hearing' => 'Hearing at ' . $offices[array_rand($offices)],
                    'deadline' => 'Deadline: ' . ['Document submission', 'Appeal', 'Payment due', 'Visa expiry'][rand(0, 3)],
                    'call' => 'Call: ' . $clientNames[array_rand($clientNames)][0] . ' ' . $clientNames[array_rand($clientNames)][1],
                    'consultation' => 'Consultation — new client',
                    'internal' => 'Team meeting',
                },
                'description' => null,
                'start_at' => $startAt,
                'end_at' => $startAt->copy()->addMinutes([30, 60, 60, 90, 120][rand(0, 4)]),
                'all_day' => 0,
                'is_all_day' => 0,
                'type' => $type,
                'event_type' => $type,
                'color' => $eventColors[$i % count($eventColors)],
                'user_id' => $allStaff[array_rand($allStaff)],
                'assigned_to' => $allStaff[array_rand($allStaff)],
                'case_id' => $i % 3 === 0 && !empty($caseIds) ? $caseIds[array_rand($caseIds)] : null,
                'client_id' => $i % 2 === 0 ? $clientIds[array_rand($clientIds)] : null,
                'location' => $type === 'hearing' ? $offices[array_rand($offices)] . ', Warszawa' : ($type === 'meeting' ? 'ul. Hoza 66/68, Warszawa' : null),
                'reminder_minutes' => [15, 30, 60, 120, null][rand(0, 4)],
                'created_at' => $now->copy()->subDays(rand(1, 30)),
                'updated_at' => $now,
            ]);
        }

        // ============================================================
        // 8. HEARINGS (10)
        // ============================================================
        $hearingTypes = ['interview', 'appeal_hearing', 'document_submission', 'oath', 'other'];
        $hearingStatuses = ['scheduled', 'completed', 'postponed', 'scheduled', 'completed', 'scheduled'];
        for ($i = 0; $i < 10; $i++) {
            DB::table('hearings')->insert([
                'case_id' => $caseIds[array_rand($caseIds)],
                'hearing_date' => $now->copy()->addDays(rand(-30, 60))->setHour(rand(9, 15))->setMinute(0),
                'location' => $offices[array_rand($offices)] . ', pokoj ' . rand(100, 400),
                'type' => $hearingTypes[array_rand($hearingTypes)],
                'status' => $hearingStatuses[array_rand($hearingStatuses)],
                'notes' => $i % 3 === 0 ? 'Bring all original documents and translations.' : null,
                'result' => $i % 4 === 0 ? 'Documents accepted, waiting for decision.' : null,
                'created_at' => $now->copy()->subDays(rand(5, 60)),
                'updated_at' => $now,
            ]);
        }

        // ============================================================
        // 9. DOCUMENTS (40)
        // ============================================================
        $docTypes = ['passport', 'visa', 'work_permit', 'residence_card', 'employment_contract', 'bank_statement', 'insurance', 'photo', 'application_form', 'power_of_attorney', 'translation', 'pesel_confirmation', 'zameldowanie'];
        $docStatuses = ['pending', 'verified', 'verified', 'verified', 'rejected', 'pending', 'verified'];
        for ($i = 0; $i < 40; $i++) {
            $docType = $docTypes[array_rand($docTypes)];
            $cId = $clientIds[array_rand($clientIds)];
            DB::table('documents')->insert([
                'documentable_type' => 'client',
                'documentable_id' => $cId,
                'client_id' => $cId,
                'case_id' => $i % 2 === 0 && !empty($caseIds) ? $caseIds[array_rand($caseIds)] : null,
                'name' => ucfirst(str_replace('_', ' ', $docType)),
                'type' => $docType,
                'original_name' => strtolower($docType) . '_' . Str::random(6) . '.pdf',
                'file_path' => 'vault/' . Str::random(16) . '/' . $docType . '/' . Str::random(20) . '.enc',
                'file_size' => rand(50000, 5000000),
                'mime_type' => 'application/pdf',
                'status' => $docStatuses[array_rand($docStatuses)],
                'expiry_date' => in_array($docType, ['passport', 'visa', 'work_permit', 'residence_card', 'insurance']) ? $now->copy()->addMonths(rand(1, 36))->format('Y-m-d') : null,
                'uploaded_by' => $allStaff[array_rand($allStaff)],
                'verified_by' => rand(0, 1) ? $bossIds[array_rand($bossIds)] ?? null : null,
                'created_at' => $now->copy()->subDays(rand(1, 90)),
                'updated_at' => $now->copy()->subDays(rand(0, 10)),
            ]);
        }

        // ============================================================
        // 10. EXPENSES (20)
        // ============================================================
        $expCategories = ['rent', 'utilities', 'office_supplies', 'software', 'travel', 'legal', 'marketing', 'salary', 'tax', 'insurance', 'courier', 'translation', 'notary'];
        $vendors = ['Allegro', 'OVH', 'Hetzner', 'Google', 'Facebook', 'InPost', 'Poczta Polska', 'UNIQA', 'Notariusz Kowalski', 'Biuro tlumaczen ABC'];
        for ($i = 0; $i < 20; $i++) {
            $net = rand(50, 5000);
            $vat = round($net * 0.23, 2);
            DB::table('expenses')->insert([
                'category' => $expCategories[array_rand($expCategories)],
                'description' => 'Monthly expense #' . ($i + 1),
                'net_amount' => $net,
                'vat_amount' => $vat,
                'gross_amount' => $net + $vat,
                'date' => $now->copy()->subDays(rand(1, 90))->format('Y-m-d'),
                'vendor' => $vendors[array_rand($vendors)],
                'invoice_number' => 'EXP/' . $now->year . '/' . sprintf('%03d', $i + 1),
                'created_by' => $bossIds[array_rand($bossIds)] ?? $allStaff[0],
                'created_at' => $now->copy()->subDays(rand(1, 90)),
                'updated_at' => $now,
            ]);
        }

        // ============================================================
        // 11. POS TRANSACTIONS (15)
        // ============================================================
        for ($i = 0; $i < 15; $i++) {
            $posStatus = ['completed', 'completed', 'completed', 'pending', 'refunded'][rand(0, 4)];
            DB::table('pos_transactions')->insert([
                'client_id' => $clientIds[array_rand($clientIds)],
                'invoice_id' => !empty($invoiceIds) ? $invoiceIds[array_rand($invoiceIds)] : null,
                'received_by' => $allStaff[array_rand($allStaff)],
                'approved_by' => $posStatus === 'completed' ? ($bossIds[array_rand($bossIds)] ?? null) : null,
                'amount' => rand(500, 5000),
                'currency' => 'PLN',
                'payment_method' => ['cash', 'card', 'transfer'][rand(0, 2)],
                'status' => $posStatus,
                'receipt_number' => 'POS-' . strtoupper(Str::random(6)),
                'notes' => null,
                'created_at' => $now->copy()->subDays(rand(1, 60)),
                'updated_at' => $now,
            ]);
        }

        // ============================================================
        // 12. ADS PERFORMANCE (90 days x 3 platforms)
        // ============================================================
        $adsPlatforms = ['google_ads', 'meta', 'tiktok'];
        $campaigns = [
            'google_ads' => ['Legalization PL', 'Work Permit 2026', 'Karta Pobytu'],
            'meta' => ['FB Lead Gen', 'IG Stories Legalization', 'FB Retargeting'],
            'tiktok' => ['TikTok Immigration', 'TikTok Brand Awareness'],
        ];
        for ($day = 0; $day < 90; $day++) {
            $date = $now->copy()->subDays($day)->format('Y-m-d');
            foreach ($adsPlatforms as $platform) {
                $cs = $campaigns[$platform];
                $campaign = $cs[array_rand($cs)];
                $cost = round(rand(20, 200) + rand(0, 99) / 100, 2);
                $impressions = rand(500, 8000);
                $clicks = rand(10, (int)($impressions * 0.08));
                $leads = rand(0, max(1, (int)($clicks * 0.15)));
                DB::table('ads_performance')->insert([
                    'platform' => $platform,
                    'campaign_id' => strtoupper(Str::random(8)),
                    'campaign_name' => $campaign,
                    'date' => $date,
                    'cost' => $cost,
                    'impressions' => $impressions,
                    'clicks' => $clicks,
                    'leads_count' => $leads,
                    'cpc' => $clicks > 0 ? round($cost / $clicks, 2) : 0,
                    'cpl' => $leads > 0 ? round($cost / $leads, 2) : 0,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }

        // ============================================================
        // 13. SEO DATA (90 days x 4 domains)
        // ============================================================
        $seoDomains = ['wincase.eu', 'wincasejobs.com', 'legalizacja-polska.pl', 'karta-pobytu.info'];
        for ($day = 0; $day < 90; $day++) {
            $date = $now->copy()->subDays($day)->format('Y-m-d');
            foreach ($seoDomains as $domain) {
                $baseClicks = $domain === 'wincase.eu' ? 80 : ($domain === 'wincasejobs.com' ? 40 : rand(5, 20));
                DB::table('seo_data')->insert([
                    'source' => 'gsc',
                    'domain' => $domain,
                    'date' => $date,
                    'clicks' => $baseClicks + rand(-20, 30),
                    'impressions' => ($baseClicks * rand(15, 25)),
                    'avg_position' => round(rand(50, 250) / 10, 1),
                    'users' => $baseClicks + rand(10, 50),
                    'sessions' => $baseClicks + rand(15, 70),
                    'domain_authority' => $domain === 'wincase.eu' ? rand(25, 30) : rand(5, 15),
                    'backlinks' => $domain === 'wincase.eu' ? rand(200, 350) : rand(10, 80),
                    'referring_domains' => $domain === 'wincase.eu' ? rand(50, 80) : rand(5, 25),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }

        // ============================================================
        // 14. SOCIAL ACCOUNTS (6) + POSTS (30)
        // ============================================================
        $socialPlatforms = [
            ['facebook', 'WinCase Legalization', 1250],
            ['instagram', 'wincase.eu', 890],
            ['linkedin', 'WinCase Immigration', 420],
            ['tiktok', 'wincase_eu', 3200],
            ['youtube', 'WinCase', 150],
            ['telegram', 'wincase_eu', 560],
        ];

        $socialAccountIds = [];
        foreach ($socialPlatforms as $sp) {
            $socialAccountIds[] = DB::table('social_accounts')->insertGetId([
                'platform' => $sp[0],
                'account_id' => strtoupper(Str::random(12)),
                'account_name' => $sp[1],
                'followers' => $sp[2],
                'posts_count' => rand(20, 150),
                'access_token' => null,
                'token_expires_at' => null,
                'last_synced_at' => $now->copy()->subHours(rand(1, 48)),
                'created_at' => $now->copy()->subMonths(6),
                'updated_at' => $now,
            ]);
        }

        $postTexts = [
            'How to get a work permit in Poland in 2026',
            'Karta Pobytu step-by-step guide',
            'New immigration rules for 2026',
            'Our client success story: from visa to citizenship',
            'Top 5 mistakes in residence applications',
            'Free consultation this Friday',
            'Important deadline for temporary residence',
            'How to renew your work permit',
            'EU Blue Card: requirements update',
            'Client testimonial: WinCase made it easy',
        ];
        for ($i = 0; $i < 30; $i++) {
            $accId = $socialAccountIds[array_rand($socialAccountIds)];
            $acc = DB::table('social_accounts')->find($accId);
            $pubDate = $now->copy()->subDays(rand(1, 60));
            DB::table('social_posts')->insert([
                'social_account_id' => $accId,
                'platform' => $acc->platform,
                'post_id' => strtoupper(Str::random(16)),
                'text' => $postTexts[array_rand($postTexts)],
                'media_url' => null,
                'status' => ['published', 'published', 'published', 'scheduled', 'draft'][rand(0, 4)],
                'published_at' => $pubDate,
                'scheduled_at' => null,
                'created_at' => $pubDate,
                'updated_at' => $pubDate,
            ]);
        }

        // ============================================================
        // 15. REVIEWS (20)
        // ============================================================
        $reviewPlatforms = ['google', 'facebook', 'trustpilot'];
        $reviewTexts = [
            'Excellent service, very professional team.',
            'They helped me get my karta pobytu in record time.',
            'Highly recommended for immigration services in Poland.',
            'Professional and caring staff. Thank you WinCase.',
            'Fast processing, clear communication.',
            'Got my work permit renewed without any issues.',
            'Very knowledgeable about Polish immigration law.',
            'Good service but could be faster.',
            'Average experience, some delays.',
            'Outstanding support throughout the process.',
        ];
        for ($i = 0; $i < 20; $i++) {
            DB::table('reviews')->insert([
                'client_id' => $clientIds[array_rand($clientIds)],
                'platform' => $reviewPlatforms[array_rand($reviewPlatforms)],
                'external_id' => strtoupper(Str::random(12)),
                'rating' => [3, 4, 4, 5, 5, 5, 5, 4, 5, 4][rand(0, 9)],
                'text' => $reviewTexts[array_rand($reviewTexts)],
                'author_name' => $clientNames[array_rand($clientNames)][0] . ' ' . substr($clientNames[array_rand($clientNames)][1], 0, 1) . '.',
                'status' => 'published',
                'reply' => $i % 3 === 0 ? 'Thank you for your kind words! We appreciate your trust.' : null,
                'reviewed_at' => $now->copy()->subDays(rand(1, 120)),
                'created_at' => $now->copy()->subDays(rand(1, 120)),
                'updated_at' => $now,
            ]);
        }

        // ============================================================
        // 16. BRAND LISTINGS (8)
        // ============================================================
        $brandPlatforms = ['google_business', 'facebook', 'trustpilot', 'yelp', 'yellow_pages', 'pkt_pl', 'panorama_firm', 'gowork'];
        foreach ($brandPlatforms as $bp) {
            DB::table('brand_listings')->insert([
                'platform' => $bp,
                'listing_url' => 'https://' . str_replace('_', '.', $bp) . '.com/wincase',
                'status' => ['active', 'active', 'active', 'pending', 'active'][rand(0, 4)],
                'rating' => round(rand(38, 50) / 10, 1),
                'reviews_count' => rand(5, 120),
                'is_claimed' => in_array($bp, ['google_business', 'facebook', 'trustpilot']) ? 1 : 0,
                'last_checked_at' => $now->copy()->subHours(rand(1, 72)),
                'created_at' => $now->copy()->subMonths(3),
                'updated_at' => $now,
            ]);
        }

        // ============================================================
        // 17. STAFF MESSAGES (15)
        // ============================================================
        $msgTexts = [
            'New client registered, please review documents.',
            'Case WC-2026-0005 requires additional documents.',
            'Client called about payment status.',
            'Hearing scheduled for next Wednesday.',
            'Please update the deadline in the system.',
            'Invoice FV/2026/03/001 has been paid.',
            'New lead from Google Ads, high priority.',
            'Team meeting moved to 15:00.',
        ];
        for ($i = 0; $i < 15; $i++) {
            $from = $allStaff[array_rand($allStaff)];
            $to = $allStaff[array_rand($allStaff)];
            while ($to === $from && count($allStaff) > 1) {
                $to = $allStaff[array_rand($allStaff)];
            }
            DB::table('staff_messages')->insert([
                'from_user_id' => $from,
                'to_user_id' => $to,
                'message' => $msgTexts[array_rand($msgTexts)],
                'is_read' => rand(0, 1),
                'created_at' => $now->copy()->subDays(rand(0, 14))->subHours(rand(0, 12)),
                'updated_at' => $now,
            ]);
        }

        // ============================================================
        // 18. NEWS ARTICLES (10)
        // ============================================================
        $articleTitles = [
            'New immigration rules for non-EU workers in 2026',
            'Poland extends temporary protection for Ukrainian refugees',
            'EU Blue Card requirements updated for tech workers',
            'How to get a permanent residence permit in Poland',
            'Changes in work permit processing times announced',
            'Digital nomad visa now available in Poland',
            'Family reunification process simplified',
            'New online system for residence applications',
            'Poland ranks top destination for skilled migrants',
            'Important tax changes for foreign workers',
        ];
        $newsSites = ['polandpulse.news', 'bizeurope.news', 'techpulse.news', 'diaspora.news'];
        foreach ($articleTitles as $i => $title) {
            DB::table('news_articles')->insert([
                'title' => $title,
                'slug' => Str::slug($title),
                'content' => '<p>' . $title . '</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Detailed article content about immigration regulations and updates for Poland.</p>',
                'original_content' => null,
                'source_url' => 'https://example.com/article-' . ($i + 1),
                'source_name' => ['Reuters', 'PAP', 'TVN24', 'Onet', 'Gazeta.pl'][rand(0, 4)],
                'site_domain' => $newsSites[array_rand($newsSites)],
                'category' => ['immigration', 'work_permits', 'legislation', 'business', 'eu_policy'][rand(0, 4)],
                'language' => 'en',
                'status' => ['published', 'published', 'published', 'draft', 'published'][rand(0, 4)],
                'wp_post_id' => $i % 4 === 0 ? null : rand(100, 500),
                'published_at' => $now->copy()->subDays(rand(1, 30)),
                'created_at' => $now->copy()->subDays(rand(1, 30)),
                'updated_at' => $now,
            ]);
        }
    }
}
