import 'package:flutter/material.dart';
import '../../main.dart';
import '../shared/multichat_chat_screen.dart';

// Channel definitions shared across boss & worker
class ChannelDef {
  final String key;
  final String label;
  final IconData icon;
  final Color color;
  const ChannelDef(this.key, this.label, this.icon, this.color);
}

const channels = [
  ChannelDef('all', 'All', Icons.all_inclusive, WC.navy),
  ChannelDef('whatsapp', 'WA', Icons.chat, Color(0xFF25D366)),
  ChannelDef('telegram', 'TG', Icons.send, Color(0xFF0088CC)),
  ChannelDef('portal', 'Portal', Icons.language, WC.success),
  ChannelDef('email', 'Email', Icons.email, Color(0xFF6C757D)),
  ChannelDef('sms', 'SMS', Icons.sms, Color(0xFF0D6EFD)),
  ChannelDef('facebook', 'FB', Icons.facebook, Color(0xFF1877F2)),
  ChannelDef('instagram', 'IG', Icons.camera_alt, Color(0xFFE4405F)),
  ChannelDef('viber', 'Viber', Icons.phone_in_talk, Color(0xFF7360F2)),
  ChannelDef('tiktok', 'TT', Icons.music_note, Color(0xFF010101)),
];

class BossMultichatScreen extends StatelessWidget {
  const BossMultichatScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return const UnifiedMultichatScreen(isBoss: true);
  }
}
