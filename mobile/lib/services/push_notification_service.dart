// =====================================================
// FILE: lib/services/push_notification_service.dart
// Push Notifications — Firebase Cloud Messaging (FCM)
// Firebase Project: wincase-7206c
// App ID: 1:501751425854:android:42348ac03cc75e95f25a6f
// =====================================================

import 'dart:io';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'api_client.dart';

/// Background message handler — must be top-level function
@pragma('vm:entry-point')
Future<void> _firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  await Firebase.initializeApp();
}

class PushNotificationService {
  static final FirebaseMessaging _messaging = FirebaseMessaging.instance;
  static final FlutterLocalNotificationsPlugin _localNotifications =
      FlutterLocalNotificationsPlugin();

  static const AndroidNotificationChannel _channel = AndroidNotificationChannel(
    'wincase_high',
    'WinCase Notifications',
    description: 'Important CRM notifications',
    importance: Importance.high,
  );

  /// Initialize Firebase + FCM + local notifications
  static Future<void> init() async {
    await Firebase.initializeApp();

    // Background handler
    FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);

    // Request permissions (iOS + Android 13+)
    final settings = await _messaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
      provisional: false,
    );

    if (settings.authorizationStatus == AuthorizationStatus.denied) {
      return;
    }

    // Create Android notification channel
    await _localNotifications
        .resolvePlatformSpecificImplementation<
            AndroidFlutterLocalNotificationsPlugin>()
        ?.createNotificationChannel(_channel);

    // Init local notifications
    await _localNotifications.initialize(
      const InitializationSettings(
        android: AndroidInitializationSettings('@mipmap/ic_launcher'),
        iOS: DarwinInitializationSettings(),
      ),
      onDidReceiveNotificationResponse: _onNotificationTapped,
    );

    // Foreground messages → show local notification
    FirebaseMessaging.onMessage.listen(_showForegroundNotification);

    // When app is opened from notification
    FirebaseMessaging.onMessageOpenedApp.listen(_onNotificationOpened);

    // Subscribe to role-based topics
    await subscribeToTopic('all');

    // Get FCM token and register on server
    final token = await _messaging.getToken();
    if (token != null) {
      await _registerTokenOnServer(token);
    }

    // Listen for token refresh
    _messaging.onTokenRefresh.listen((newToken) {
      _registerTokenOnServer(newToken);
    });
  }

  /// Register FCM token on server
  static Future<void> _registerTokenOnServer(String token) async {
    try {
      final api = ApiClient();
      if (await api.isAuthenticated()) {
        await api.dio.post('/notifications/register-device', data: {
          'fcm_token': token,
          'platform': Platform.operatingSystem,
        });
      }
    } catch (_) {
      // Silently fail — will retry on next app start
    }
  }

  /// Get current FCM token
  static Future<String?> getToken() async {
    return await _messaging.getToken();
  }

  /// Subscribe to a topic (e.g., 'all', 'boss', 'staff', 'case_updates')
  static Future<void> subscribeToTopic(String topic) async {
    await _messaging.subscribeToTopic(topic);
  }

  /// Unsubscribe from a topic
  static Future<void> unsubscribeFromTopic(String topic) async {
    await _messaging.unsubscribeFromTopic(topic);
  }

  /// Show foreground notification as local notification
  static void _showForegroundNotification(RemoteMessage message) {
    final notification = message.notification;
    if (notification == null) return;

    _localNotifications.show(
      notification.hashCode,
      notification.title ?? 'WinCase',
      notification.body ?? '',
      NotificationDetails(
        android: AndroidNotificationDetails(
          _channel.id,
          _channel.name,
          channelDescription: _channel.description,
          icon: '@mipmap/ic_launcher',
          importance: Importance.high,
          priority: Priority.high,
        ),
        iOS: const DarwinNotificationDetails(
          presentAlert: true,
          presentBadge: true,
          presentSound: true,
        ),
      ),
      payload: message.data['route'],
    );
  }

  /// Handle notification tap (from local notification)
  static void _onNotificationTapped(NotificationResponse response) {
    final route = response.payload;
    if (route != null && route.isNotEmpty) {
      // TODO: navigate to route via GoRouter
    }
  }

  /// Handle notification tap (from FCM background)
  static void _onNotificationOpened(RemoteMessage message) {
    final route = message.data['route'];
    if (route != null && route.isNotEmpty) {
      // TODO: navigate to route via GoRouter
    }
  }
}
