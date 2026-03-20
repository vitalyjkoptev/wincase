<?php

namespace App\Http\Controllers;

use App\Models\ClientRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientPortalController extends Controller
{
    /**
     * Handle client registration form submission.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:client_registrations,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:50',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = $request->userAgent();
        $data['agreements_signed_at'] = now();
        $data['status'] = 'new';

        // Convert checkbox booleans
        foreach (['agreed_terms', 'agreed_rodo', 'agreed_poa', 'agreed_data_sharing', 'agreed_marketing',
                   'entry_ban', 'criminal_record', 'same_correspondence_address', 'bank_account_poland'] as $field) {
            if (isset($data[$field])) {
                $data[$field] = filter_var($data[$field], FILTER_VALIDATE_BOOLEAN);
            }
        }

        // Nullify empty date strings so MySQL doesn't choke
        $dateFields = [
            'date_of_birth', 'passport_issue_date', 'passport_expiry_date',
            'pl_living_since', 'arrival_date', 'permit_from', 'permit_until',
            'employment_since', 'work_permit_expiry',
        ];
        foreach ($dateFields as $df) {
            if (isset($data[$df]) && ($data[$df] === '' || $data[$df] === null)) {
                $data[$df] = null;
            }
        }

        // Nullify empty numeric fields
        foreach (['salary', 'graduation_year', 'num_children', 'dependents_in_poland'] as $nf) {
            if (isset($data[$nf]) && ($data[$nf] === '' || $data[$nf] === null)) {
                $data[$nf] = null;
            }
        }

        // JSON encode family members
        if (isset($data['family_members']) && is_array($data['family_members'])) {
            $data['family_members'] = json_encode($data['family_members']);
        }

        // Strip any fields not in fillable
        $fillable = (new ClientRegistration())->getFillable();
        $data = array_intersect_key($data, array_flip($fillable));

        $reg = ClientRegistration::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Registration completed successfully',
            'id' => $reg->id,
        ], 201);
    }

    /**
     * API: List all registrations (for admin).
     */
    public function index(Request $request): JsonResponse
    {
        $query = ClientRegistration::orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('passport_number', 'like', "%{$s}%");
            });
        }

        return response()->json($query->get());
    }

    /**
     * API: Get single registration.
     */
    public function show(int $id): JsonResponse
    {
        $reg = ClientRegistration::findOrFail($id);
        return response()->json($reg);
    }

    /**
     * API: Update registration status.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $reg = ClientRegistration::findOrFail($id);
        $reg->update(['status' => $request->input('status', 'reviewed')]);
        return response()->json(['success' => true, 'status' => $reg->status]);
    }

    /**
     * API: Delete registration.
     */
    public function destroy(int $id): JsonResponse
    {
        ClientRegistration::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
