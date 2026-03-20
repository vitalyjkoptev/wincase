import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../../providers/auth_provider.dart';
import '../../main.dart';

class BossProfileScreen extends ConsumerWidget {
  const BossProfileScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final auth = ref.watch(authProvider);

    return Scaffold(
      backgroundColor: WC.background,
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          // Profile header with logo
          Container(
            padding: const EdgeInsets.all(24),
            decoration: BoxDecoration(
              gradient: const LinearGradient(colors: [WC.navy, WC.navyLight]),
              borderRadius: BorderRadius.circular(16),
            ),
            child: Column(
              children: [
                CircleAvatar(
                  radius: 36,
                  backgroundColor: Colors.white24,
                  backgroundImage: auth.avatarUrl != null ? NetworkImage(auth.avatarUrl!) : null,
                  child: auth.avatarUrl == null
                      ? Text(
                          _initials(auth.userName ?? 'D'),
                          style: const TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold),
                        )
                      : null,
                ),
                const SizedBox(height: 12),
                Text(auth.userName ?? 'Director', style: const TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold)),
                Text(auth.userEmail ?? '', style: TextStyle(color: Colors.white.withValues(alpha: 0.7), fontSize: 13)),
                const SizedBox(height: 8),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                  decoration: BoxDecoration(color: Colors.white.withValues(alpha: 0.15), borderRadius: BorderRadius.circular(16)),
                  child: Text(
                    (auth.userRole ?? 'boss').toUpperCase(),
                    style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.w600),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 20),

          // Quick stats
          Row(
            children: [
              _statCard('Workers', '12', Icons.groups, WC.info),
              const SizedBox(width: 12),
              _statCard('Active Cases', '47', Icons.folder_open, WC.success),
              const SizedBox(width: 12),
              _statCard('Revenue', '€24.5k', Icons.euro, WC.warning),
            ],
          ),
          const SizedBox(height: 20),

          // Company section
          _menuSection('Company', [
            _menuItem(Icons.business, 'Company Settings', () {}),
            _menuItem(Icons.people, 'Worker Management', () => context.go('/workers')),
            _menuItem(Icons.assessment, 'Reports & Analytics', () {}),
            _menuItem(Icons.account_balance_wallet, 'Finances', () => context.go('/finances')),
          ]),
          const SizedBox(height: 12),

          // CRM section
          _menuSection('CRM', [
            _menuItem(Icons.leaderboard, 'Leads', () => context.go('/leads')),
            _menuItem(Icons.point_of_sale, 'POS / Payments', () => context.go('/pos')),
            _menuItem(Icons.notifications_active, 'Notifications', () => context.go('/notifications')),
          ]),
          const SizedBox(height: 12),

          // Communication
          _menuSection('Communication', [
            _menuItem(Icons.notifications, 'Notification Preferences', () {}),
            _menuItem(Icons.integration_instructions, 'Channel Integrations', () {}),
            _menuItem(Icons.webhook, 'Webhooks & API', () {}),
          ]),
          const SizedBox(height: 12),

          // App settings
          _menuSection('App', [
            _menuItem(Icons.dark_mode, 'Appearance', () {}),
            _menuItem(Icons.language, 'Language', () {}),
            _menuItem(Icons.security, 'Security & 2FA', () {}),
            _menuItem(Icons.info_outline, 'About WinCase', () => _showAbout(context)),
          ]),
          const SizedBox(height: 20),

          // Logout
          SizedBox(
            width: double.infinity,
            child: OutlinedButton.icon(
              onPressed: () => _confirmLogout(context, ref),
              icon: const Icon(Icons.logout, color: WC.danger),
              label: const Text('Logout', style: TextStyle(color: WC.danger)),
              style: OutlinedButton.styleFrom(
                side: const BorderSide(color: WC.danger),
                padding: const EdgeInsets.symmetric(vertical: 14),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
            ),
          ),
          const SizedBox(height: 80),
        ],
      ),
    );
  }

  String _initials(String name) {
    final parts = name.trim().split(' ');
    if (parts.length >= 2) return '${parts[0][0]}${parts[1][0]}'.toUpperCase();
    return parts[0][0].toUpperCase();
  }

  Widget _statCard(String label, String value, IconData icon, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: WC.border),
        ),
        child: Column(
          children: [
            Icon(icon, size: 22, color: color),
            const SizedBox(height: 6),
            Text(value, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
            Text(label, style: const TextStyle(fontSize: 11, color: WC.textMuted)),
          ],
        ),
      ),
    );
  }

  Widget _menuSection(String title, List<Widget> items) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.only(left: 4, bottom: 8),
          child: Text(title, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold, color: WC.textMuted, letterSpacing: 0.5)),
        ),
        Container(
          decoration: BoxDecoration(
            color: Colors.white,
            borderRadius: BorderRadius.circular(12),
            border: Border.all(color: WC.border),
          ),
          child: Column(children: items),
        ),
      ],
    );
  }

  Widget _menuItem(IconData icon, String label, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        child: Row(
          children: [
            Icon(icon, size: 20, color: WC.navy),
            const SizedBox(width: 12),
            Expanded(child: Text(label, style: const TextStyle(fontSize: 14))),
            const Icon(Icons.chevron_right, size: 18, color: WC.textMuted),
          ],
        ),
      ),
    );
  }

  void _confirmLogout(BuildContext context, WidgetRef ref) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Logout'),
        content: const Text('Are you sure you want to logout?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancel')),
          FilledButton(
            onPressed: () {
              Navigator.pop(ctx);
              ref.read(authProvider.notifier).logout();
            },
            style: FilledButton.styleFrom(backgroundColor: WC.danger),
            child: const Text('Logout', style: TextStyle(color: Colors.white)),
          ),
        ],
      ),
    );
  }

  void _showAbout(BuildContext context) {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: Row(
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(8),
              child: Image.asset('assets/images/logo_icon_dark.png', width: 32, height: 32),
            ),
            const SizedBox(width: 10),
            const Text('WinCase CRM'),
          ],
        ),
        content: const Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Version 4.0.0'),
            SizedBox(height: 4),
            Text('Immigration Bureau CRM', style: TextStyle(color: WC.textMuted, fontSize: 13)),
            SizedBox(height: 8),
            Text('wincase.eu', style: TextStyle(color: WC.navy, fontSize: 13)),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('OK')),
        ],
      ),
    );
  }
}
