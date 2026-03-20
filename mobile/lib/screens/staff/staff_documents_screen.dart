// =====================================================
// WINCASE CRM -- Staff Documents Screen
// Filter by type / expiring, upload FAB, request document
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:image_picker/image_picker.dart';
import '../../providers/staff_provider.dart';
import '../../models/staff_models.dart';
import '../../providers/auth_provider.dart';

class StaffDocumentsScreen extends ConsumerStatefulWidget {
  const StaffDocumentsScreen({super.key});

  @override
  ConsumerState<StaffDocumentsScreen> createState() => _StaffDocumentsScreenState();
}

class _StaffDocumentsScreenState extends ConsumerState<StaffDocumentsScreen> {
  String _typeFilter = 'all';
  bool _expiringOnly = false;

  static const _docTypes = [
    'all', 'passport', 'visa', 'residence_card', 'work_permit',
    'pesel', 'meldunek', 'contract', 'bank_statement',
  ];

  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(documentsProvider.notifier).load());
  }

  void _onTypeChanged(String type) {
    setState(() => _typeFilter = type);
    _reload();
  }

  void _toggleExpiring() {
    setState(() => _expiringOnly = !_expiringOnly);
    _reload();
  }

  void _reload() {
    ref.read(documentsProvider.notifier).load(
      type: _typeFilter == 'all' ? null : _typeFilter,
      expiring: _expiringOnly ? true : null,
    );
  }

  Future<void> _pickAndUpload(ImageSource source) async {
    final picker = ImagePicker();
    final file = await picker.pickImage(source: source, imageQuality: 85);
    if (file == null) return;
    if (!mounted) return;

    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Uploading document...')),
    );

    final ok = await ref.read(documentsProvider.notifier).uploadDocument(
      clientId: 0, // Will be selected in a real flow
      filePath: file.path,
      type: _typeFilter == 'all' ? null : _typeFilter,
      name: file.name,
    );

    if (!mounted) return;
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(ok ? 'Document uploaded!' : 'Upload failed')),
    );
  }

  void _showUploadDialog() {
    showModalBottomSheet(
      context: context,
      builder: (ctx) => Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Upload Document', style: Theme.of(ctx).textTheme.titleMedium),
            const SizedBox(height: 16),
            ListTile(
              leading: const Icon(Icons.photo_camera),
              title: const Text('Take Photo'),
              subtitle: const Text('Capture document with camera'),
              onTap: () {
                Navigator.pop(ctx);
                _pickAndUpload(ImageSource.camera);
              },
            ),
            ListTile(
              leading: const Icon(Icons.photo_library),
              title: const Text('Choose from Gallery'),
              subtitle: const Text('Select image from gallery'),
              onTap: () {
                Navigator.pop(ctx);
                _pickAndUpload(ImageSource.gallery);
              },
            ),
            ListTile(
              leading: const Icon(Icons.insert_drive_file),
              title: const Text('Pick File'),
              subtitle: const Text('Select PDF or other document'),
              onTap: () {
                Navigator.pop(ctx);
                _pickAndUpload(ImageSource.gallery);
              },
            ),
          ],
        ),
      ),
    );
  }

  void _showRequestDialog() {
    final clientIdController = TextEditingController();
    String selectedType = 'passport';

    showDialog(
      context: context,
      builder: (ctx) => StatefulBuilder(
        builder: (ctx, setStateDialog) => AlertDialog(
          title: const Text('Request Document'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                controller: clientIdController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(
                  labelText: 'Client ID',
                  border: OutlineInputBorder(),
                ),
              ),
              const SizedBox(height: 12),
              DropdownButtonFormField<String>(
                value: selectedType,
                decoration: const InputDecoration(
                  labelText: 'Document Type',
                  border: OutlineInputBorder(),
                ),
                items: _docTypes.where((t) => t != 'all').map((t) => DropdownMenuItem(
                  value: t,
                  child: Text(_typeLabel(t)),
                )).toList(),
                onChanged: (val) {
                  if (val != null) setStateDialog(() => selectedType = val);
                },
              ),
            ],
          ),
          actions: [
            TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancel')),
            FilledButton(
              onPressed: () {
                final clientId = int.tryParse(clientIdController.text);
                if (clientId != null) {
                  ref.read(documentsProvider.notifier).requestFromClient(clientId, selectedType);
                  Navigator.pop(ctx);
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Document request sent')),
                  );
                }
              },
              child: const Text('Request'),
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(documentsProvider);
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Documents'),
        actions: [
          // Expiring toggle
          IconButton(
            icon: Icon(
              Icons.timer,
              color: _expiringOnly ? colorScheme.error : null,
            ),
            onPressed: _toggleExpiring,
            tooltip: _expiringOnly ? 'Show all' : 'Show expiring only',
          ),
          // Request document
          IconButton(
            icon: const Icon(Icons.request_page),
            onPressed: _showRequestDialog,
            tooltip: 'Request Document',
          ),
        ],
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(52),
          child: SizedBox(
            height: 52,
            child: ListView(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              children: _docTypes.map((type) {
                final isSelected = _typeFilter == type;
                return Padding(
                  padding: const EdgeInsets.only(right: 8),
                  child: FilterChip(
                    label: Text(_typeLabel(type)),
                    selected: isSelected,
                    onSelected: (_) => _onTypeChanged(type),
                  ),
                );
              }).toList(),
            ),
          ),
        ),
      ),
      body: _buildBody(state, theme),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: _showUploadDialog,
        icon: const Icon(Icons.upload_file),
        label: const Text('Upload'),
      ),
    );
  }

  Widget _buildBody(DocumentsState state, ThemeData theme) {
    if (state.isLoading) {
      return const Center(child: CircularProgressIndicator());
    }

    if (state.documents.isEmpty) {
      return Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.description_outlined, size: 64, color: theme.colorScheme.outline),
            const SizedBox(height: 16),
            Text('No documents found', style: theme.textTheme.titleMedium),
            if (_expiringOnly)
              Text(
                'No expiring documents',
                style: theme.textTheme.bodySmall?.copyWith(color: theme.colorScheme.outline),
              ),
          ],
        ),
      );
    }

    return RefreshIndicator(
      onRefresh: () async => _reload(),
      child: ListView.builder(
        padding: const EdgeInsets.fromLTRB(16, 16, 16, 80),
        itemCount: state.documents.length,
        itemBuilder: (context, index) => _DocumentCard(document: state.documents[index]),
      ),
    );
  }

  String _typeLabel(String type) => switch (type) {
    'all'            => 'All',
    'passport'       => 'Passport',
    'visa'           => 'Visa',
    'residence_card' => 'Karta Pobytu',
    'work_permit'    => 'Work Permit',
    'pesel'          => 'PESEL',
    'meldunek'       => 'Meldunek',
    'contract'       => 'Contract',
    'bank_statement' => 'Bank Statement',
    _                => type,
  };
}

