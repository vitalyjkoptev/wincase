import 'package:flutter/material.dart';
import '../theme/app_theme.dart';

class PaymentsScreen extends StatelessWidget {
  const PaymentsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      padding: const EdgeInsets.all(16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Balance Card
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
                Text('Outstanding Balance', style: TextStyle(color: Colors.white.withAlpha(180), fontSize: 13)),
                const SizedBox(height: 4),
                const Text('1 500 PLN', style: TextStyle(color: Colors.white, fontSize: 32, fontWeight: FontWeight.w800)),
                const SizedBox(height: 12),
                Row(
                  children: [
                    _infoPill('Total', '3 500 PLN'),
                    const SizedBox(width: 8),
                    _infoPill('Paid', '2 000 PLN'),
                  ],
                ),
                const SizedBox(height: 16),
                ClipRRect(
                  borderRadius: BorderRadius.circular(4),
                  child: LinearProgressIndicator(
                    value: 0.57,
                    backgroundColor: Colors.white.withAlpha(60),
                    color: Colors.white,
                    minHeight: 6,
                  ),
                ),
                const SizedBox(height: 6),
                Text('57% paid • Next payment: 20 Mar 2026', style: TextStyle(color: Colors.white.withAlpha(180), fontSize: 11)),
              ],
            ),
          ),
          const SizedBox(height: 16),

          // Pay Now Button
          SizedBox(
            width: double.infinity,
            child: ElevatedButton.icon(
              onPressed: () => _showPayDialog(context),
              icon: const Icon(Icons.payment, size: 20),
              label: const Text('Make Payment'),
              style: ElevatedButton.styleFrom(padding: const EdgeInsets.symmetric(vertical: 14)),
            ),
          ),
          const SizedBox(height: 20),

          // Payment Schedule
          const Text('Payment Schedule', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600)),
          const SizedBox(height: 12),
          _scheduleCard('1st Installment', '1 000 PLN', '15 Jan 2026', 'paid'),
          _scheduleCard('2nd Installment', '1 000 PLN', '15 Feb 2026', 'paid'),
          _scheduleCard('3rd Installment', '1 000 PLN', '20 Mar 2026', 'upcoming'),
          _scheduleCard('Final Payment', '500 PLN', '20 Apr 2026', 'pending'),

          const SizedBox(height: 20),

          // Transaction History
          const Text('Transaction History', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600)),
          const SizedBox(height: 12),
          _transactionItem('Bank Transfer', '1 000 PLN', '15 Jan 2026', Icons.account_balance, WC.primary),
          _transactionItem('Card Payment', '500 PLN', '20 Jan 2026', Icons.credit_card, WC.info),
          _transactionItem('Bank Transfer', '500 PLN', '15 Feb 2026', Icons.account_balance, WC.primary),

          const SizedBox(height: 20),

          // Invoice Section
          const Text('Invoices', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600)),
          const SizedBox(height: 12),
          _invoiceItem('INV-2026-001', '1 000 PLN', '15 Jan 2026', true),
          _invoiceItem('INV-2026-002', '500 PLN', '20 Jan 2026', true),
          _invoiceItem('INV-2026-003', '500 PLN', '15 Feb 2026', true),

          const SizedBox(height: 20),

          // Payment Methods
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Payment Methods', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 12),
                  _payMethod(Icons.account_balance, 'Bank Transfer', 'PKO BP — PL 12 3456 7890 1234 5678'),
                  const Divider(height: 20),
                  _payMethod(Icons.credit_card, 'Card Payment', 'Via secure payment link'),
                  const Divider(height: 20),
                  _payMethod(Icons.store, 'In-Office', 'Cash / Terminal at WinCase office'),
                ],
              ),
            ),
          ),

          const SizedBox(height: 24),
        ],
      ),
    );
  }

  static Widget _infoPill(String label, String value) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(color: Colors.white.withAlpha(40), borderRadius: BorderRadius.circular(20)),
      child: Text('$label: $value', style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.w500)),
    );
  }

  Widget _scheduleCard(String title, String amount, String date, String status) {
    Color color;
    IconData icon;
    String label;
    switch (status) {
      case 'paid':
        color = WC.primary;
        icon = Icons.check_circle;
        label = 'Paid';
        break;
      case 'upcoming':
        color = WC.warning;
        icon = Icons.schedule;
        label = 'Upcoming';
        break;
      default:
        color = Colors.grey;
        icon = Icons.circle_outlined;
        label = 'Pending';
    }

    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Row(
          children: [
            Container(
              width: 40, height: 40,
              decoration: BoxDecoration(color: color.withAlpha(20), borderRadius: BorderRadius.circular(10)),
              child: Icon(icon, color: color, size: 20),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(title, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                  const SizedBox(height: 2),
                  Text(date, style: TextStyle(fontSize: 11, color: Colors.grey[500])),
                ],
              ),
            ),
            Column(
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                Text(amount, style: TextStyle(fontWeight: FontWeight.w700, fontSize: 14, color: color)),
                const SizedBox(height: 2),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                  decoration: BoxDecoration(color: color.withAlpha(20), borderRadius: BorderRadius.circular(8)),
                  child: Text(label, style: TextStyle(color: color, fontSize: 10, fontWeight: FontWeight.w600)),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _transactionItem(String method, String amount, String date, IconData icon, Color color) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Row(
          children: [
            Container(
              width: 40, height: 40,
              decoration: BoxDecoration(color: color.withAlpha(20), borderRadius: BorderRadius.circular(10)),
              child: Icon(icon, color: color, size: 20),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(method, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                  Text(date, style: TextStyle(fontSize: 11, color: Colors.grey[500])),
                ],
              ),
            ),
            Text('- $amount', style: const TextStyle(fontWeight: FontWeight.w700, fontSize: 14, color: WC.primary)),
          ],
        ),
      ),
    );
  }

  Widget _invoiceItem(String number, String amount, String date, bool paid) {
    return Card(
      margin: const EdgeInsets.only(bottom: 8),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Row(
          children: [
            Container(
              width: 40, height: 40,
              decoration: BoxDecoration(color: WC.info.withAlpha(20), borderRadius: BorderRadius.circular(10)),
              child: const Icon(Icons.receipt_long, color: WC.info, size: 20),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(number, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
                  Text('$date • $amount', style: TextStyle(fontSize: 11, color: Colors.grey[500])),
                ],
              ),
            ),
            OutlinedButton.icon(
              onPressed: () {},
              icon: const Icon(Icons.download, size: 14),
              label: const Text('PDF', style: TextStyle(fontSize: 11)),
              style: OutlinedButton.styleFrom(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                minimumSize: Size.zero,
                tapTargetSize: MaterialTapTargetSize.shrinkWrap,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _payMethod(IconData icon, String title, String sub) {
    return Row(
      children: [
        Icon(icon, size: 22, color: WC.primary),
        const SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(title, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
              Text(sub, style: TextStyle(fontSize: 11, color: Colors.grey[500])),
            ],
          ),
        ),
      ],
    );
  }

  void _showPayDialog(BuildContext context) {
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
            const Text('Make a Payment', style: TextStyle(fontSize: 18, fontWeight: FontWeight.w700)),
            const SizedBox(height: 20),
            _payOption(context, Icons.account_balance, 'Bank Transfer', 'Transfer to our account', WC.primary),
            const SizedBox(height: 10),
            _payOption(context, Icons.credit_card, 'Card Payment', 'Pay with Visa / Mastercard', WC.info),
            const SizedBox(height: 10),
            _payOption(context, Icons.qr_code_2, 'BLIK', 'Pay with BLIK code', Colors.deepPurple),
            const SizedBox(height: 16),
            SizedBox(
              width: double.infinity,
              child: TextButton(
                onPressed: () => Navigator.pop(context),
                child: const Text('Cancel'),
              ),
            ),
            const SizedBox(height: 8),
          ],
        ),
      ),
    );
  }

  Widget _payOption(BuildContext ctx, IconData icon, String title, String sub, Color color) {
    return InkWell(
      onTap: () {
        Navigator.pop(ctx);
        ScaffoldMessenger.of(ctx).showSnackBar(
          SnackBar(content: Text('Redirecting to $title...'), backgroundColor: WC.primary),
        );
      },
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.all(14),
        decoration: BoxDecoration(
          border: Border.all(color: Colors.grey[200]!),
          borderRadius: BorderRadius.circular(12),
        ),
        child: Row(
          children: [
            Container(
              width: 44, height: 44,
              decoration: BoxDecoration(color: color.withAlpha(20), borderRadius: BorderRadius.circular(10)),
              child: Icon(icon, color: color, size: 22),
            ),
            const SizedBox(width: 14),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(title, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 14)),
                  Text(sub, style: TextStyle(fontSize: 12, color: Colors.grey[500])),
                ],
              ),
            ),
            const Icon(Icons.chevron_right, color: Colors.grey),
          ],
        ),
      ),
    );
  }
}
