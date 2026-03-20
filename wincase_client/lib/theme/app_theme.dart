import 'package:flutter/material.dart';

class WC {
  static const Color primary = Color(0xFF1F3864);
  static const Color primaryDark = Color(0xFF152850);
  static const Color dark = Color(0xFF1A1D23);
  static const Color bg = Color(0xFFF5F6FA);
  static const Color cardBg = Colors.white;
  static const Color textPrimary = Color(0xFF1A1D23);
  static const Color textSecondary = Color(0xFF6C757D);
  static const Color danger = Color(0xFFDC3545);
  static const Color warning = Color(0xFFF0AD4E);
  static const Color info = Color(0xFF0DCAF0);
  static const Color success = Color(0xFF3B82F6);

  static const String apiBase = 'https://admin.wincase.eu/api/client';
  static const String apiBaseV1 = 'https://admin.wincase.eu/api/v1';

  static ThemeData get lightTheme => ThemeData(
    useMaterial3: true,
    colorSchemeSeed: primary,
    brightness: Brightness.light,
    scaffoldBackgroundColor: bg,
    fontFamily: 'Roboto',
    appBarTheme: const AppBarTheme(
      backgroundColor: Colors.white,
      foregroundColor: textPrimary,
      elevation: 0,
      centerTitle: false,
      titleTextStyle: TextStyle(
        color: textPrimary,
        fontSize: 20,
        fontWeight: FontWeight.w700,
      ),
    ),
    cardTheme: CardThemeData(
      elevation: 1,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      color: cardBg,
    ),
    elevatedButtonTheme: ElevatedButtonThemeData(
      style: ElevatedButton.styleFrom(
        backgroundColor: primary,
        foregroundColor: Colors.white,
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
        textStyle: const TextStyle(fontWeight: FontWeight.w600, fontSize: 15),
      ),
    ),
    outlinedButtonTheme: OutlinedButtonThemeData(
      style: OutlinedButton.styleFrom(
        foregroundColor: primary,
        side: const BorderSide(color: primary),
        padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
      ),
    ),
    inputDecorationTheme: InputDecorationTheme(
      filled: true,
      fillColor: Colors.white,
      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(8),
        borderSide: const BorderSide(color: Color(0xFFDEE2E6)),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(8),
        borderSide: const BorderSide(color: Color(0xFFDEE2E6)),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(8),
        borderSide: const BorderSide(color: primary, width: 2),
      ),
      errorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(8),
        borderSide: const BorderSide(color: danger),
      ),
      hintStyle: const TextStyle(color: textSecondary, fontSize: 14),
      labelStyle: const TextStyle(color: textSecondary, fontSize: 14),
    ),
  );
}
