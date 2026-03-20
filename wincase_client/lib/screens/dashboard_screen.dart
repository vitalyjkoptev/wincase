import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import '../widgets/stat_card.dart';

class DashboardScreen extends StatelessWidget {
  const DashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Welcome Card
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              gradient: const LinearGradient(colors: [WC.primary, WC.primaryDark]),
              borderRadius: BorderRadius.circular(16),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text('Welcome back, Olena!', style: TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.w700)),
                const SizedBox(height: 4),
                Text('Your immigration case is being processed', style: TextStyle(color: Colors.white.withAlpha(180), fontSize: 13)),
                const SizedBox(height: 12),
                Row(
                  children: [
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(20)),
                      child: const Text('Case #WC-2026-0847', style: TextStyle(color: WC.primary, fontSize: 12, fontWeight: FontWeight.w600)),
                    ),
                    const SizedBox(width: 8),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                      decoration: BoxDecoration(color: Colors.white.withAlpha(50), borderRadius: BorderRadius.circular(20)),
                      child: const Text('Temp. Residence Permit', style: TextStyle(color: Colors.white, fontSize: 12)),
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                Row(
                  mainAxisAlignment: MainAxisAlignment.end,
                  children: [
                    const Text('68%', style: TextStyle(color: Colors.white, fontSize: 36, fontWeight: FontWeight.w800)),
                    const SizedBox(width: 8),
                    Text('progress', style: TextStyle(color: Colors.white.withAlpha(180), fontSize: 13)),
                  ],
                ),
              ],
            ),
          ),

          const SizedBox(height: 16),

          // Stat Cards
          GridView.count(
            crossAxisCount: 2,
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            childAspectRatio: 1.5,
            crossAxisSpacing: 8,
            mainAxisSpacing: 8,
            children: const [
              StatCard(icon: Icons.description_outlined, color: Colors.blue, value: '12', label: 'Documents'),
              StatCard(icon: Icons.access_time, color: WC.warning, value: '3', label: 'Pending'),
              StatCard(icon: Icons.message_outlined, color: WC.primary, value: '2', label: 'Messages'),
              StatCard(icon: Icons.calendar_today, color: WC.info, value: '14 Mar', label: 'Appointment'),
            ],
          ),

          const SizedBox(height: 20),

          // Case Progress Timeline
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
                      const Text('Case Progress', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600)),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                        decoration: BoxDecoration(color: WC.warning.withAlpha(30), borderRadius: BorderRadius.circular(12)),
                        child: const Text('Stage 4/7', style: TextStyle(color: WC.warning, fontSize: 11, fontWeight: FontWeight.w600)),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  _timelineItem('Application Submitted', '15 Jan 2026', true, false),
                  _timelineItem('Awaiting Fingerprints', '22 Jan 2026', true, false),
                  _timelineItem('Fingerprint Appointment', '5 Feb 2026', true, false),
                  _timelineItem('Fingerprints Submitted', '5 Feb 2026', false, true),
                  _timelineItem('Awaiting Decision', 'Est. Mar-Apr 2026', false, false),
                  _timelineItem('Decision Signed', '—', false, false),
                  _timelineItem('Card Issued', '—', false, false, isLast: true),
                ],
              ),
            ),
          ),

          const SizedBox(height: 16),

          // Pending Actions
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Row(
                    children: [
                      Icon(Icons.warning_amber_rounded, color: WC.warning, size: 20),
                      SizedBox(width: 8),
                      Text('Pending Actions', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600)),
                    ],
                  ),
                  const SizedBox(height: 12),
                  _actionItem(Icons.upload_file, WC.danger, 'Upload Bank Statement', 'Last 3 months required', 'Due: 5 Mar 2026'),
                  const Divider(height: 20),
                  _actionItem(Icons.edit_document, WC.warning, 'Sign Employment Certificate', 'Employer certificate needs signature', 'Due: 10 Mar 2026'),
                  const Divider(height: 20),
                  _actionItem(Icons.event_available, WC.info, 'Confirm Appointment', 'WinCase office — 14 Mar, 10:00', '14 Mar 2026'),
                ],
              ),
            ),
          ),

          const SizedBox(height: 16),

          // Your Manager
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Your Manager', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 12),
                  Row(
                    children: [
                      const CircleAvatar(radius: 24, backgroundColor: WC.primary, child: Text('AP', style: TextStyle(color: Colors.white, fontWeight: FontWeight.w700))),
                      const SizedBox(width: 12),
                      const Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text('Anya Petrova', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 15)),
                            Text('Senior Immigration Consultant', style: TextStyle(color: Colors.grey, fontSize: 12)),
                          ],
                        ),
                      ),
                      IconButton(onPressed: null, icon: Icon(Icons.phone, color: WC.primary)),
                      IconButton(onPressed: null, icon: Icon(Icons.message, color: WC.primary)),
                    ],
                  ),
                ],
              ),
            ),
          ),

          const SizedBox(height: 16),

          // Payments
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Payments', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 12),
                  _payRow('Total Service Fee', '3 500 PLN', Colors.black87),
                  _payRow('Paid', '2 000 PLN', WC.primary),
                  _payRow('Remaining', '1 500 PLN', WC.danger),
                  const SizedBox(height: 8),
                  ClipRRect(
                    borderRadius: BorderRadius.circular(4),
                    child: LinearProgressIndicator(value: 0.57, backgroundColor: Colors.grey[200], color: WC.primary, minHeight: 6),
                  ),
                  const SizedBox(height: 4),
                  Text('57% paid — next payment 20 Mar 2026', style: TextStyle(color: Colors.grey[600], fontSize: 11)),
                ],
              ),
            ),
          ),

          const SizedBox(height: 24),
        ],
      ),
    );
  }

  static Widget _timelineItem(String title, String subtitle, bool done, bool current, {bool isLast = false}) {
    final dotColor = done ? WC.primary : current ? WC.warning : Colors.grey[300]!;
    return IntrinsicHeight(
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 30,
            child: Column(
              children: [
                Container(
                  width: 14, height: 14,
                  decoration: BoxDecoration(
                    shape: BoxShape.circle,
                    color: dotColor,
                    border: current ? Border.all(color: WC.warning.withAlpha(80), width: 3) : null,
                  ),
                  child: done ? const Icon(Icons.check, size: 10, color: Colors.white) : null,
                ),
                if (!isLast) Expanded(child: Container(width: 2, color: done ? WC.primary : Colors.grey[300])),
              ],
            ),
          ),
          Expanded(
            child: Padding(
              padding: EdgeInsets.only(bottom: isLast ? 0 : 16, left: 8),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(title, style: TextStyle(fontWeight: FontWeight.w600, fontSize: 13, color: (done || current) ? Colors.black87 : Colors.grey)),
                  Text(subtitle, style: TextStyle(fontSize: 11, color: Colors.grey[500])),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  static Widget _actionItem(IconData icon, Color color, String title, String desc, String due) {
    return Row(
      children: [
        Container(
          width: 40, height: 40,
          decoration: BoxDecoration(color: color.withAlpha(25), borderRadius: BorderRadius.circular(8)),
          child: Icon(icon, color: color, size: 20),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(title, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
              Text(desc, style: TextStyle(fontSize: 11, color: Colors.grey[600])),
              const SizedBox(height: 2),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                decoration: BoxDecoration(color: color.withAlpha(20), borderRadius: BorderRadius.circular(8)),
                child: Text(due, style: TextStyle(color: color, fontSize: 10, fontWeight: FontWeight.w600)),
              ),
            ],
          ),
        ),
      ],
    );
  }

  static Widget _payRow(String label, String value, Color color) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(label, style: TextStyle(color: Colors.grey[600], fontSize: 13)),
          Text(value, style: TextStyle(color: color, fontWeight: FontWeight.w600, fontSize: 14)),
        ],
      ),
    );
  }
}
