<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientCase;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ClientPortalController extends Controller
{
    /**
     * Get the Client record linked to the authenticated user (by email).
     */
    private function resolveClient(Request $request): ?Client
    {
        $user = $request->user();
        if (!$user || $user->role !== 'user') {
            return null;
        }
        return Client::where('email', $user->email)->first();
    }

    // ───────────────────────────────────────────────
    // GET /client-portal/dashboard — all-in-one dashboard data
    // ───────────────────────────────────────────────
    public function dashboard(Request $request): JsonResponse
    {
      try {
        $user   = $request->user();
        $client = $this->resolveClient($request);

        // Base user data (always available even without linked client)
        $userData = [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
            'avatar_url' => $user->avatar_url,
        ];

        if (!$client) {
            return response()->json(['success' => true, 'data' => [
                'user'    => $userData,
                'client'  => null,
                'message' => 'No client profile linked. Please contact support.',
            ]]);
        }

        // Active case (latest non-closed)
        $activeCase = ClientCase::where('client_id', $client->id)
            ->whereNotIn('status', ['closed', 'cancelled'])
            ->orderByDesc('created_at')
            ->first();

        // Manager info
        $manager = $client->assigned_to
            ? User::select('id', 'name', 'email', 'phone', 'position', 'avatar_url')
                ->find($client->assigned_to)
            : null;

        // Documents stats
        $docs = Document::where('client_id', $client->id);
        $docsTotal    = (clone $docs)->count();
        $docsApproved = (clone $docs)->where('status', 'approved')->count();
        $docsPending  = (clone $docs)->whereIn('status', ['pending', 'review', 'uploaded'])->count();
        $docsRequired = (clone $docs)->where('status', 'required')->count();

        // Messages (unread count)
        $unreadMessages = Message::where('client_id', $client->id)
            ->where('direction', 'inbound')
            ->whereNull('read_at')
            ->count();

        // Payments summary
        $invoiceIds = Invoice::where('client_id', $client->id)->pluck('id');
        $totalFee = Invoice::where('client_id', $client->id)->sum('gross_amount');
        $totalPaid = Payment::where('client_id', $client->id)
            ->where('status', 'completed')
            ->sum('amount');
        $remaining = max(0, $totalFee - $totalPaid);

        // Upcoming events (tasks with due dates)
        $upcomingTasks = Task::where('client_id', $client->id)
            ->whereNotNull('due_date')
            ->where('status', '!=', 'completed')
            ->orderBy('due_date')
            ->limit(5)
            ->get(['id', 'title', 'type', 'due_date', 'status', 'priority']);

        // Case timeline (notes/messages related to active case)
        $caseTimeline = [];
        if ($activeCase) {
            $caseTimeline = Message::where('case_id', $activeCase->id)
                ->orderBy('created_at')
                ->limit(20)
                ->get(['id', 'subject', 'body', 'direction', 'channel', 'status', 'created_at']);
        }

        // Pending actions (required docs + upcoming tasks)
        $pendingDocs = Document::where('client_id', $client->id)
            ->whereIn('status', ['required', 'rejected'])
            ->get(['id', 'name', 'type', 'status']);

        $pendingTasks = Task::where('client_id', $client->id)
            ->where('status', '!=', 'completed')
            ->orderBy('due_date')
            ->limit(10)
            ->get(['id', 'title', 'type', 'due_date', 'status', 'priority']);

        return response()->json(['success' => true, 'data' => [
            'user'    => $userData,
            'client'  => $client->only([
                'id', 'first_name', 'last_name', 'email', 'phone',
                'nationality', 'passport_number', 'pesel', 'date_of_birth',
                'address', 'city', 'postal_code', 'voivodeship',
                'preferred_language', 'status', 'company_name', 'nip',
            ]),
            'active_case' => $activeCase ? [
                'id'               => $activeCase->id,
                'case_number'      => $activeCase->case_number,
                'service_type'     => $activeCase->service_type,
                'status'           => $activeCase->status,
                'priority'         => $activeCase->priority,
                'voivodeship'      => $activeCase->voivodeship,
                'office_name'      => $activeCase->office_name,
                'submission_date'  => $activeCase->submission_date,
                'decision_date'    => $activeCase->decision_date,
                'deadline'         => $activeCase->deadline,
                'progress'         => $activeCase->progress_percentage ?? 0,
                'amount'           => $activeCase->amount,
                'is_paid'          => $activeCase->is_paid,
                'notes'            => $activeCase->notes,
            ] : null,
            'manager' => $manager,
            'stats'   => [
                'documents_total'    => $docsTotal,
                'documents_approved' => $docsApproved,
                'documents_pending'  => $docsPending,
                'documents_required' => $docsRequired,
                'unread_messages'    => $unreadMessages,
                'total_fee'          => round($totalFee, 2),
                'total_paid'         => round($totalPaid, 2),
                'remaining'          => round($remaining, 2),
                'paid_percentage'    => $totalFee > 0 ? round(($totalPaid / $totalFee) * 100) : 0,
            ],
            'pending_actions' => [
                'documents' => $pendingDocs,
                'tasks'     => $pendingTasks,
            ],
            'upcoming_events' => $upcomingTasks,
            'case_timeline'   => $caseTimeline,
        ]]);
      } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'file'    => basename($e->getFile()) . ':' . $e->getLine(),
        ], 500);
      }
    }

    // ───────────────────────────────────────────────
    // GET /client-portal/documents — list client's documents
    // ───────────────────────────────────────────────
    public function documents(Request $request): JsonResponse
    {
        $client = $this->resolveClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'No client profile'], 403);
        }

        $docs = Document::where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $docs]);
    }

    // ───────────────────────────────────────────────
    // POST /client-portal/documents — upload a document
    // ───────────────────────────────────────────────
    public function uploadDocument(Request $request): JsonResponse
    {
        $client = $this->resolveClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'No client profile'], 403);
        }

        $request->validate([
            'file'    => 'required|file|max:20480', // 20MB
            'type'    => 'nullable|string|max:50',
            'name'    => 'nullable|string|max:255',
            'case_id' => 'nullable|integer',
        ]);

        $file = $request->file('file');
        $path = $file->store('documents/' . $client->id, 'public');

        $doc = Document::create([
            'documentable_type' => 'App\\Models\\Client',
            'documentable_id'   => $client->id,
            'client_id'         => $client->id,
            'case_id'           => $request->input('case_id'),
            'uploaded_by'       => $request->user()->id,
            'type'              => $request->input('type', 'other'),
            'name'              => $request->input('name', $file->getClientOriginalName()),
            'original_name'     => $file->getClientOriginalName(),
            'file_path'         => $path,
            'file_size'         => $file->getSize(),
            'mime_type'         => $file->getMimeType(),
            'status'            => 'pending',
        ]);

        return response()->json(['success' => true, 'data' => $doc], 201);
    }

    // ───────────────────────────────────────────────
    // GET /client-portal/messages — list messages
    // ───────────────────────────────────────────────
    public function messages(Request $request): JsonResponse
    {
        $client = $this->resolveClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'No client profile'], 403);
        }

        $messages = Message::where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->get();

        // Mark inbound as read
        Message::where('client_id', $client->id)
            ->where('direction', 'inbound')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true, 'data' => $messages]);
    }

    // ───────────────────────────────────────────────
    // POST /client-portal/messages — send a message
    // ───────────────────────────────────────────────
    public function sendMessage(Request $request): JsonResponse
    {
        $client = $this->resolveClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'No client profile'], 403);
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
            'case_id' => 'nullable|integer',
        ]);

        $msg = Message::create([
            'conversation_id' => 0,
            'sender_type'     => 'seeker',
            'sender_id'       => $request->user()->id,
            'message'         => $request->input('body'),
            'client_id'       => $client->id,
            'case_id'         => $request->input('case_id'),
            'user_id'         => $request->user()->id,
            'channel'         => 'in_app',
            'direction'       => 'outbound',
            'subject'         => $request->input('subject'),
            'body'            => $request->input('body'),
            'status'          => 'sent',
            'sent_at'         => now(),
        ]);

        return response()->json(['success' => true, 'data' => $msg], 201);
    }

    // ───────────────────────────────────────────────
    // GET /client-portal/payments — payment history
    // ───────────────────────────────────────────────
    public function payments(Request $request): JsonResponse
    {
        $client = $this->resolveClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'No client profile'], 403);
        }

        $invoices = Invoice::where('client_id', $client->id)
            ->orderByDesc('issue_date')
            ->get();

        $payments = Payment::where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->get();

        $totalFee  = $invoices->sum('gross_amount');
        $totalPaid = $payments->where('status', 'completed')->sum('amount');

        return response()->json(['success' => true, 'data' => [
            'invoices' => $invoices,
            'payments' => $payments,
            'summary'  => [
                'total_fee'  => round($totalFee, 2),
                'total_paid' => round($totalPaid, 2),
                'remaining'  => round(max(0, $totalFee - $totalPaid), 2),
                'paid_pct'   => $totalFee > 0 ? round(($totalPaid / $totalFee) * 100) : 0,
            ],
        ]]);
    }

    // ───────────────────────────────────────────────
    // GET /client-portal/profile — full profile
    // ───────────────────────────────────────────────
    public function profile(Request $request): JsonResponse
    {
        $user   = $request->user();
        $client = $this->resolveClient($request);

        return response()->json(['success' => true, 'data' => [
            'user'   => $user->only(['id', 'name', 'email', 'role', 'avatar_url', 'phone']),
            'client' => $client,
        ]]);
    }

    // ───────────────────────────────────────────────
    // PUT /client-portal/profile — update profile
    // ───────────────────────────────────────────────
    public function updateProfile(Request $request): JsonResponse
    {
        $client = $this->resolveClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'No client profile'], 403);
        }

        $allowed = [
            'phone', 'address', 'city', 'postal_code', 'voivodeship',
            'preferred_language', 'company_name', 'nip',
        ];

        $client->update($request->only($allowed));

        // Also update user name/phone if provided
        $user = $request->user();
        if ($request->filled('name')) {
            $user->update(['name' => $request->input('name')]);
        }
        if ($request->filled('phone')) {
            $user->update(['phone' => $request->input('phone')]);
        }

        return response()->json(['success' => true, 'data' => $client->fresh()]);
    }

    // ───────────────────────────────────────────────
    // POST /client-portal/password — change password
    // ───────────────────────────────────────────────
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 422);
        }

        $user->update(['password' => Hash::make($request->input('password'))]);

        return response()->json(['success' => true, 'message' => 'Password changed successfully']);
    }

    // ───────────────────────────────────────────────
    // GET /client-portal/cases — all client's cases
    // ───────────────────────────────────────────────
    public function cases(Request $request): JsonResponse
    {
        $client = $this->resolveClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'No client profile'], 403);
        }

        $cases = ClientCase::where('client_id', $client->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'data' => $cases]);
    }

    // ───────────────────────────────────────────────
    // POST /v1/auth/register-client — public registration
    // ───────────────────────────────────────────────
    public function registerClient(Request $request): JsonResponse
    {
        $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:8',
            'phone'        => 'required|string|max:20',
            'nationality'  => 'nullable|string|max:50',
            'date_of_birth'=> 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            // Create User
            $user = User::create([
                'name'     => $request->input('first_name') . ' ' . $request->input('last_name'),
                'email'    => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'role'     => 'user',
                'status'   => 'active',
                'phone'    => $request->input('phone'),
            ]);

            // Create Client
            $client = Client::create([
                'first_name'         => $request->input('first_name'),
                'last_name'          => $request->input('last_name'),
                'email'              => $request->input('email'),
                'phone'              => $request->input('phone'),
                'nationality'        => $request->input('nationality'),
                'date_of_birth'      => $request->input('date_of_birth'),
                'passport_number'    => $request->input('passport_number'),
                'pesel'              => $request->input('pesel'),
                'address'            => $request->input('address'),
                'city'               => $request->input('city'),
                'postal_code'        => $request->input('postal_code'),
                'voivodeship'        => $request->input('voivodeship'),
                'preferred_language' => $request->input('preferred_language', 'English'),
                'status'             => 'active',
                'gdpr_consent'       => true,
            ]);

            DB::commit();

            // Auto-login — generate token
            $token = $user->createToken('web-panel', [
                'client.profile', 'client.cases', 'client.documents',
                'client.messages', 'client.payments',
            ])->plainTextToken;

            return response()->json([
                'success'  => true,
                'token'    => $token,
                'user'     => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ],
                'redirect' => '/client-dashboard',
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Client registration failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. ' . $e->getMessage(),
            ], 500);
        }
    }
}
