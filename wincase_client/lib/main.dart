import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'theme/app_theme.dart';
import 'screens/login_screen.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();
  SystemChrome.setPreferredOrientations([
    DeviceOrientation.portraitUp,
    DeviceOrientation.portraitDown,
  ]);
  SystemChrome.setSystemUIOverlayStyle(const SystemUiOverlayStyle(
    statusBarColor: Colors.transparent,
    statusBarIconBrightness: Brightness.dark,
  ));
  runApp(const WinCaseApp());
}

class WinCaseApp extends StatelessWidget {
  const WinCaseApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'WinCase Client',
      debugShowCheckedModeBanner: false,
      theme: WC.lightTheme,
      home: const LoginScreen(),
    );
  }
}
