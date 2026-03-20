// =====================================================
// FILE: lib/screens/leads/lead_create_screen.dart
// Create new lead form
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../providers/leads_provider.dart';

class LeadCreateScreen extends ConsumerStatefulWidget {
  const LeadCreateScreen({super.key});

  @override
  ConsumerState<LeadCreateScreen> createState() => _LeadCreateScreenState();
}

class _LeadCreateScreenState extends ConsumerState<LeadCreateScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameCtrl = TextEditingController();
  final _phoneCtrl = TextEditingController();
  final _emailCtrl = TextEditingController();
  final _notesCtrl = TextEditingController();
  String _source = 'walk_in';
  String _serviceType = 'legalization';
  String _language = 'pl';
  bool _submitting = false;

  static const _sources = [
    'walk_in',
    'phone_call',
    'google_ads',
    'facebook_ads',
    'tiktok_ads',
    'organic_search',
    'referral',
    'whatsapp',
    'telegram',
    'threads',
  ];
  static const _services = [
    'legalization',
    'work_permit',
    'residence_card',
    'citizenship',
    'family_reunion',
    'blue_card',
    'business',
    'consultation',
  ];
  static const _languages = ['pl', 'en', 'ru', 'ua', 'hi', 'tl', 'es', 'tr'];

  @override
  void dispose() {
    _nameCtrl.dispose();
    _phoneCtrl.dispose();
    _emailCtrl.dispose();
    _notesCtrl.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => _submitting = true);

    final ok = await ref.read(leadsProvider.notifier).createLead({
      'name': _nameCtrl.text.trim(),
      'phone': _phoneCtrl.text.trim(),
      'email':
          _emailCtrl.text.trim().isNotEmpty ? _emailCtrl.text.trim() : null,
      'source': _source,
      'service_type': _serviceType,
      'language': _language,
      'notes':
          _notesCtrl.text.trim().isNotEmpty ? _notesCtrl.text.trim() : null,
    });

    setState(() => _submitting = false);

    if (ok && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Lead created successfully')),
      );
      Navigator.of(context).pop();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('New Lead')),
      body: Form(
        key: _formKey,
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            TextFormField(
              controller: _nameCtrl,
              decoration: const InputDecoration(
                labelText: 'Full Name *',
                border: OutlineInputBorder(),
              ),
              validator:
                  (v) =>
                      v != null && v.trim().length >= 2
                          ? null
                          : 'Name required',
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _phoneCtrl,
              keyboardType: TextInputType.phone,
              decoration: const InputDecoration(
                labelText: 'Phone *',
                border: OutlineInputBorder(),
                hintText: '+48...',
              ),
              validator:
                  (v) =>
                      v != null && v.trim().length >= 9
                          ? null
                          : 'Valid phone required',
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _emailCtrl,
              keyboardType: TextInputType.emailAddress,
              decoration: const InputDecoration(
                labelText: 'Email (optional)',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              initialValue: _source,
              decoration: const InputDecoration(
                labelText: 'Source',
                border: OutlineInputBorder(),
              ),
              items:
                  _sources
                      .map(
                        (s) => DropdownMenuItem(
                          value: s,
                          child: Text(s.replaceAll('_', ' ')),
                        ),
                      )
                      .toList(),
              onChanged: (v) => setState(() => _source = v!),
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              initialValue: _serviceType,
              decoration: const InputDecoration(
                labelText: 'Service Type',
                border: OutlineInputBorder(),
              ),
              items:
                  _services
                      .map(
                        (s) => DropdownMenuItem(
                          value: s,
                          child: Text(s.replaceAll('_', ' ')),
                        ),
                      )
                      .toList(),
              onChanged: (v) => setState(() => _serviceType = v!),
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              initialValue: _language,
              decoration: const InputDecoration(
                labelText: 'Language',
                border: OutlineInputBorder(),
              ),
              items:
                  _languages
                      .map(
                        (l) => DropdownMenuItem(
                          value: l,
                          child: Text(l.toUpperCase()),
                        ),
                      )
                      .toList(),
              onChanged: (v) => setState(() => _language = v!),
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _notesCtrl,
              maxLines: 3,
              decoration: const InputDecoration(
                labelText: 'Notes (optional)',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 24),
            SizedBox(
              height: 52,
              child: FilledButton(
                onPressed: _submitting ? null : _submit,
                child:
                    _submitting
                        ? const SizedBox(
                          width: 24,
                          height: 24,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            color: Colors.white,
                          ),
                        )
                        : const Text(
                          'Create Lead',
                          style: TextStyle(fontSize: 16),
                        ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// LeadCreateScreen — форма создания нового лида.
// Поля: Name*, Phone*, Email, Source (dropdown 10), Service (dropdown 8),
// Language (dropdown 8), Notes.
// POST /leads → при успехе → pop + snackbar.
// Файл: lib/screens/leads/lead_create_screen.dart
// ---------------------------------------------------------------
