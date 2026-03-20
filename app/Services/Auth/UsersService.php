<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersService
{
    // =====================================================
    // EMAIL DOMAINS
    // =====================================================

    const BOSS_ROLES = ['boss'];
    const STAFF_ROLES = ['staff'];
    const BOSS_DOMAIN = 'crm.wincase.eu';
    const STAFF_DOMAIN = 'staff.wincase.eu';

    // =====================================================
    // LIST USERS
    // =====================================================

    public function list(array $filters = []): array
    {
        $query = User::query();

        if (!empty($filters['role'])) $query->where('role', $filters['role']);
        if (!empty($filters['status'])) $query->where('status', $filters['status']);
        if (!empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%");
            });
        }

        return $query->orderBy('name')->get([
            'id', 'name', 'email', 'role', 'status', 'avatar_url',
            'phone', 'department', 'last_login_at', 'created_at',
        ])->toArray();
    }

    // =====================================================
    // SHOW
    // =====================================================

    public function show(int $id): array
    {
        $user = User::findOrFail($id);

        return [
            'user' => $user->makeHidden(['password', 'two_factor_secret', 'two_factor_recovery_codes'])->toArray(),
            'active_cases' => $user->assignedCases()->where('status', 'active')->count(),
            'active_tasks' => $user->tasks()->where('status', '!=', 'completed')->count(),
            'leads_this_month' => $user->assignedLeads()
                ->where('created_at', '>=', now()->startOfMonth())->count(),
            'can_change_password' => $this->canChangePassword($user->role),
        ];
    }

    // =====================================================
    // CREATE — auto-generates email from name + role
    // =====================================================

    public function create(array $data): User
    {
        // Auto-generate email if not provided or auto_email flag is set
        if (!empty($data['auto_email']) || empty($data['email'])) {
            $data['email'] = $this->generateEmail($data['name'], $data['role']);
        }

        unset($data['auto_email']);

        $data['password'] = Hash::make($data['password']);
        $data['status'] = $data['status'] ?? 'active';
        $data['can_change_password'] = $this->canChangePassword($data['role']);

        return User::create($data);
    }

    // =====================================================
    // UPDATE
    // =====================================================

    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        unset($data['auto_email']);
        $user->update($data);
        return $user->fresh();
    }

    // =====================================================
    // CHANGE PASSWORD (by Boss for Staff)
    // =====================================================

    public function changePasswordByBoss(int $targetUserId, string $newPassword, User $requestingUser): User
    {
        // Only boss-level users can change others' passwords
        if (!in_array($requestingUser->role, self::BOSS_ROLES)) {
            throw new \RuntimeException('Only Boss can change user passwords.');
        }

        $target = User::findOrFail($targetUserId);
        $target->update(['password' => Hash::make($newPassword)]);
        $target->tokens()->delete(); // force re-login with new password

        return $target;
    }

    public function changeOwnPassword(User $user, string $currentPassword, string $newPassword): bool
    {
        // Staff cannot change their own password
        if (!$this->canChangePassword($user->role)) {
            throw new \RuntimeException('You are not allowed to change your password. Contact your boss.');
        }

        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->update(['password' => Hash::make($newPassword)]);
        $user->tokens()->delete(); // force re-login
        return true;
    }

    // =====================================================
    // DEACTIVATE / ACTIVATE
    // =====================================================

    public function deactivate(int $id): User
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'inactive']);
        $user->tokens()->delete(); // force logout
        return $user;
    }

    public function activate(int $id): User
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);
        return $user;
    }

    // =====================================================
    // CHANGE ROLE
    // =====================================================

    public function changeRole(int $id, string $role): User
    {
        $validRoles = ['boss', 'staff', 'user'];
        if (!in_array($role, $validRoles)) {
            throw new \InvalidArgumentException("Invalid role: {$role}");
        }

        $user = User::findOrFail($id);
        $user->update(['role' => $role]);
        $user->tokens()->delete(); // force re-login for new abilities
        return $user;
    }

    // =====================================================
    // TEAM STATS
    // =====================================================

    public function getTeamStats(): array
    {
        $users = User::where('status', 'active')->get();

        return [
            'total_active' => $users->count(),
            'by_role' => $users->groupBy('role')->map->count()->toArray(),
            'by_department' => $users->groupBy('department')->map->count()->toArray(),
            'online_today' => User::where('last_login_at', '>=', now()->startOfDay())->count(),
        ];
    }

    // =====================================================
    // AUTO-GENERATE EMAIL
    // =====================================================

    public function generateEmail(string $name, string $role): string
    {
        // Boss → lastname.firstname@admin.wincase.eu
        // Staff → lastname.firstname@staff.wincase.eu
        $domain = in_array($role, self::BOSS_ROLES) ? self::BOSS_DOMAIN : self::STAFF_DOMAIN;
        $prefix = $this->transliterate($name);

        $parts = preg_split('/\s+/', $prefix);
        $parts = array_filter($parts, fn($p) => strlen($p) > 0);
        $parts = array_values($parts);

        if (count($parts) >= 2) {
            // Фамилия.Имя (lastname first)
            $emailPrefix = end($parts) . '.' . $parts[0];
        } elseif (count($parts) === 1) {
            $emailPrefix = $parts[0];
        } else {
            $emailPrefix = 'user';
        }

        // Clean: only a-z, 0-9, dots, hyphens
        $emailPrefix = preg_replace('/[^a-z0-9.\-]/', '', $emailPrefix);

        $email = $emailPrefix . '@' . $domain;

        // Check uniqueness — append number if needed
        $counter = 1;
        $originalPrefix = $emailPrefix;
        while (User::where('email', $email)->exists()) {
            $counter++;
            $email = $originalPrefix . $counter . '@' . $domain;
        }

        return $email;
    }

    // =====================================================
    // CAN CHANGE PASSWORD
    // =====================================================

    public function canChangePassword(string $role): bool
    {
        // Only boss-level roles can change their own password
        return in_array($role, self::BOSS_ROLES);
    }

    // =====================================================
    // TRANSLITERATE
    // =====================================================

    protected function transliterate(string $text): string
    {
        $map = [
            'а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'yo','ж'=>'zh',
            'з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o',
            'п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'kh','ц'=>'ts',
            'ч'=>'ch','ш'=>'sh','щ'=>'shch','ъ'=>'','ы'=>'y','ь'=>'','э'=>'e','ю'=>'yu','я'=>'ya',
            // Ukrainian
            'є'=>'ye','і'=>'i','ї'=>'yi','ґ'=>'g',
            // Polish
            'ą'=>'a','ć'=>'c','ę'=>'e','ł'=>'l','ń'=>'n','ó'=>'o','ś'=>'s','ź'=>'z','ż'=>'z',
            // Uppercase versions
            'А'=>'a','Б'=>'b','В'=>'v','Г'=>'g','Д'=>'d','Е'=>'e','Ё'=>'yo','Ж'=>'zh',
            'З'=>'z','И'=>'i','Й'=>'y','К'=>'k','Л'=>'l','М'=>'m','Н'=>'n','О'=>'o',
            'П'=>'p','Р'=>'r','С'=>'s','Т'=>'t','У'=>'u','Ф'=>'f','Х'=>'kh','Ц'=>'ts',
            'Ч'=>'ch','Ш'=>'sh','Щ'=>'shch','Ъ'=>'','Ы'=>'y','Ь'=>'','Э'=>'e','Ю'=>'yu','Я'=>'ya',
            'Є'=>'ye','І'=>'i','Ї'=>'yi','Ґ'=>'g',
            'Ą'=>'a','Ć'=>'c','Ę'=>'e','Ł'=>'l','Ń'=>'n','Ó'=>'o','Ś'=>'s','Ź'=>'z','Ż'=>'z',
        ];

        $text = mb_strtolower($text);
        $result = '';
        $len = mb_strlen($text);
        for ($i = 0; $i < $len; $i++) {
            $ch = mb_substr($text, $i, 1);
            $result .= $map[$ch] ?? $ch;
        }
        return $result;
    }

    // =====================================================
    // AVAILABLE ROLES
    // =====================================================

    public function getRoles(): array
    {
        return [
            'boss' => [
                'label' => 'Boss',
                'description' => 'Full system access. Admin panel, mobile Boss app, all modules.',
                'abilities_count' => 'All',
                'email_domain' => self::BOSS_DOMAIN,
                'can_change_password' => true,
            ],
            'staff' => [
                'label' => 'Staff',
                'description' => 'Staff access: clients, cases, leads, tasks, documents, POS.',
                'abilities_count' => 16,
                'email_domain' => self::STAFF_DOMAIN,
                'can_change_password' => false,
            ],
            'user' => [
                'label' => 'Client',
                'description' => 'Client portal: view own cases, documents, payments.',
                'abilities_count' => 0,
                'email_domain' => null,
                'can_change_password' => true,
            ],
        ];
    }
}
