import 'package:flutter/material.dart';
import '../theme/app_theme.dart';

class WcLogo extends StatelessWidget {
  final double fontSize;
  const WcLogo({super.key, this.fontSize = 32});

  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        ClipRRect(
          borderRadius: BorderRadius.circular(18),
          child: Image.asset('assets/images/logo_icon_light.png', width: 80, height: 80),
        ),
        const SizedBox(height: 12),
        RichText(
          text: TextSpan(
            style: TextStyle(fontSize: fontSize, fontWeight: FontWeight.w800, letterSpacing: 1),
            children: const [
              TextSpan(text: 'WIN', style: TextStyle(color: WC.dark)),
              TextSpan(text: 'CASE', style: TextStyle(color: WC.primary)),
            ],
          ),
        ),
      ],
    );
  }
}
