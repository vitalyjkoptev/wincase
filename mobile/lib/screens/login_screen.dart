import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../providers/auth_provider.dart';
import '../services/biometric_service.dart';
import '../main.dart';

class LoginScreen extends ConsumerStatefulWidget {
  const LoginScreen({super.key});

  @override
  ConsumerState<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends ConsumerState<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailCtrl = TextEditingController();
  final _passwordCtrl = TextEditingController();
  bool _obscurePassword = true;
  String? _selectedMode; // null = role selection, 'boss' / 'staff' / 'user' = login form
  bool _biometricAvailable = false;
  bool _hasSavedLogin = false;
  String? _savedRole;
  bool _saveBiometric = true; // checkbox to enable biometric on login

  @override
  void initState() {
    super.initState();
    _checkBiometric();
  }

  Future<void> _checkBiometric() async {
    final available = await BiometricService.isAvailable();
    final hasSaved = await BiometricService.hasStoredCredentials();
    final savedRole = await BiometricService.getStoredRole();
    if (mounted) {
      setState(() {
        _biometricAvailable = available;
        _hasSavedLogin = hasSaved;
        _savedRole = savedRole;
      });
    }
  }

  @override
  void dispose() {
    _emailCtrl.dispose();
    _passwordCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;

    final success = await ref.read(authProvider.notifier).login(
      _emailCtrl.text.trim(),
      _passwordCtrl.text,
      _selectedMode!,
    );

    if (success && mounted) {
      // Save credentials for biometric login if user opted in
      if (_biometricAvailable && _saveBiometric) {
        final api = ref.read(apiClientProvider);
        final token = await api.getToken();
        if (token != null) {
          await BiometricService.saveCredentials(
            token: token,
            email: _emailCtrl.text.trim(),
            role: _selectedMode!,
          );
        }
      }
      context.go('/dashboard');
    }
  }

  Future<void> _biometricLogin() async {
    final token = await BiometricService.getTokenAfterAuth();
    if (token == null) return;

    final role = _savedRole ?? 'boss';
    final success = await ref.read(authProvider.notifier).loginWithToken(token, role);

    if (success && mounted) {
      // Refresh stored token
      try {
        final api = ref.read(apiClientProvider);
        final response = await api.dio.post('/auth/refresh');
        final newToken = response.data['data']['token'] as String;
        await api.setToken(newToken);
        await BiometricService.saveCredentials(
          token: newToken,
          email: ref.read(authProvider).userEmail ?? '',
          role: role,
        );
      } catch (_) {}
      context.go('/dashboard');
    } else if (mounted) {
      // Token expired — clear biometric and show password form
      await BiometricService.clear();
      setState(() {
        _hasSavedLogin = false;
        _selectedMode = role;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final auth = ref.watch(authProvider);
    final theme = Theme.of(context);

    return Scaffold(
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(32),
            child: _selectedMode == null ? _buildRoleSelection(theme, auth) : _buildLoginForm(auth, theme),
          ),
        ),
      ),
    );
  }

