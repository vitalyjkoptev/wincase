// =====================================================
// WINCASE CRM -- Staff Client Chat Screen
// Multi-channel messaging (WhatsApp, Telegram, Email, SMS, App)
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../providers/staff_provider.dart';
import '../../models/staff_models.dart';
import '../../providers/auth_provider.dart';

class StaffClientChatScreen extends ConsumerStatefulWidget {
  final int clientId;
  final String? clientName;

  const StaffClientChatScreen({
    super.key,
    required this.clientId,
    this.clientName,
  });

  @override
  ConsumerState<StaffClientChatScreen> createState() => _StaffClientChatScreenState();
}

class _StaffClientChatScreenState extends ConsumerState<StaffClientChatScreen> {
  final _messageController = TextEditingController();
  final _scrollController = ScrollController();
  String _selectedChannel = 'app';

  static const _channels = ['app', 'whatsapp', 'telegram', 'email', 'sms'];

  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(clientMessagesProvider.notifier).load(widget.clientId));
  }

  @override
  void dispose() {
    _messageController.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  void _sendMessage() {
    final body = _messageController.text.trim();
    if (body.isEmpty) return;

    ref.read(clientMessagesProvider.notifier).send(
      widget.clientId,
      body,
      channel: _selectedChannel,
    );

    _messageController.clear();
  }

  void _logCall() {
    showDialog(
      context: context,
      builder: (ctx) {
        final notesController = TextEditingController();
        return AlertDialog(
          title: const Text('Log Phone Call'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              TextField(
                controller: notesController,
                decoration: const InputDecoration(
                  hintText: 'Call notes (optional)',
                  border: OutlineInputBorder(),
                ),
                maxLines: 3,
              ),
            ],
          ),
          actions: [
            TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancel')),
            FilledButton(
              onPressed: () {
                ref.read(clientMessagesProvider.notifier).logCall(
                  widget.clientId,
                  notes: notesController.text.isEmpty ? null : notesController.text,
                );
                Navigator.pop(ctx);
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Call logged successfully')),
                );
              },
              child: const Text('Log Call'),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(clientMessagesProvider);
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    return Scaffold(
      appBar: AppBar(
        title: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(widget.clientName ?? 'Client #${widget.clientId}'),
            Text(
              'Client messages',
              style: theme.textTheme.bodySmall?.copyWith(color: colorScheme.onSurfaceVariant),
            ),
          ],
        ),
        actions: [
          // Log Call
          IconButton(
            icon: const Icon(Icons.phone_callback),
            onPressed: _logCall,
            tooltip: 'Log Call',
          ),
        ],
      ),
      body: Column(
        children: [
          // --- Messages ---
          Expanded(
            child: state.isLoading
                ? const Center(child: CircularProgressIndicator())
                : state.messages.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(Icons.message_outlined, size: 64, color: colorScheme.outline),
                            const SizedBox(height: 16),
                            Text('No messages', style: theme.textTheme.titleMedium),
                            const SizedBox(height: 4),
                            Text(
                              'Send a message to the client',
                              style: theme.textTheme.bodySmall?.copyWith(color: colorScheme.outline),
                            ),
                          ],
                        ),
                      )
                    : ListView.builder(
                        controller: _scrollController,
                        reverse: true,
                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                        itemCount: state.messages.length,
                        itemBuilder: (context, index) {
                          final msg = state.messages[index];
                          return _ClientChatBubble(message: msg);
                        },
                      ),
          ),

          // --- Channel selector + input ---
          Container(
            decoration: BoxDecoration(
              color: colorScheme.surface,
              border: Border(top: BorderSide(color: colorScheme.outlineVariant, width: 0.5)),
            ),
            child: Column(
              children: [
                // Channel dropdown
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
                  child: Row(
                    children: [
                      Text('Channel:', style: theme.textTheme.labelSmall?.copyWith(color: colorScheme.outline)),
                      const SizedBox(width: 8),
                      DropdownButton<String>(
                        value: _selectedChannel,
                        isDense: true,
                        underline: const SizedBox.shrink(),
                        style: theme.textTheme.bodySmall?.copyWith(
                          fontWeight: FontWeight.w600,
                          color: colorScheme.primary,
                        ),
                        items: _channels.map((ch) => DropdownMenuItem(
                          value: ch,
                          child: Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              Icon(_channelIcon(ch), size: 16, color: _channelColor(ch)),
                              const SizedBox(width: 4),
                              Text(_channelLabel(ch)),
                            ],
                          ),
                        )).toList(),
                        onChanged: (val) {
                          if (val != null) setState(() => _selectedChannel = val);
                        },
                      ),
                    ],
                  ),
                ),

                // Message input
                Padding(
                  padding: EdgeInsets.only(
                    left: 8,
                    right: 8,
                    bottom: MediaQuery.of(context).padding.bottom + 8,
                  ),
                  child: Row(
                    children: [
                      Expanded(
                        child: TextField(
                          controller: _messageController,
                          decoration: InputDecoration(
                            hintText: 'Type a message...',
                            filled: true,
                            fillColor: colorScheme.surfaceContainerHighest,
                            border: OutlineInputBorder(
                              borderRadius: BorderRadius.circular(24),
                              borderSide: BorderSide.none,
                            ),
                            contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
                            isDense: true,
                          ),
                          textInputAction: TextInputAction.send,
                          onSubmitted: (_) => _sendMessage(),
                          maxLines: null,
                        ),
                      ),
                      const SizedBox(width: 4),
                      IconButton.filled(
                        icon: const Icon(Icons.send, size: 20),
                        onPressed: _sendMessage,
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  IconData _channelIcon(String channel) => switch (channel) {
    'whatsapp' => Icons.message,
    'telegram' => Icons.send,
    'email'    => Icons.email,
    'sms'      => Icons.sms,
    'app'      => Icons.smartphone,
    _          => Icons.chat,
  };

  Color _channelColor(String channel) => switch (channel) {
    'whatsapp' => Colors.green,
    'telegram' => Colors.blue.shade400,
    'email'    => Colors.orange,
    'sms'      => Colors.purple,
    'app'      => Colors.teal,
    _          => Colors.grey,
  };

  String _channelLabel(String channel) => switch (channel) {
    'whatsapp' => 'WhatsApp',
    'telegram' => 'Telegram',
    'email'    => 'Email',
    'sms'      => 'SMS',
    'app'      => 'App',
    _          => channel,
  };
}

// =====================================================
// CLIENT CHAT BUBBLE
// =====================================================

class _ClientChatBubble extends StatelessWidget {
  final ClientMessage message;

  const _ClientChatBubble({required this.message});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;
    final isInbound = message.isInbound;

    return Padding(
      padding: const EdgeInsets.only(bottom: 6),
      child: Row(
        mainAxisAlignment: isInbound ? MainAxisAlignment.start : MainAxisAlignment.end,
        crossAxisAlignment: CrossAxisAlignment.end,
        children: [
          if (isInbound)
            Padding(
              padding: const EdgeInsets.only(right: 6),
              child: CircleAvatar(
                radius: 14,
                backgroundColor: colorScheme.secondaryContainer,
                child: Icon(Icons.person, size: 14, color: colorScheme.onSecondaryContainer),
              ),
            ),

          Flexible(
            child: Container(
              constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.75),
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
              decoration: BoxDecoration(
                color: isInbound ? colorScheme.surfaceContainerHighest : colorScheme.primary,
                borderRadius: BorderRadius.only(
                  topLeft: const Radius.circular(16),
                  topRight: const Radius.circular(16),
                  bottomLeft: Radius.circular(isInbound ? 4 : 16),
                  bottomRight: Radius.circular(isInbound ? 16 : 4),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Subject (for email)
                  if (message.subject != null && message.subject!.isNotEmpty)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 4),
                      child: Text(
                        message.subject!,
                        style: theme.textTheme.labelMedium?.copyWith(
                          fontWeight: FontWeight.bold,
                          color: isInbound ? colorScheme.onSurface : colorScheme.onPrimary,
                        ),
                      ),
                    ),

                  // Body
                  Text(
                    message.body,
                    style: theme.textTheme.bodyMedium?.copyWith(
                      color: isInbound ? colorScheme.onSurface : colorScheme.onPrimary,
                    ),
                  ),

                  // Footer: time + channel badge
                  const SizedBox(height: 4),
                  Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      // Channel badge
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 1),
                        decoration: BoxDecoration(
                          color: _channelColor(message.channel).withOpacity(0.2),
                          borderRadius: BorderRadius.circular(3),
                        ),
                        child: Row(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(
                              _channelIcon(message.channel),
                              size: 10,
                              color: _channelColor(message.channel),
                            ),
                            const SizedBox(width: 2),
                            Text(
                              message.channelLabel,
                              style: TextStyle(
                                fontSize: 9,
                                fontWeight: FontWeight.w600,
                                color: _channelColor(message.channel),
                              ),
                            ),
                          ],
                        ),
                      ),
                      const SizedBox(width: 6),
                      Text(
                        _formatTime(message.createdAt),
                        style: TextStyle(
                          fontSize: 10,
                          color: isInbound
                              ? colorScheme.outline
                              : colorScheme.onPrimary.withOpacity(0.6),
                        ),
                      ),
                      if (!isInbound && message.readAt != null) ...[
                        const SizedBox(width: 4),
                        Icon(
                          Icons.done_all,
                          size: 14,
                          color: Colors.lightBlueAccent,
                        ),
                      ],
                    ],
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  IconData _channelIcon(String? channel) => switch (channel) {
    'whatsapp' => Icons.message,
    'telegram' => Icons.send,
    'email'    => Icons.email,
    'sms'      => Icons.sms,
    'phone'    => Icons.phone,
    'app'      => Icons.smartphone,
    _          => Icons.chat,
  };

  Color _channelColor(String? channel) => switch (channel) {
    'whatsapp' => Colors.green,
    'telegram' => Colors.blue.shade400,
    'email'    => Colors.orange,
    'sms'      => Colors.purple,
    'phone'    => Colors.teal,
    'app'      => Colors.teal,
    _          => Colors.grey,
  };

  String _formatTime(DateTime dt) {
    return '${dt.hour.toString().padLeft(2, '0')}:${dt.minute.toString().padLeft(2, '0')}';
  }
}
