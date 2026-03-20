import 'package:flutter/material.dart';
import '../theme/app_theme.dart';

class DocumentsScreen extends StatefulWidget {
  const DocumentsScreen({super.key});
  @override
  State<DocumentsScreen> createState() => _DocumentsScreenState();
}

class _DocumentsScreenState extends State<DocumentsScreen> with SingleTickerProviderStateMixin {
  late TabController _tabCtrl;
  final _categories = ['All', 'Required', 'Uploaded', 'Approved', 'Rejected'];
  int _selected = 0;

  final _docs = <Map<String, dynamic>>[
    {'name': 'Passport Copy', 'status': 'approved', 'date': '15 Jan 2026', 'size': '2.4 MB', 'icon': Icons.badge_outlined},
    {'name': 'Bank Statement (Jan)', 'status': 'approved', 'date': '20 Jan 2026', 'size': '1.1 MB', 'icon': Icons.account_balance_outlined},
    {'name': 'Bank Statement (Feb)', 'status': 'uploaded', 'date': '28 Feb 2026', 'size': '1.3 MB', 'icon': Icons.account_balance_outlined},
    {'name': 'Bank Statement (Mar)', 'status': 'required', 'date': 'Due: 5 Mar', 'size': '—', 'icon': Icons.account_balance_outlined},
    {'name': 'Employment Certificate', 'status': 'required', 'date': 'Due: 10 Mar', 'size': '—', 'icon': Icons.work_outline},
    {'name': 'Health Insurance', 'status': 'approved', 'date': '18 Jan 2026', 'size': '0.8 MB', 'icon': Icons.health_and_safety_outlined},
    {'name': 'Rental Agreement', 'status': 'approved', 'date': '15 Jan 2026', 'size': '3.2 MB', 'icon': Icons.home_outlined},
    {'name': 'Photo 3.5x4.5', 'status': 'approved', 'date': '15 Jan 2026', 'size': '0.5 MB', 'icon': Icons.photo_camera_outlined},
    {'name': 'Tax Declaration (PIT)', 'status': 'uploaded', 'date': '1 Mar 2026', 'size': '0.9 MB', 'icon': Icons.receipt_long_outlined},
    {'name': 'University Diploma', 'status': 'rejected', 'date': '20 Jan 2026', 'size': '1.7 MB', 'icon': Icons.school_outlined},
    {'name': 'PESEL Confirmation', 'status': 'approved', 'date': '16 Jan 2026', 'size': '0.3 MB', 'icon': Icons.pin_outlined},
    {'name': 'Fingerprint Confirmation', 'status': 'approved', 'date': '5 Feb 2026', 'size': '0.4 MB', 'icon': Icons.fingerprint},
  ];

  @override
  void initState() {
    super.initState();
    _tabCtrl = TabController(length: _categories.length, vsync: this);
  }

  @override
  void dispose() {
    _tabCtrl.dispose();
    super.dispose();
  }

  List<Map<String, dynamic>> get _filtered {
    if (_selected == 0) return _docs;
    final status = _categories[_selected].toLowerCase();
    return _docs.where((d) => d['status'] == status).toList();
  }

  Color _statusColor(String s) {
    switch (s) {
      case 'approved': return WC.primary;
      case 'uploaded': return WC.info;
      case 'required': return WC.warning;
      case 'rejected': return WC.danger;
      default: return Colors.grey;
    }
  }

  IconData _statusIcon(String s) {
    switch (s) {
      case 'approved': return Icons.check_circle;
      case 'uploaded': return Icons.cloud_done;
      case 'required': return Icons.upload_file;
      case 'rejected': return Icons.cancel;
      default: return Icons.circle;
    }
  }

