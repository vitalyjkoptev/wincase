<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Auth\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct(protected UsersService $users) {}

    // GET /api/v1/users
    public function index(Request $request): JsonResponse
    {
        return response()->json(['success' => true, 'data' =>
            $this->users->list($request->only('role', 'status', 'search'))]);
    }

    // GET /api/v1/users/roles
    public function roles(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->users->getRoles()]);
    }

    // GET /api/v1/users/team-stats
    public function teamStats(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->users->getTeamStats()]);
    }

    // GET /api/v1/users/{id}
    public function show(int $id): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->users->show($id)]);
    }

    // POST /api/v1/users
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'nullable|email', // can be auto-generated
            'password' => 'required|string|min:8',
            'role' => 'required|in:boss,staff,user',
            'phone' => 'nullable|string|max:30',
            'department' => 'nullable|string|max:100',
        ]);

        $data = $request->all();

        // If auto_email flag is set or email is empty, generate from name+role
        if (!empty($data['auto_email']) || empty($data['email'])) {
            $data['auto_email'] = true;
        }

        $user = $this->users->create($data);
        return response()->json(['success' => true, 'data' => $user->toArray()], 201);
    }

    // PUT /api/v1/users/{id}
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'email' => "nullable|email|unique:users,email,{$id}",
            'password' => 'nullable|string|min:8',
        ]);
        return response()->json(['success' => true, 'data' => $this->users->update($id, $request->all())->toArray()]);
    }

    // POST /api/v1/users/{id}/role
    public function changeRole(Request $request, int $id): JsonResponse
    {
        $request->validate(['role' => 'required|in:boss,staff,user']);
        return response()->json(['success' => true, 'data' =>
            $this->users->changeRole($id, $request->input('role'))->toArray()]);
    }

    // POST /api/v1/users/{id}/password — Boss changes staff password
    public function changePassword(Request $request, int $id): JsonResponse
    {
        $request->validate(['password' => 'required|string|min:8']);
        $user = $this->users->changePasswordByBoss($id, $request->input('password'), $request->user());
        return response()->json(['success' => true, 'data' => $user->toArray()]);
    }

    // POST /api/v1/users/{id}/deactivate
    public function deactivate(int $id): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->users->deactivate($id)->toArray()]);
    }

    // POST /api/v1/users/{id}/activate
    public function activate(int $id): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $this->users->activate($id)->toArray()]);
    }

    // DELETE /api/v1/users/{id} — soft-delete
    public function destroy(int $id): JsonResponse
    {
        $user = \App\Models\User::findOrFail($id);
        $user->tokens()->delete();
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted.']);
    }
}