// =====================================================
// DOCUMENT CARD
// =====================================================

class _DocumentCard extends StatelessWidget {
  final StaffDocument document;

  const _DocumentCard({required this.document});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;
    final isExpiring = document.isExpiring;
    final isExpired = document.isExpired;

    return Card(
      elevation: 0,
      margin: const EdgeInsets.only(bottom: 8),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
        side: BorderSide(
          color: isExpired
              ? colorScheme.error.withOpacity(0.5)
              : isExpiring
                  ? Colors.orange.withOpacity(0.5)
                  : colorScheme.outlineVariant,
          width: (isExpired || isExpiring) ? 1.5 : 0.5,
        ),
      ),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Row(
          children: [
            // Doc type icon
            Container(
              width: 44,
              height: 44,
              decoration: BoxDecoration(
                color: _typeColor(document.type).withOpacity(0.1),
                borderRadius: BorderRadius.circular(10),
              ),
              child: Icon(
                _typeIcon(document.type),
                color: _typeColor(document.type),
                size: 22,
              ),
            ),
            const SizedBox(width: 12),

            // Info
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: Text(
                          document.name,
                          style: theme.textTheme.bodyMedium?.copyWith(fontWeight: FontWeight.w600),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                      ),
                      // Type badge
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                        decoration: BoxDecoration(
                          color: _typeColor(document.type).withOpacity(0.1),
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: Text(
                          document.typeLabel,
                          style: TextStyle(
                            fontSize: 10,
                            fontWeight: FontWeight.w600,
                            color: _typeColor(document.type),
                          ),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 4),

                  // Client
                  if (document.client != null)
                    Row(
                      children: [
                        Icon(Icons.person, size: 12, color: colorScheme.outline),
                        const SizedBox(width: 2),
                        Text(
                          document.client!.displayName,
                          style: theme.textTheme.labelSmall?.copyWith(color: colorScheme.outline),
                        ),
                      ],
                    ),
                  const SizedBox(height: 4),

                  // Status + Expiry
                  Row(
                    children: [
                      if (document.status != null)
                        _StatusChip(status: document.status!),
                      if (document.expiresAt != null) ...[
                        const SizedBox(width: 8),
                        Icon(
                          Icons.event,
                          size: 12,
                          color: isExpired
                              ? colorScheme.error
                              : isExpiring
                                  ? Colors.orange
                                  : colorScheme.outline,
                        ),
                        const SizedBox(width: 2),
                        Text(
                          isExpired
                              ? 'Expired'
                              : isExpiring
                                  ? 'Expires ${_daysUntil(document.expiresAt!)}d'
                                  : _formatDate(document.expiresAt!),
                          style: theme.textTheme.labelSmall?.copyWith(
                            color: isExpired
                                ? colorScheme.error
                                : isExpiring
                                    ? Colors.orange.shade800
                                    : colorScheme.outline,
                            fontWeight: (isExpired || isExpiring) ? FontWeight.bold : null,
                          ),
                        ),
                      ],
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  IconData _typeIcon(String type) => switch (type) {
    'passport'       => Icons.badge,
    'visa'           => Icons.card_travel,
    'residence_card' => Icons.credit_card,
    'work_permit'    => Icons.work,
    'pesel'          => Icons.numbers,
    'meldunek'       => Icons.home,
    'contract'       => Icons.description,
    'bank_statement' => Icons.account_balance,
    _                => Icons.insert_drive_file,
  };

  Color _typeColor(String type) => switch (type) {
    'passport'       => Colors.indigo,
    'visa'           => Colors.purple,
    'residence_card' => Colors.teal,
    'work_permit'    => Colors.blue,
    'pesel'          => Colors.green,
    'meldunek'       => Colors.orange,
    'contract'       => Colors.brown,
    'bank_statement' => Colors.cyan,
    _                => Colors.grey,
  };

  String _formatDate(DateTime date) {
    return '${date.day.toString().padLeft(2, '0')}.${date.month.toString().padLeft(2, '0')}.${date.year}';
  }

  int _daysUntil(DateTime date) {
    return date.difference(DateTime.now()).inDays;
  }
}

// =====================================================
// STATUS CHIP
// =====================================================

class _StatusChip extends StatelessWidget {
  final String status;

  const _StatusChip({required this.status});

  @override
  Widget build(BuildContext context) {
    final (Color bg, Color fg) = switch (status) {
      'valid'    => (Colors.green.shade50, Colors.green.shade800),
      'pending'  => (Colors.orange.shade50, Colors.orange.shade800),
      'expired'  => (Colors.red.shade50, Colors.red.shade800),
      'rejected' => (Colors.red.shade50, Colors.red.shade800),
      'uploaded' => (Colors.blue.shade50, Colors.blue.shade800),
      _          => (Colors.grey.shade100, Colors.grey.shade700),
    };

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(4),
      ),
      child: Text(
        status[0].toUpperCase() + status.substring(1),
        style: TextStyle(fontSize: 10, fontWeight: FontWeight.w600, color: fg),
      ),
    );
  }
}
