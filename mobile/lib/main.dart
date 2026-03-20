// =====================================================
// FILE: lib/main.dart
// WINCASE CRM Mobile App — Entry Point
// Herozi Bootstrap 5 matched theme
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'core/app_router.dart';
import 'services/push_notification_service.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await PushNotificationService.init();

  SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
    statusBarColor: Colors.transparent,
    statusBarIconBrightness: Brightness.light,
  ));

  runApp(const ProviderScope(child: WincaseApp()));
}

// =====================================================
// WINCASE BRAND COLORS — wincase.eu match
// =====================================================
class WC {
  WC._();

  // Primary brand (wincase.eu: #015EA7)
  static const Color navy = Color(0xFF015EA7);
  static const Color navyLight = Color(0xFF1A7BC4);
  static const Color navyDark = Color(0xFF014D8A);

  // Bootstrap-like palette
  static const Color primary = Color(0xFF015EA7);
  static const Color success = Color(0xFF3B82F6);
  static const Color danger = Color(0xFFDC3545);
  static const Color warning = Color(0xFFFFC107);
  static const Color info = Color(0xFF17A2B8);

  // Neutral
  static const Color textPrimary = Color(0xFF2D3748);
  static const Color textSecondary = Color(0xFF6C757D);
  static const Color textMuted = Color(0xFF8898AA);
  static const Color border = Color(0xFFE2E8F0);
  static const Color background = Color(0xFFF5F7FA);
  static const Color surface = Color(0xFFFFFFFF);
  static const Color cardShadow = Color(0x0D000000);

  // Sidebar / Dark
  static const Color sidebar = Color(0xFF1A2332);
  static const Color sidebarActive = Color(0xFF015EA7);
}

class WincaseApp extends ConsumerWidget {
  const WincaseApp({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final router = ref.watch(routerProvider);

    return MaterialApp.router(
      title: 'WINCASE Staff',
      debugShowCheckedModeBanner: false,
      theme: _buildLightTheme(),
      darkTheme: _buildDarkTheme(),
      themeMode: ThemeMode.light,
      routerConfig: router,
    );
  }

  ThemeData _buildLightTheme() {
    final colorScheme = ColorScheme.fromSeed(
      seedColor: WC.navy,
      brightness: Brightness.light,
      primary: WC.navy,
      onPrimary: Colors.white,
      secondary: WC.info,
      surface: WC.surface,
      error: WC.danger,
    );

    return ThemeData(
      useMaterial3: true,
      colorScheme: colorScheme,
      scaffoldBackgroundColor: WC.background,

      // AppBar — Herozi dark header style
      appBarTheme: const AppBarTheme(
        centerTitle: false,
        elevation: 0,
        scrolledUnderElevation: 1,
        backgroundColor: WC.navy,
        foregroundColor: Colors.white,
        titleTextStyle: TextStyle(
          fontFamily: 'Inter',
          fontSize: 18,
          fontWeight: FontWeight.w600,
          color: Colors.white,
        ),
        iconTheme: IconThemeData(color: Colors.white),
      ),

      // Cards — Bootstrap card style
      cardTheme: CardThemeData(
        elevation: 0,
        color: WC.surface,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(8),
          side: const BorderSide(color: WC.border, width: 1),
        ),
        margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
      ),

      // Inputs — Bootstrap form-control
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: WC.surface,
        contentPadding: const EdgeInsets.symmetric(horizontal: 14, vertical: 12),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(6),
          borderSide: const BorderSide(color: WC.border),
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(6),
          borderSide: const BorderSide(color: WC.border),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(6),
          borderSide: const BorderSide(color: WC.navy, width: 2),
        ),
        hintStyle: const TextStyle(color: WC.textMuted, fontSize: 14),
        labelStyle: const TextStyle(color: WC.textSecondary, fontSize: 14),
      ),

