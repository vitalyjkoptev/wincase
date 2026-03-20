import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import '../widgets/wc_logo.dart';
import '../services/api_service.dart';
import 'register_screen.dart';
import 'main_shell.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});
  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _emailCtrl = TextEditingController();
  final _passCtrl = TextEditingController();
  bool _obscure = true;
  bool _loading = false;
  bool _remember = false;

  void _login() async {
    if (_emailCtrl.text.isEmpty || _passCtrl.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please enter email and password'), backgroundColor: WC.danger),
      );
      return;
    }
    setState(() => _loading = true);
    try {
      final result = await ApiService.login(_emailCtrl.text.trim(), _passCtrl.text);
      if (!mounted) return;
      if (result['success'] == true) {
        Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const MainShell()));
      } else {
        final msg = result['message'] ?? result['errors']?.values?.first?.first ?? 'Login failed';
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(msg.toString()), backgroundColor: WC.danger),
        );
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Connection error: $e'), backgroundColor: WC.danger),
      );
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: WC.bg,
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(24),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const WcLogo(fontSize: 36),
                const SizedBox(height: 8),
                const Text('Client Portal', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w600)),
                const SizedBox(height: 4),
                Text('Sign in to manage your immigration case',
                    style: TextStyle(color: Colors.grey[600], fontSize: 13)),
                const SizedBox(height: 32),

                Card(
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                  child: Padding(
                    padding: const EdgeInsets.all(24),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      children: [
                        TextField(
                          controller: _emailCtrl,
                          keyboardType: TextInputType.emailAddress,
                          decoration: const InputDecoration(
                            labelText: 'Email',
                            prefixIcon: Icon(Icons.email_outlined, size: 20),
                            hintText: 'your@email.com',
                          ),
                        ),
                        const SizedBox(height: 16),
                        TextField(
                          controller: _passCtrl,
                          obscureText: _obscure,
                          decoration: InputDecoration(
                            labelText: 'Password',
                            prefixIcon: const Icon(Icons.lock_outline, size: 20),
                            hintText: 'Enter password',
                            suffixIcon: IconButton(
                              icon: Icon(_obscure ? Icons.visibility_off_outlined : Icons.visibility_outlined, size: 20),
                              onPressed: () => setState(() => _obscure = !_obscure),
                            ),
                          ),
                        ),
                        const SizedBox(height: 8),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Row(
                              children: [
                                SizedBox(
                                  height: 24, width: 24,
                                  child: Checkbox(
                                    value: _remember,
                                    onChanged: (v) => setState(() => _remember = v!),
                                    activeColor: WC.primary,
                                  ),
                                ),
                                const SizedBox(width: 6),
                                const Text('Remember me', style: TextStyle(fontSize: 13)),
                              ],
                            ),
                            TextButton(
                              onPressed: () => _showForgot(),
                              child: const Text('Forgot?', style: TextStyle(fontSize: 13)),
                            ),
                          ],
                        ),
                        const SizedBox(height: 16),
                        ElevatedButton(
                          onPressed: _loading ? null : _login,
                          child: _loading
                              ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                              : const Row(
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: [
                                    Icon(Icons.login, size: 18),
                                    SizedBox(width: 8),
                                    Text('Sign In'),
                                  ],
                                ),
                        ),
                      ],
                    ),
                  ),
                ),

                const SizedBox(height: 24),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text("Don't have an account? ", style: TextStyle(color: Colors.grey[600], fontSize: 13)),
                    GestureDetector(
                      onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const RegisterScreen())),
                      child: const Text('Register here', style: TextStyle(color: WC.primary, fontWeight: FontWeight.w600, fontSize: 13)),
                    ),
                  ],
                ),

                const SizedBox(height: 32),
                const Divider(),
                const SizedBox(height: 12),
                Text('Or contact us directly:', style: TextStyle(color: Colors.grey[600], fontSize: 12)),
                const SizedBox(height: 8),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.phone, size: 14, color: Colors.grey[600]),
                    const SizedBox(width: 4),
                    Text('+48 579 266 493', style: TextStyle(color: Colors.grey[600], fontSize: 12)),
                    const SizedBox(width: 16),
                    Icon(Icons.email, size: 14, color: Colors.grey[600]),
                    const SizedBox(width: 4),
                    Text('info@wincase.eu', style: TextStyle(color: Colors.grey[600], fontSize: 12)),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  void _showForgot() {
    final ctrl = TextEditingController();
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Reset Password'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            const Text("Enter your email. We'll send a reset link.", style: TextStyle(fontSize: 13, color: Colors.grey)),
            const SizedBox(height: 12),
            TextField(controller: ctrl, decoration: const InputDecoration(labelText: 'Email', hintText: 'your@email.com')),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancel')),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(ctx);
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Reset link sent! Check your email.'), backgroundColor: WC.primary),
              );
            },
            child: const Text('Send Link'),
          ),
        ],
      ),
    );
  }
}
