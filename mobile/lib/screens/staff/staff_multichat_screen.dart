import 'package:flutter/material.dart';
import '../shared/multichat_chat_screen.dart';

class StaffMultichatScreen extends StatelessWidget {
  const StaffMultichatScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return const UnifiedMultichatScreen(isBoss: false);
  }
}
