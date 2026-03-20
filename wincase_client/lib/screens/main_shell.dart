import 'package:flutter/material.dart';
import '../theme/app_theme.dart';
import '../widgets/wc_logo.dart';
import 'dashboard_screen.dart';
import 'documents_screen.dart';
import 'messages_screen.dart';
import 'payments_screen.dart';
import 'profile_screen.dart';

class MainShell extends StatefulWidget {
  const MainShell({super.key});
  @override
  State<MainShell> createState() => _MainShellState();
}

class _MainShellState extends State<MainShell> {
  int _idx = 0;

  final _screens = const [
    DashboardScreen(),
    DocumentsScreen(),
    MessagesScreen(),
    PaymentsScreen(),
    ProfileScreen(),
  ];

  final _titles = ['Dashboard', 'Documents', 'Messages', 'Payments', 'Profile'];
  final _icons = [
    Icons.dashboard_outlined,
    Icons.folder_outlined,
    Icons.message_outlined,
    Icons.payment_outlined,
    Icons.person_outline,
  ];
  final _activeIcons = [
    Icons.dashboard,
    Icons.folder,
    Icons.message,
    Icons.payment,
    Icons.person,
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: _idx == 0
            ? const WcLogo(fontSize: 22)
            : Text(_titles[_idx]),
        actions: [
          if (_idx == 0)
            IconButton(
              onPressed: () {},
              icon: Stack(
                children: [
                  const Icon(Icons.notifications_outlined),
                  Positioned(
                    right: 0, top: 0,
                    child: Container(
                      width: 8, height: 8,
                      decoration: const BoxDecoration(color: WC.danger, shape: BoxShape.circle),
                    ),
                  ),
                ],
              ),
            ),
          if (_idx == 2)
            IconButton(
              onPressed: () {},
              icon: const Icon(Icons.search),
            ),
          if (_idx == 1)
            IconButton(
              onPressed: () {},
              icon: const Icon(Icons.filter_list),
            ),
        ],
      ),
      body: IndexedStack(
        index: _idx,
        children: _screens,
      ),
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [BoxShadow(color: Colors.black.withAlpha(10), blurRadius: 8, offset: const Offset(0, -2))],
        ),
        child: SafeArea(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 6),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: List.generate(5, (i) => _navItem(i)),
            ),
          ),
        ),
      ),
    );
  }

  Widget _navItem(int i) {
    final active = _idx == i;
    final color = active ? WC.primary : Colors.grey;
    return InkWell(
      onTap: () => setState(() => _idx = i),
      borderRadius: BorderRadius.circular(12),
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Stack(
              children: [
                Icon(active ? _activeIcons[i] : _icons[i], color: color, size: 24),
                // Badge for messages
                if (i == 2)
                  Positioned(
                    right: -2, top: -2,
                    child: Container(
                      padding: const EdgeInsets.all(3),
                      decoration: const BoxDecoration(color: WC.danger, shape: BoxShape.circle),
                      child: const Text('1', style: TextStyle(color: Colors.white, fontSize: 8, fontWeight: FontWeight.w700)),
                    ),
                  ),
              ],
            ),
            const SizedBox(height: 2),
            Text(
              _titles[i],
              style: TextStyle(fontSize: 10, color: color, fontWeight: active ? FontWeight.w600 : FontWeight.w400),
            ),
          ],
        ),
      ),
    );
  }
}
