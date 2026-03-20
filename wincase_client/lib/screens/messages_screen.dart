import 'package:flutter/material.dart';
import '../theme/app_theme.dart';

class MessagesScreen extends StatefulWidget {
  const MessagesScreen({super.key});
  @override
  State<MessagesScreen> createState() => _MessagesScreenState();
}

class _MessagesScreenState extends State<MessagesScreen> {
  final _msgCtrl = TextEditingController();
  final _scrollCtrl = ScrollController();

  final _messages = <Map<String, dynamic>>[
    {'from': 'manager', 'name': 'Anya Petrova', 'text': 'Hello Olena! Welcome to WinCase. I will be managing your case for the Temporary Residence Permit. Please upload all required documents as soon as possible.', 'time': '15 Jan, 10:30', 'read': true},
    {'from': 'client', 'name': 'Olena', 'text': 'Thank you! I have uploaded my passport and rental agreement. What else do I need?', 'time': '15 Jan, 11:15', 'read': true},
    {'from': 'manager', 'name': 'Anya Petrova', 'text': 'Great, I can see them. You still need to upload:\n• Bank statements (last 3 months)\n• Health insurance\n• Employment certificate\n\nPlease also book your fingerprint appointment.', 'time': '15 Jan, 14:22', 'read': true},
    {'from': 'system', 'name': 'System', 'text': '📋 Document "Health Insurance" was approved.', 'time': '18 Jan, 09:00', 'read': true},
    {'from': 'client', 'name': 'Olena', 'text': 'I uploaded the health insurance. The bank statement for January is also ready.', 'time': '20 Jan, 16:40', 'read': true},
    {'from': 'manager', 'name': 'Anya Petrova', 'text': 'Your fingerprint appointment is scheduled for 5 February at the Immigration Office. Please arrive 15 minutes early with your passport.', 'time': '28 Jan, 11:00', 'read': true},
    {'from': 'system', 'name': 'System', 'text': '🗓 Appointment reminder: Fingerprint appointment — 5 Feb, 10:00', 'time': '4 Feb, 18:00', 'read': true},
    {'from': 'client', 'name': 'Olena', 'text': 'I completed the fingerprints today. Everything went well!', 'time': '5 Feb, 12:30', 'read': true},
    {'from': 'manager', 'name': 'Anya Petrova', 'text': 'Excellent! Your fingerprints have been submitted. Now we are waiting for the decision. I will keep you updated. In the meantime, please upload:\n• Bank statement for February\n• Employment certificate (signed by employer)\n\nBoth are due before March 10.', 'time': '6 Feb, 10:15', 'read': true},
    {'from': 'system', 'name': 'System', 'text': '⚠️ Document "University Diploma" was rejected. Reason: sworn translation missing.', 'time': '20 Feb, 14:00', 'read': true},
    {'from': 'manager', 'name': 'Anya Petrova', 'text': 'Olena, your university diploma was rejected because we need a sworn translation into Polish. Please get it translated and re-upload. Also, don\'t forget about the bank statement and employment certificate.', 'time': '1 Mar, 09:30', 'read': false},
  ];

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        // Manager info bar
        Container(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
          decoration: BoxDecoration(
            color: Colors.white,
            boxShadow: [BoxShadow(color: Colors.black.withAlpha(8), blurRadius: 4, offset: const Offset(0, 2))],
          ),
          child: Row(
            children: [
              const CircleAvatar(radius: 18, backgroundColor: WC.primary, child: Text('AP', style: TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.w700))),
              const SizedBox(width: 10),
              const Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text('Anya Petrova', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 14)),
                    Text('Senior Immigration Consultant', style: TextStyle(color: Colors.grey, fontSize: 11)),
                  ],
                ),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(color: WC.primary.withAlpha(20), borderRadius: BorderRadius.circular(12)),
                child: const Row(
                  children: [
                    Icon(Icons.circle, size: 8, color: WC.primary),
                    SizedBox(width: 4),
                    Text('Online', style: TextStyle(color: WC.primary, fontSize: 11, fontWeight: FontWeight.w600)),
                  ],
                ),
              ),
            ],
          ),
        ),

        // Messages
        Expanded(
          child: ListView.builder(
            controller: _scrollCtrl,
            padding: const EdgeInsets.all(16),
            itemCount: _messages.length,
            itemBuilder: (_, i) => _bubble(_messages[i]),
          ),
        ),

        // Input
        Container(
          padding: const EdgeInsets.fromLTRB(12, 8, 12, 12),
          decoration: BoxDecoration(
            color: Colors.white,
            boxShadow: [BoxShadow(color: Colors.black.withAlpha(8), blurRadius: 4, offset: const Offset(0, -2))],
          ),
          child: SafeArea(
            top: false,
            child: Row(
              children: [
                IconButton(
                  onPressed: () {},
                  icon: const Icon(Icons.attach_file, color: Colors.grey),
                  constraints: const BoxConstraints(minWidth: 36, minHeight: 36),
                  padding: EdgeInsets.zero,
                ),
                Expanded(
                  child: TextField(
                    controller: _msgCtrl,
                    maxLines: 3,
                    minLines: 1,
                    decoration: InputDecoration(
                      hintText: 'Type a message...',
                      filled: true,
                      fillColor: WC.bg,
                      contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(20), borderSide: BorderSide.none),
                      enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(20), borderSide: BorderSide.none),
                      focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(20), borderSide: BorderSide.none),
                    ),
                    style: const TextStyle(fontSize: 14),
                  ),
                ),
                const SizedBox(width: 4),
                Container(
                  decoration: const BoxDecoration(color: WC.primary, shape: BoxShape.circle),
                  child: IconButton(
                    onPressed: _send,
                    icon: const Icon(Icons.send, color: Colors.white, size: 18),
                    constraints: const BoxConstraints(minWidth: 40, minHeight: 40),
                    padding: EdgeInsets.zero,
                  ),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _bubble(Map<String, dynamic> msg) {
    final isClient = msg['from'] == 'client';
    final isSystem = msg['from'] == 'system';

    if (isSystem) {
      return Container(
        margin: const EdgeInsets.only(bottom: 12),
        padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 8),
        decoration: BoxDecoration(
          color: WC.info.withAlpha(15),
          borderRadius: BorderRadius.circular(12),
          border: Border.all(color: WC.info.withAlpha(30)),
        ),
        child: Row(
          children: [
            const Icon(Icons.info_outline, size: 16, color: WC.info),
            const SizedBox(width: 8),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(msg['text'], style: const TextStyle(fontSize: 12, color: Colors.black87)),
                  const SizedBox(height: 2),
                  Text(msg['time'], style: TextStyle(fontSize: 10, color: Colors.grey[500])),
                ],
              ),
            ),
          ],
        ),
      );
    }

    return Align(
      alignment: isClient ? Alignment.centerRight : Alignment.centerLeft,
      child: Container(
        margin: const EdgeInsets.only(bottom: 12),
        constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.78),
        child: Column(
          crossAxisAlignment: isClient ? CrossAxisAlignment.end : CrossAxisAlignment.start,
          children: [
            if (!isClient)
              Padding(
                padding: const EdgeInsets.only(left: 4, bottom: 4),
                child: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    CircleAvatar(radius: 10, backgroundColor: WC.primary, child: Text(msg['name'][0], style: const TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.w700))),
                    const SizedBox(width: 6),
                    Text(msg['name'], style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 11)),
                  ],
                ),
              ),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
              decoration: BoxDecoration(
                color: isClient ? WC.primary : Colors.white,
                borderRadius: BorderRadius.only(
                  topLeft: const Radius.circular(16),
                  topRight: const Radius.circular(16),
                  bottomLeft: Radius.circular(isClient ? 16 : 4),
                  bottomRight: Radius.circular(isClient ? 4 : 16),
                ),
                boxShadow: [BoxShadow(color: Colors.black.withAlpha(6), blurRadius: 4, offset: const Offset(0, 1))],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(msg['text'], style: TextStyle(fontSize: 13, color: isClient ? Colors.white : Colors.black87, height: 1.4)),
                  const SizedBox(height: 4),
                  Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(msg['time'], style: TextStyle(fontSize: 10, color: isClient ? Colors.white.withAlpha(180) : Colors.grey[500])),
                      if (isClient) ...[
                        const SizedBox(width: 4),
                        Icon(msg['read'] == true ? Icons.done_all : Icons.done, size: 14, color: Colors.white.withAlpha(180)),
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

  void _send() {
    final text = _msgCtrl.text.trim();
    if (text.isEmpty) return;
    setState(() {
      _messages.add({
        'from': 'client',
        'name': 'Olena',
        'text': text,
        'time': 'Just now',
        'read': false,
      });
      _msgCtrl.clear();
    });
    Future.delayed(const Duration(milliseconds: 100), () {
      _scrollCtrl.animateTo(_scrollCtrl.position.maxScrollExtent + 100, duration: const Duration(milliseconds: 300), curve: Curves.easeOut);
    });
  }
}
