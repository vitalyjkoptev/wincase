// =====================================================
// FILE: lib/core/app_router.dart
// GoRouter — Role-based navigation: Boss / Worker
// FIXED: GoRouter created ONCE, uses refreshListenable
// =====================================================

import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../screens/login_screen.dart';

// Boss screens
import '../screens/boss/boss_dashboard_screen.dart';
import '../screens/boss/boss_multichat_screen.dart';
import '../screens/boss/boss_workers_screen.dart';
import '../screens/boss/boss_finances_screen.dart';
import '../screens/boss/boss_profile_screen.dart';

// Worker screens
import '../screens/staff/staff_dashboard_screen.dart';
import '../screens/staff/staff_multichat_screen.dart';
import '../screens/staff/staff_clients_screen.dart';
import '../screens/staff/staff_cases_screen.dart';
import '../screens/staff/staff_tasks_screen.dart';
import '../screens/staff/staff_boss_chat_screen.dart';
import '../screens/staff/staff_client_chat_screen.dart';
import '../screens/staff/staff_calendar_screen.dart';
import '../screens/staff/staff_documents_screen.dart';
import '../screens/staff/staff_profile_screen.dart';

// Shared screens
import '../screens/leads_list_screen.dart';
import '../screens/pos_screen.dart';
import '../screens/notifications_screen.dart';

import '../providers/auth_provider.dart';

// =====================================================
// ROUTER REFRESH NOTIFIER
// Bridges Riverpod auth state → GoRouter's refreshListenable
// GoRouter is created ONCE, redirect re-evaluated on notify
// =====================================================

class _RouterRefreshNotifier extends ChangeNotifier {
  void notify() => notifyListeners();
}

final _routerRefreshProvider = Provider<_RouterRefreshNotifier>((ref) {
  final notifier = _RouterRefreshNotifier();
  ref.listen(authProvider, (_, __) => notifier.notify());
  return notifier;
});

// =====================================================
// GO ROUTER — created once, never rebuilt
// =====================================================

final routerProvider = Provider<GoRouter>((ref) {
  final notifier = ref.read(_routerRefreshProvider);

  return GoRouter(
    initialLocation: '/login',
    refreshListenable: notifier,
    redirect: (context, state) {
      final auth = ref.read(authProvider);
      final isLoggedIn = auth.isAuthenticated;
      final isLoginRoute = state.matchedLocation == '/login';

      if (!isLoggedIn && !isLoginRoute) return '/login';
      if (isLoggedIn && isLoginRoute) return '/dashboard';
      return null;
    },
    routes: [
      GoRoute(path: '/login', builder: (_, __) => const LoginScreen()),

      // Single ShellRoute — adaptive shell picks Boss/Worker nav
      ShellRoute(
        builder: (_, __, child) => _AdaptiveShell(child: child),
        routes: [
          // Shared routes — role-adaptive screens
          GoRoute(path: '/dashboard', builder: (_, __) => const _RoleScreen(boss: BossDashboardScreen(), worker: StaffDashboardScreen())),
          GoRoute(path: '/multichat', builder: (_, __) => const _RoleScreen(boss: BossMultichatScreen(), worker: StaffMultichatScreen())),
          GoRoute(path: '/profile', builder: (_, __) => const _RoleScreen(boss: BossProfileScreen(), worker: StaffProfileScreen())),

          // Boss-only routes
          GoRoute(path: '/workers', builder: (_, __) => const BossWorkersScreen()),
          GoRoute(path: '/finances', builder: (_, __) => const BossFinancesScreen()),

          // Worker-only routes
          GoRoute(
            path: '/clients',
            builder: (_, __) => const StaffClientsScreen(),
            routes: [
              GoRoute(
                path: ':clientId/chat',
                builder: (_, state) => StaffClientChatScreen(clientId: int.parse(state.pathParameters['clientId']!)),
              ),
            ],
          ),
          GoRoute(path: '/cases', builder: (_, __) => const StaffCasesScreen()),
          GoRoute(path: '/chat', builder: (_, __) => const StaffBossChatScreen()),
          GoRoute(path: '/calendar', builder: (_, __) => const StaffCalendarScreen()),
          GoRoute(path: '/tasks', builder: (_, __) => const StaffTasksScreen()),
          GoRoute(path: '/documents', builder: (_, __) => const StaffDocumentsScreen()),

          // Shared routes (accessible from both roles)
          GoRoute(path: '/leads', builder: (_, __) => const LeadsListScreen()),
          GoRoute(path: '/pos', builder: (_, __) => const PosScreen()),
          GoRoute(path: '/notifications', builder: (_, __) => const NotificationsScreen()),
        ],
      ),
    ],
  );
});

// =====================================================
// ADAPTIVE SHELL — picks BossShell or WorkerShell
// =====================================================

class _AdaptiveShell extends ConsumerWidget {
  final Widget child;
  const _AdaptiveShell({required this.child});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final auth = ref.watch(authProvider);
    if (auth.isBoss) return BossShell(child: child);
    return WorkerShell(child: child);
  }
}

// =====================================================
// ROLE SCREEN — picks boss or worker screen variant
// =====================================================