  Widget _buildRoleSelection(ThemeData theme, AuthState auth) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        // Logo
        Image.asset('assets/images/logo_full.png', width: 220),
        const SizedBox(height: 8),
        const Text('Immigration Bureau', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: WC.textSecondary)),
        const SizedBox(height: 40),

        // Biometric quick login
        if (_biometricAvailable && _hasSavedLogin) ...[
          _BiometricButton(
            savedRole: _savedRole,
            isLoading: auth.isLoading,
            onTap: _biometricLogin,
          ),
          const SizedBox(height: 24),
          Row(
            children: [
              const Expanded(child: Divider()),
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Text('or select role', style: TextStyle(fontSize: 13, color: WC.textMuted)),
              ),
              const Expanded(child: Divider()),
            ],
          ),
          const SizedBox(height: 20),
        ] else ...[
          const Text('Select your role', style: TextStyle(fontSize: 17, fontWeight: FontWeight.w600, color: WC.navy)),
          const SizedBox(height: 20),
        ],

        if (auth.error != null) ...[
          Padding(
            padding: const EdgeInsets.only(bottom: 12),
            child: Text(auth.error!, style: TextStyle(color: theme.colorScheme.error, fontSize: 13)),
          ),
        ],

        // Boss
        _RoleCard(
          icon: Icons.admin_panel_settings,
          title: 'Boss',
          subtitle: 'Dashboard, finances, workers, full control',
          solid: true,
          onTap: () => setState(() => _selectedMode = 'boss'),
        ),
        const SizedBox(height: 12),

        // Staff
        _RoleCard(
          icon: Icons.badge,
          title: 'Staff',
          subtitle: 'Clients, cases, tasks, calendar, documents',
          solid: false,
          onTap: () => setState(() => _selectedMode = 'staff'),
        ),
        const SizedBox(height: 12),

        // User / Client
        _RoleCard(
          icon: Icons.person,
          title: 'Client',
          subtitle: 'My cases, documents, messages',
          solid: false,
          onTap: () => setState(() => _selectedMode = 'user'),
        ),
      ],
    );
  }

  Widget _buildLoginForm(AuthState auth, ThemeData theme) {
    final modeLabel = switch (_selectedMode) {
      'boss' => 'Boss Login',
      'staff' => 'Staff Login',
      'user' => 'Client Login',
      _ => 'Login',
    };

    return Form(
      key: _formKey,
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          // Back
          Align(
            alignment: Alignment.centerLeft,
            child: TextButton.icon(
              onPressed: () => setState(() => _selectedMode = null),
              icon: const Icon(Icons.arrow_back),
              label: const Text('Back'),
            ),
          ),
          const SizedBox(height: 16),

          // Logo icon
          Image.asset('assets/images/logo_icon_dark.png', width: 64, height: 64),
          const SizedBox(height: 16),
          Text(
            modeLabel,
            style: theme.textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.bold, color: WC.navy),
          ),
          const SizedBox(height: 32),

          // Email
          TextFormField(
            controller: _emailCtrl,
            keyboardType: TextInputType.emailAddress,
            decoration: const InputDecoration(
              labelText: 'Email',
              prefixIcon: Icon(Icons.email_outlined),
            ),
            validator: (v) => v != null && v.contains('@') ? null : 'Valid email required',
          ),
          const SizedBox(height: 16),

          // Password
          TextFormField(
            controller: _passwordCtrl,
            obscureText: _obscurePassword,
            decoration: InputDecoration(
              labelText: 'Password',
              prefixIcon: const Icon(Icons.lock_outlined),
              suffixIcon: IconButton(
                icon: Icon(_obscurePassword ? Icons.visibility_off : Icons.visibility),
                onPressed: () => setState(() => _obscurePassword = !_obscurePassword),
              ),
            ),
            validator: (v) => v != null && v.length >= 6 ? null : 'Min 6 characters',
            onFieldSubmitted: (_) => _submit(),
          ),

          // Save biometric option
          if (_biometricAvailable) ...[
            const SizedBox(height: 8),
            Row(
              children: [
                SizedBox(
                  width: 24, height: 24,
                  child: Checkbox(
                    value: _saveBiometric,
                    onChanged: (v) => setState(() => _saveBiometric = v ?? true),
                    activeColor: WC.navy,
                  ),
                ),
                const SizedBox(width: 8),
                GestureDetector(
                  onTap: () => setState(() => _saveBiometric = !_saveBiometric),
                  child: Text(
                    'Enable biometric login',
                    style: TextStyle(fontSize: 13, color: WC.textSecondary),
                  ),
                ),
              ],
            ),
          ],

          if (auth.error != null) ...[
            const SizedBox(height: 12),
            Text(auth.error!, style: TextStyle(color: theme.colorScheme.error)),
          ],

          const SizedBox(height: 24),

          // Submit
          SizedBox(
            width: double.infinity,
            height: 52,
            child: FilledButton(
              onPressed: auth.isLoading ? null : _submit,
              style: FilledButton.styleFrom(backgroundColor: WC.navy),
              child: auth.isLoading
                  ? const SizedBox(width: 24, height: 24,
                      child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                  : const Text('Log In', style: TextStyle(fontSize: 16, color: Colors.white)),
            ),
          ),

          // Register — only for clients
          if (_selectedMode == 'user') ...[
            const SizedBox(height: 16),
            TextButton(
              onPressed: () => Navigator.of(context).push(
                MaterialPageRoute(builder: (_) => const _RegisterScreen()),
              ),
              child: const Text('Don\'t have an account? Create one'),
            ),
          ],

          // Info for boss/staff — no registration
          if (_selectedMode != 'user') ...[
            const SizedBox(height: 16),
            Text(
              'Account created by admin in CRM',
              style: TextStyle(fontSize: 12, color: WC.textMuted),
            ),
          ],
        ],
      ),
    );
  }
}

// =====================================================
// BIOMETRIC LOGIN BUTTON
// =====================================================

class _BiometricButton extends StatelessWidget {
  final String? savedRole;
  final bool isLoading;
  final VoidCallback onTap;

  const _BiometricButton({required this.savedRole, required this.isLoading, required this.onTap});

  @override
  Widget build(BuildContext context) {
    final roleLabel = switch (savedRole) {
      'boss' => 'Boss',
      'staff' => 'Staff',
      'user' => 'Client',
      _ => '',
    };

    return SizedBox(
      width: double.infinity,
      height: 64,
      child: OutlinedButton(
        onPressed: isLoading ? null : onTap,
        style: OutlinedButton.styleFrom(
          side: BorderSide(color: WC.navy, width: 2),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
        ),
        child: isLoading
          ? const SizedBox(width: 24, height: 24, child: CircularProgressIndicator(strokeWidth: 2))
          : Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Icon(Icons.fingerprint, size: 28, color: WC.navy),
                const SizedBox(width: 12),
                Text(
                  'Quick Login ($roleLabel)',
                  style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w600, color: WC.navy),
                ),
              ],
            ),
      ),
    );
  }
}