      // Buttons — Bootstrap btn-primary
      filledButtonTheme: FilledButtonThemeData(
        style: FilledButton.styleFrom(
          backgroundColor: WC.navy,
          foregroundColor: Colors.white,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6)),
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
          textStyle: const TextStyle(fontFamily: 'Inter', fontWeight: FontWeight.w500, fontSize: 14),
        ),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: WC.navy,
          foregroundColor: Colors.white,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6)),
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
          elevation: 0,
        ),
      ),
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: OutlinedButton.styleFrom(
          foregroundColor: WC.navy,
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(6)),
          side: const BorderSide(color: WC.navy),
          padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
        ),
      ),
      textButtonTheme: TextButtonThemeData(
        style: TextButton.styleFrom(
          foregroundColor: WC.navy,
          textStyle: const TextStyle(fontWeight: FontWeight.w500),
        ),
      ),

      // FAB
      floatingActionButtonTheme: const FloatingActionButtonThemeData(
        backgroundColor: WC.navy,
        foregroundColor: Colors.white,
        elevation: 2,
      ),

      // Chips — Bootstrap badge style
      chipTheme: ChipThemeData(
        backgroundColor: WC.background,
        selectedColor: WC.navy.withOpacity(0.12),
        labelStyle: const TextStyle(fontSize: 12, fontWeight: FontWeight.w500),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        side: const BorderSide(color: WC.border),
        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
      ),

      // Bottom Navigation — Herozi sidebar feel
      navigationBarTheme: NavigationBarThemeData(
        backgroundColor: WC.surface,
        elevation: 4,
        indicatorColor: WC.navy.withOpacity(0.12),
        labelTextStyle: WidgetStateProperty.resolveWith((states) {
          if (states.contains(WidgetState.selected)) {
            return const TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: WC.navy);
          }
          return const TextStyle(fontSize: 11, fontWeight: FontWeight.w400, color: WC.textMuted);
        }),
        iconTheme: WidgetStateProperty.resolveWith((states) {
          if (states.contains(WidgetState.selected)) {
            return const IconThemeData(color: WC.navy, size: 24);
          }
          return const IconThemeData(color: WC.textMuted, size: 24);
        }),
      ),

      // Divider
      dividerTheme: const DividerThemeData(color: WC.border, thickness: 1, space: 1),

      // Typography — Inter (Google Fonts fallback)
      textTheme: const TextTheme(
        headlineLarge: TextStyle(fontSize: 28, fontWeight: FontWeight.w700, color: WC.textPrimary),
        headlineMedium: TextStyle(fontSize: 22, fontWeight: FontWeight.w600, color: WC.textPrimary),
        headlineSmall: TextStyle(fontSize: 18, fontWeight: FontWeight.w600, color: WC.textPrimary),
        titleLarge: TextStyle(fontSize: 16, fontWeight: FontWeight.w600, color: WC.textPrimary),
        titleMedium: TextStyle(fontSize: 14, fontWeight: FontWeight.w600, color: WC.textPrimary),
        titleSmall: TextStyle(fontSize: 13, fontWeight: FontWeight.w500, color: WC.textSecondary),
        bodyLarge: TextStyle(fontSize: 15, fontWeight: FontWeight.w400, color: WC.textPrimary),
        bodyMedium: TextStyle(fontSize: 14, fontWeight: FontWeight.w400, color: WC.textPrimary),
        bodySmall: TextStyle(fontSize: 12, fontWeight: FontWeight.w400, color: WC.textSecondary),
        labelLarge: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: WC.textPrimary),
        labelMedium: TextStyle(fontSize: 12, fontWeight: FontWeight.w500, color: WC.textSecondary),
        labelSmall: TextStyle(fontSize: 11, fontWeight: FontWeight.w400, color: WC.textMuted),
      ),

      // ListTile
      listTileTheme: const ListTileThemeData(
        contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 4),
        dense: true,
        titleTextStyle: TextStyle(fontSize: 14, fontWeight: FontWeight.w500, color: WC.textPrimary),
        subtitleTextStyle: TextStyle(fontSize: 12, color: WC.textSecondary),
      ),

      // BottomSheet
      bottomSheetTheme: const BottomSheetThemeData(
        backgroundColor: WC.surface,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.vertical(top: Radius.circular(16)),
        ),
      ),

      // Dialog
      dialogTheme: DialogThemeData(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        titleTextStyle: const TextStyle(fontSize: 18, fontWeight: FontWeight.w600, color: WC.textPrimary),
      ),

      // SnackBar
      snackBarTheme: SnackBarThemeData(
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
        backgroundColor: WC.sidebar,
      ),

      // TabBar
      tabBarTheme: const TabBarThemeData(
        labelColor: WC.navy,
        unselectedLabelColor: WC.textMuted,
        indicatorColor: WC.navy,
        labelStyle: TextStyle(fontWeight: FontWeight.w600, fontSize: 13),
        unselectedLabelStyle: TextStyle(fontWeight: FontWeight.w400, fontSize: 13),
      ),

      // Badge
      badgeTheme: const BadgeThemeData(
        backgroundColor: WC.danger,
        textColor: Colors.white,
        smallSize: 8,
        largeSize: 16,
      ),
    );
  }

  ThemeData _buildDarkTheme() {
    final colorScheme = ColorScheme.fromSeed(
      seedColor: WC.navy,
      brightness: Brightness.dark,
      primary: WC.navyLight,
      secondary: WC.info,
      error: WC.danger,
    );

    return ThemeData(
      useMaterial3: true,
      colorScheme: colorScheme,
      scaffoldBackgroundColor: const Color(0xFF0F1923),

      appBarTheme: const AppBarTheme(
        centerTitle: false,
        elevation: 0,
        backgroundColor: Color(0xFF1A2332),
        foregroundColor: Colors.white,
        titleTextStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.w600, color: Colors.white),
      ),

      cardTheme: CardThemeData(
        elevation: 0,
        color: const Color(0xFF1A2332),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(8),
          side: const BorderSide(color: Color(0xFF2D3748)),
        ),
      ),

      navigationBarTheme: NavigationBarThemeData(
        backgroundColor: const Color(0xFF1A2332),
        indicatorColor: WC.navyLight.withOpacity(0.2),
      ),

      dividerTheme: const DividerThemeData(color: Color(0xFF2D3748)),
    );
  }
}