class _RoleScreen extends ConsumerWidget {
  final Widget boss;
  final Widget worker;
  const _RoleScreen({required this.boss, required this.worker});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return ref.watch(authProvider).isBoss ? boss : worker;
  }
}

// =====================================================
// Dark bottom navigation bar theme (shared by both shells)
// =====================================================

NavigationBarThemeData _darkNavBarTheme() {
  return NavigationBarThemeData(
    backgroundColor: const Color(0xFF1A2332),
    indicatorColor: Colors.white.withOpacity(0.15),
    labelTextStyle: WidgetStateProperty.resolveWith((states) {
      if (states.contains(WidgetState.selected)) {
        return const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: Colors.white);
      }
      return TextStyle(fontSize: 11, fontWeight: FontWeight.w400, color: Colors.white.withOpacity(0.6));
    }),
    iconTheme: WidgetStateProperty.resolveWith((states) {
      if (states.contains(WidgetState.selected)) {
        return const IconThemeData(color: Colors.white, size: 24);
      }
      return IconThemeData(color: Colors.white.withOpacity(0.6), size: 24);
    }),
  );
}

// =====================================================
// BOSS SHELL — Bottom Navigation (5 tabs)
// Dashboard | Multichat | Workers | Finances | Profile
// =====================================================

class BossShell extends StatelessWidget {
  final Widget child;
  const BossShell({super.key, required this.child});

  int _currentIndex(BuildContext context) {
    final location = GoRouterState.of(context).matchedLocation;
    if (location.startsWith('/dashboard')) return 0;
    if (location.startsWith('/multichat')) return 1;
    if (location.startsWith('/workers')) return 2;
    if (location.startsWith('/finances')) return 3;
    if (location.startsWith('/profile')) return 4;
    return 0;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: child,
      bottomNavigationBar: Theme(
        data: Theme.of(context).copyWith(navigationBarTheme: _darkNavBarTheme()),
        child: NavigationBar(
          selectedIndex: _currentIndex(context),
          height: 64,
          onDestinationSelected: (index) {
            switch (index) {
              case 0: context.go('/dashboard');
              case 1: context.go('/multichat');
              case 2: context.go('/workers');
              case 3: context.go('/finances');
              case 4: context.go('/profile');
            }
          },
          destinations: const [
            NavigationDestination(icon: Icon(Icons.dashboard_outlined), selectedIcon: Icon(Icons.dashboard), label: 'Overview'),
            NavigationDestination(icon: Badge(label: Text(''), child: Icon(Icons.forum_outlined)), selectedIcon: Icon(Icons.forum), label: 'Multichat'),
            NavigationDestination(icon: Icon(Icons.groups_outlined), selectedIcon: Icon(Icons.groups), label: 'Workers'),
            NavigationDestination(icon: Icon(Icons.account_balance_wallet_outlined), selectedIcon: Icon(Icons.account_balance_wallet), label: 'Finances'),
            NavigationDestination(icon: Icon(Icons.person_outline), selectedIcon: Icon(Icons.person), label: 'Profile'),
          ],
        ),
      ),
    );
  }
}

// =====================================================
// WORKER SHELL — Bottom Navigation (5 tabs)
// Dashboard | Multichat | Boss Chat | Calendar | Profile
// =====================================================

class WorkerShell extends StatelessWidget {
  final Widget child;
  const WorkerShell({super.key, required this.child});

  int _currentIndex(BuildContext context) {
    final location = GoRouterState.of(context).matchedLocation;
    if (location.startsWith('/dashboard')) return 0;
    if (location.startsWith('/multichat')) return 1;
    if (location.startsWith('/chat')) return 2;
    if (location.startsWith('/calendar')) return 3;
    if (location.startsWith('/profile')) return 4;
    return 0;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: child,
      bottomNavigationBar: Theme(
        data: Theme.of(context).copyWith(navigationBarTheme: _darkNavBarTheme()),
        child: NavigationBar(
          selectedIndex: _currentIndex(context),
          height: 64,
          onDestinationSelected: (index) {
            switch (index) {
              case 0: context.go('/dashboard');
              case 1: context.go('/multichat');
              case 2: context.go('/chat');
              case 3: context.go('/calendar');
              case 4: context.go('/profile');
            }
          },
          destinations: const [
            NavigationDestination(icon: Icon(Icons.dashboard_outlined), selectedIcon: Icon(Icons.dashboard), label: 'Home'),
            NavigationDestination(icon: Badge(label: Text(''), child: Icon(Icons.forum_outlined)), selectedIcon: Icon(Icons.forum), label: 'Multichat'),
            NavigationDestination(icon: Badge(label: Text(''), child: Icon(Icons.chat_bubble_outline)), selectedIcon: Icon(Icons.chat_bubble), label: 'Boss Chat'),
            NavigationDestination(icon: Icon(Icons.calendar_month_outlined), selectedIcon: Icon(Icons.calendar_month), label: 'Calendar'),
            NavigationDestination(icon: Icon(Icons.person_outline), selectedIcon: Icon(Icons.person), label: 'Profile'),
          ],
        ),
      ),
    );
  }
}