// =====================================================
// REGISTER SCREEN — only for 'user' (client) role
// =====================================================

class _RegisterScreen extends ConsumerStatefulWidget {
  const _RegisterScreen();

  @override
  ConsumerState<_RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends ConsumerState<_RegisterScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();
  final _phoneCtrl = TextEditingController();
  final _passwordCtrl = TextEditingController();
  bool _obscure = true;

  @override
  void dispose() {
    _nameCtrl.dispose();
    _emailCtrl.dispose();
    _phoneCtrl.dispose();
    _passwordCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;

    final success = await ref.read(authProvider.notifier).register(
      _nameCtrl.text.trim(),
      _emailCtrl.text.trim(),
      _passwordCtrl.text,
      _phoneCtrl.text.trim().isEmpty ? null : _phoneCtrl.text.trim(),
    );

    if (success && mounted) {
      Navigator.of(context).pop();
      GoRouter.of(context).go('/dashboard');
    }
  }

  @override
  Widget build(BuildContext context) {
    final auth = ref.watch(authProvider);
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(title: const Text('Create Account')),
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(32),
            child: Form(
              key: _formKey,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Image.asset('assets/images/logo_icon_dark.png', width: 56, height: 56),
                  const SizedBox(height: 16),
                  Text('Client Registration', style: theme.textTheme.headlineSmall?.copyWith(fontWeight: FontWeight.bold, color: WC.navy)),
                  const SizedBox(height: 32),

                  TextFormField(
                    controller: _nameCtrl,
                    decoration: const InputDecoration(labelText: 'Full Name', prefixIcon: Icon(Icons.person_outlined)),
                    validator: (v) => v != null && v.length >= 2 ? null : 'Name required',
                  ),
                  const SizedBox(height: 16),

                  TextFormField(
                    controller: _emailCtrl,
                    keyboardType: TextInputType.emailAddress,
                    decoration: const InputDecoration(labelText: 'Email', prefixIcon: Icon(Icons.email_outlined)),
                    validator: (v) => v != null && v.contains('@') ? null : 'Valid email required',
                  ),
                  const SizedBox(height: 16),

                  TextFormField(
                    controller: _phoneCtrl,
                    keyboardType: TextInputType.phone,
                    decoration: const InputDecoration(labelText: 'Phone (optional)', prefixIcon: Icon(Icons.phone_outlined)),
                  ),
                  const SizedBox(height: 16),

                  TextFormField(
                    controller: _passwordCtrl,
                    obscureText: _obscure,
                    decoration: InputDecoration(
                      labelText: 'Password',
                      prefixIcon: const Icon(Icons.lock_outlined),
                      suffixIcon: IconButton(
                        icon: Icon(_obscure ? Icons.visibility_off : Icons.visibility),
                        onPressed: () => setState(() => _obscure = !_obscure),
                      ),
                    ),
                    validator: (v) => v != null && v.length >= 8 ? null : 'Min 8 characters',
                  ),

                  if (auth.error != null) ...[
                    const SizedBox(height: 12),
                    Text(auth.error!, style: TextStyle(color: theme.colorScheme.error)),
                  ],

                  const SizedBox(height: 24),

                  SizedBox(
                    width: double.infinity,
                    height: 52,
                    child: FilledButton(
                      onPressed: auth.isLoading ? null : _submit,
                      style: FilledButton.styleFrom(backgroundColor: WC.navy),
                      child: auth.isLoading
                          ? const SizedBox(width: 24, height: 24,
                              child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                          : const Text('Create Account', style: TextStyle(fontSize: 16, color: Colors.white)),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}

class _RoleCard extends StatelessWidget {
  final IconData icon;
  final String title;
  final String subtitle;
  final bool solid;
  final VoidCallback onTap;

  const _RoleCard({
    required this.icon,
    required this.title,
    required this.subtitle,
    required this.solid,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    final fgColor = solid ? Colors.white : WC.navy;
    final subtitleColor = solid ? Colors.white70 : WC.textMuted;

    return Card(
      elevation: solid ? 4 : 0,
      color: solid ? WC.navy : null,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
        side: solid ? BorderSide.none : BorderSide(color: WC.navy.withOpacity(0.2), width: 1.5),
      ),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: Row(
            children: [
              Container(
                width: 50, height: 50,
                decoration: BoxDecoration(
                  color: solid ? Colors.white.withOpacity(0.15) : WC.navy.withOpacity(0.08),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(icon, size: 26, color: fgColor),
              ),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(title, style: TextStyle(fontSize: 17, fontWeight: FontWeight.w700, color: fgColor)),
                    const SizedBox(height: 3),
                    Text(subtitle, style: TextStyle(fontSize: 12, color: subtitleColor)),
                  ],
                ),
              ),
              Icon(Icons.arrow_forward_ios, size: 16, color: fgColor),
            ],
          ),
        ),
      ),
    );
  }
}
