import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import '../widgets/wc_logo.dart';
import 'login_screen.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});
  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  bool _notifPush = true;
  bool _notifEmail = true;
  bool _notifSms = false;
  bool _biometric = false;
  String _lang = 'English';

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          // Profile Card
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
            child: Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                children: [
                  Stack(
                    children: [
                      const CircleAvatar(
                        radius: 44,
                        backgroundColor: WC.primary,
                        child: Text('OK', style: TextStyle(color: Colors.white, fontSize: 28, fontWeight: FontWeight.w700)),
                      ),
                      Positioned(
                        bottom: 0, right: 0,
                        child: Container(
                          padding: const EdgeInsets.all(4),
                          decoration: const BoxDecoration(color: Colors.white, shape: BoxShape.circle),
                          child: Container(
                            padding: const EdgeInsets.all(6),
                            decoration: const BoxDecoration(color: WC.primary, shape: BoxShape.circle),
                            child: const Icon(Icons.camera_alt, size: 14, color: Colors.white),
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  const Text('Olena Kovalenko', style: TextStyle(fontSize: 20, fontWeight: FontWeight.w700)),
                  const SizedBox(height: 2),
                  Text('olena.kovalenko@gmail.com', style: TextStyle(color: Colors.grey[600], fontSize: 13)),
                  const SizedBox(height: 4),
                  Text('+48 579 123 456', style: TextStyle(color: Colors.grey[600], fontSize: 13)),
                  const SizedBox(height: 12),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                    decoration: BoxDecoration(color: WC.primary.withAlpha(15), borderRadius: BorderRadius.circular(20)),
                    child: const Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(Icons.verified, size: 16, color: WC.primary),
                        SizedBox(width: 4),
                        Text('Verified Client', style: TextStyle(color: WC.primary, fontSize: 12, fontWeight: FontWeight.w600)),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),

          // Case Info
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Case Information', style: TextStyle(fontSize: 15, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 12),
                  _infoRow('Case Number', '#WC-2026-0847'),
                  _infoRow('Service', 'Temporary Residence Permit'),
                  _infoRow('Status', 'In Progress'),
                  _infoRow('Manager', 'Anya Petrova'),
                  _infoRow('Created', '15 January 2026'),
                  _infoRow('Nationality', 'Ukrainian'),
                  _infoRow('PESEL', '02271012345'),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),

          // Personal Data
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      const Text('Personal Data', style: TextStyle(fontSize: 15, fontWeight: FontWeight.w600)),
                      TextButton.icon(
                        onPressed: () => _showEditDialog(),
                        icon: const Icon(Icons.edit, size: 14),
                        label: const Text('Edit', style: TextStyle(fontSize: 12)),
                        style: TextButton.styleFrom(padding: EdgeInsets.zero, minimumSize: Size.zero, tapTargetSize: MaterialTapTargetSize.shrinkWrap),
                      ),
                    ],
                  ),
                  const SizedBox(height: 12),
                  _infoRow('Full Name', 'Olena Kovalenko'),
                  _infoRow('Date of Birth', '10 July 1995'),
                  _infoRow('Place of Birth', 'Kyiv, Ukraine'),
                  _infoRow('Passport', 'FE 123456'),
                  _infoRow('Address (PL)', 'ul. Marszałkowska 55/12, 00-676 Warszawa'),
                  _infoRow('Voivodeship', 'Mazowieckie'),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),

          // Notifications
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Notifications', style: TextStyle(fontSize: 15, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 8),
                  _toggle('Push Notifications', _notifPush, (v) => setState(() => _notifPush = v)),
                  _toggle('Email Notifications', _notifEmail, (v) => setState(() => _notifEmail = v)),
                  _toggle('SMS Notifications', _notifSms, (v) => setState(() => _notifSms = v)),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),

          // Security
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Security', style: TextStyle(fontSize: 15, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 8),
                  _toggle('Biometric Login', _biometric, (v) => setState(() => _biometric = v)),
                  const SizedBox(height: 8),
                  _actionTile(Icons.lock_outline, 'Change Password', () => _showChangePassword()),
                  _actionTile(Icons.devices, 'Active Sessions', () {}),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),

          // Settings
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Settings', style: TextStyle(fontSize: 15, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 8),
                  _langTile(),
                  _actionTile(Icons.description_outlined, 'Terms & Conditions', () {}),
                  _actionTile(Icons.privacy_tip_outlined, 'Privacy Policy', () {}),
                  _actionTile(Icons.help_outline, 'Help & Support', () {}),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),

          // App Info
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  const WcLogo(fontSize: 22),
                  const SizedBox(height: 4),
                  Text('Version 1.0.0', style: TextStyle(color: Colors.grey[500], fontSize: 11)),
                  const SizedBox(height: 2),
                  Text('© 2026 WinCase Immigration Bureau', style: TextStyle(color: Colors.grey[500], fontSize: 11)),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),

          // Logout
          SizedBox(
            width: double.infinity,
            child: OutlinedButton.icon(
              onPressed: () => _confirmLogout(),
              icon: const Icon(Icons.logout, size: 18, color: WC.danger),
              label: const Text('Sign Out', style: TextStyle(color: WC.danger)),
              style: OutlinedButton.styleFrom(
                side: const BorderSide(color: WC.danger),
                padding: const EdgeInsets.symmetric(vertical: 14),
              ),
            ),
          ),
          const SizedBox(height: 24),
        ],
      ),
    );
  }

  Widget _infoRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 120,
            child: Text(label, style: TextStyle(color: Colors.grey[600], fontSize: 13)),
          ),
          Expanded(child: Text(value, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13))),
        ],
      ),
    );
  }

  Widget _toggle(String label, bool value, ValueChanged<bool> onChanged) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: const TextStyle(fontSize: 13)),
          Switch(value: value, onChanged: onChanged, activeColor: WC.primary),
        ],
      ),
    );
  }

  Widget _actionTile(IconData icon, String title, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 10),
        child: Row(
          children: [
            Icon(icon, size: 20, color: WC.textSecondary),
            const SizedBox(width: 12),
            Expanded(child: Text(title, style: const TextStyle(fontSize: 13))),
            const Icon(Icons.chevron_right, size: 18, color: Colors.grey),
          ],
        ),
      ),
    );
  }

  Widget _langTile() {
    return InkWell(
      onTap: () => _showLangPicker(),
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 10),
        child: Row(
          children: [
            const Icon(Icons.language, size: 20, color: WC.textSecondary),
            const SizedBox(width: 12),
            const Expanded(child: Text('Language', style: TextStyle(fontSize: 13))),
            Text(_lang, style: TextStyle(fontSize: 12, color: Colors.grey[600])),
            const SizedBox(width: 4),
            const Icon(Icons.chevron_right, size: 18, color: Colors.grey),
          ],
        ),
      ),
    );
  }

  void _showLangPicker() {
    final langs = ['English', 'Polski', 'Українська', 'Русский'];
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (_) => Padding(
        padding: const EdgeInsets.all(20),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Center(child: Container(width: 40, height: 4, decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(2)))),
            const SizedBox(height: 16),
            const Text('Select Language', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600)),
            const SizedBox(height: 12),
            ...langs.map((l) => ListTile(
              title: Text(l),
              trailing: l == _lang ? const Icon(Icons.check, color: WC.primary) : null,
              onTap: () {
                setState(() => _lang = l);
                Navigator.pop(context);
              },
            )),
            const SizedBox(height: 8),
          ],
        ),
      ),
    );
  }

  void _showEditDialog() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Edit Personal Data'),
        content: const Text('To update your personal data, please contact your manager Anya Petrova or visit the WinCase office.', style: TextStyle(fontSize: 13)),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('OK')),
          ElevatedButton.icon(
            onPressed: () {
              Navigator.pop(ctx);
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Request sent to your manager'), backgroundColor: WC.primary),
              );
            },
            icon: const Icon(Icons.send, size: 16),
            label: const Text('Request Change'),
          ),
        ],
      ),
    );
  }

  void _showChangePassword() {
    final oldCtrl = TextEditingController();
    final newCtrl = TextEditingController();
    final confirmCtrl = TextEditingController();
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Change Password'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            TextField(controller: oldCtrl, obscureText: true, decoration: const InputDecoration(labelText: 'Current Password')),
            const SizedBox(height: 10),
            TextField(controller: newCtrl, obscureText: true, decoration: const InputDecoration(labelText: 'New Password')),
            const SizedBox(height: 10),
            TextField(controller: confirmCtrl, obscureText: true, decoration: const InputDecoration(labelText: 'Confirm New Password')),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancel')),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(ctx);
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Password changed successfully'), backgroundColor: WC.primary),
              );
            },
            child: const Text('Save'),
          ),
        ],
      ),
    );
  }

  void _confirmLogout() {
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Sign Out'),
        content: const Text('Are you sure you want to sign out?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancel')),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(ctx);
              Navigator.pushAndRemoveUntil(context, MaterialPageRoute(builder: (_) => const LoginScreen()), (_) => false);
            },
            style: ElevatedButton.styleFrom(backgroundColor: WC.danger),
            child: const Text('Sign Out'),
          ),
        ],
      ),
    );
  }
}