  @override
  Widget build(BuildContext context) {
    final required = _docs.where((d) => d['status'] == 'required').length;
    final uploaded = _docs.where((d) => d['status'] == 'uploaded').length;
    final approved = _docs.where((d) => d['status'] == 'approved').length;
    final rejected = _docs.where((d) => d['status'] == 'rejected').length;

    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Summary Row
          Row(
            children: [
              _miniStat('Total', '${_docs.length}', Colors.grey),
              const SizedBox(width: 8),
              _miniStat('Required', '$required', WC.warning),
              const SizedBox(width: 8),
              _miniStat('Pending', '$uploaded', WC.info),
              const SizedBox(width: 8),
              _miniStat('Done', '$approved', WC.primary),
            ],
          ),
          const SizedBox(height: 16),

          // Filter Chips
          SizedBox(
            height: 36,
            child: ListView.separated(
              scrollDirection: Axis.horizontal,
              itemCount: _categories.length,
              separatorBuilder: (_, __) => const SizedBox(width: 8),
              itemBuilder: (_, i) => ChoiceChip(
                label: Text(_categories[i], style: TextStyle(fontSize: 12, color: _selected == i ? Colors.white : WC.textPrimary)),
                selected: _selected == i,
                selectedColor: WC.primary,
                backgroundColor: Colors.white,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
                onSelected: (_) => setState(() => _selected = i),
              ),
            ),
          ),
          const SizedBox(height: 16),

          // Docs List
          ..._filtered.map((doc) => _docCard(doc)),

          const SizedBox(height: 16),

          // Upload Button
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: () => _showUploadSheet(),
              icon: const Icon(Icons.cloud_upload_outlined, size: 20),
              label: const Text('Upload Document'),
            ),
          ),

          // Rejected Notice
          if (rejected > 0) ...[
            const SizedBox(height: 16),
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: WC.danger.withAlpha(15),
                borderRadius: BorderRadius.circular(12),
                border: Border.all(color: WC.danger.withAlpha(40)),
              ),
              child: Row(
                children: [
                  const Icon(Icons.info_outline, color: WC.danger, size: 20),
                  const SizedBox(width: 10),
                  Expanded(
                    child: Text(
                      '$rejected document(s) rejected — please re-upload corrected versions.',
                      style: const TextStyle(fontSize: 12, color: WC.danger),
                    ),
                  ),
                ],
              ),
            ),
          ],
          const SizedBox(height: 24),
        ],
      ),
    );
  }

  Widget _miniStat(String label, String value, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 10),
        decoration: BoxDecoration(
          color: color.withAlpha(15),
          borderRadius: BorderRadius.circular(10),
        ),
        child: Column(
          children: [
            Text(value, style: TextStyle(color: color, fontSize: 18, fontWeight: FontWeight.w700)),
            const SizedBox(height: 2),
            Text(label, style: TextStyle(color: color, fontSize: 10, fontWeight: FontWeight.w500)),
          ],
        ),
      ),
    );
  }

  Widget _docCard(Map<String, dynamic> doc) {
    final color = _statusColor(doc['status']);
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: InkWell(
        borderRadius: BorderRadius.circular(12),
        onTap: () => _showDocDetail(doc),
        child: Padding(
          padding: const EdgeInsets.all(14),
          child: Row(
            children: [
              Container(
                width: 44, height: 44,
                decoration: BoxDecoration(color: color.withAlpha(20), borderRadius: BorderRadius.circular(10)),
                child: Icon(doc['icon'], color: color, size: 22),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(doc['name'], style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                    const SizedBox(height: 4),
                    Row(
                      children: [
                        Icon(_statusIcon(doc['status']), size: 12, color: color),
                        const SizedBox(width: 4),
                        Text(doc['status'][0].toUpperCase() + doc['status'].substring(1),
                            style: TextStyle(fontSize: 11, color: color, fontWeight: FontWeight.w600)),
                        const SizedBox(width: 10),
                        Text(doc['date'], style: TextStyle(fontSize: 11, color: Colors.grey[500])),
                      ],
                    ),
                  ],
                ),
              ),
              if (doc['status'] == 'required')
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                  decoration: BoxDecoration(color: WC.warning, borderRadius: BorderRadius.circular(6)),
                  child: const Text('Upload', style: TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w600)),
                )
              else
                Text(doc['size'], style: TextStyle(fontSize: 11, color: Colors.grey[500])),
            ],
          ),
        ),
      ),
    );
  }

  void _showDocDetail(Map<String, dynamic> doc) {
    final color = _statusColor(doc['status']);
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (_) => Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Center(child: Container(width: 40, height: 4, decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(2)))),
            const SizedBox(height: 16),
            Row(
              children: [
                Container(
                  width: 48, height: 48,
                  decoration: BoxDecoration(color: color.withAlpha(20), borderRadius: BorderRadius.circular(12)),
                  child: Icon(doc['icon'], color: color, size: 24),
                ),
                const SizedBox(width: 14),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(doc['name'], style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 16)),
                      const SizedBox(height: 4),
                      Row(children: [
                        Icon(_statusIcon(doc['status']), size: 14, color: color),
                        const SizedBox(width: 4),
                        Text(doc['status'][0].toUpperCase() + doc['status'].substring(1),
                            style: TextStyle(color: color, fontWeight: FontWeight.w600, fontSize: 13)),
                      ]),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 20),
            _detailRow('Date', doc['date']),
            _detailRow('File Size', doc['size']),
            _detailRow('Case', '#WC-2026-0847'),
            if (doc['status'] == 'rejected')
              _detailRow('Reason', 'Translation missing — please attach sworn translation.'),
            const SizedBox(height: 20),
            if (doc['status'] == 'required')
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  onPressed: () { Navigator.pop(context); _showUploadSheet(); },
                  icon: const Icon(Icons.upload, size: 18),
                  label: const Text('Upload Now'),
                ),
              )
            else if (doc['status'] == 'rejected')
              SizedBox(
                width: double.infinity,
                child: ElevatedButton.icon(
                  onPressed: () { Navigator.pop(context); _showUploadSheet(); },
                  icon: const Icon(Icons.refresh, size: 18),
                  label: const Text('Re-Upload'),
                  style: ElevatedButton.styleFrom(backgroundColor: WC.danger),
                ),
              )
            else
              SizedBox(
                width: double.infinity,
                child: OutlinedButton.icon(
                  onPressed: () => Navigator.pop(context),
                  icon: const Icon(Icons.visibility_outlined, size: 18),
                  label: const Text('View Document'),
                ),
              ),
            const SizedBox(height: 8),
          ],
        ),
      ),
    );
  }

  Widget _detailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(color: Colors.grey[600], fontSize: 13)),
          Flexible(child: Text(value, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13), textAlign: TextAlign.end)),
        ],
      ),
    );
  }

  void _showUploadSheet() {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(20))),
      builder: (_) => Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Center(child: Container(width: 40, height: 4, decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(2)))),
            const SizedBox(height: 16),
            const Text('Upload Document', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w700)),
            const SizedBox(height: 20),
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(32),
              decoration: BoxDecoration(
                color: WC.primary.withAlpha(10),
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: WC.primary.withAlpha(40), style: BorderStyle.solid),
              ),
              child: Column(
                children: [
                  Icon(Icons.cloud_upload_outlined, size: 48, color: WC.primary.withAlpha(150)),
                  const SizedBox(height: 12),
                  const Text('Tap to select file', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 14)),
                  const SizedBox(height: 4),
                  Text('PDF, JPG, PNG — max 10 MB', style: TextStyle(fontSize: 12, color: Colors.grey[500])),
                ],
              ),
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(
                  child: OutlinedButton.icon(
                    onPressed: () => Navigator.pop(context),
                    icon: const Icon(Icons.camera_alt_outlined, size: 18),
                    label: const Text('Camera'),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: ElevatedButton.icon(
                    onPressed: () {
                      Navigator.pop(context);
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Document upload started...'), backgroundColor: WC.primary),
                      );
                    },
                    icon: const Icon(Icons.folder_open_outlined, size: 18),
                    label: const Text('Files'),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 8),
          ],
        ),
      ),
    );
  }
}
