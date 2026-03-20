// =====================================================
// WINCASE CRM -- Staff Boss Chat Screen
// WhatsApp-style chat UI with boss, encrypted badge
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../providers/staff_provider.dart';
import '../../models/staff_models.dart';
import '../../providers/auth_provider.dart';

class StaffBossChatScreen extends ConsumerStatefulWidget {
  const StaffBossChatScreen({super.key});

  @override
  ConsumerState<StaffBossChatScreen> createState() => _StaffBossChatScreenState();
}

class _StaffBossChatScreenState extends ConsumerState<StaffBossChatScreen> {
  final _messageController = TextEditingController();
  final _scrollController = ScrollController();
  int? _attachCaseId;
  int? _attachClientId;

  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(bossChatProvider.notifier).load());
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _messageController.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    // Load more when scrolled to top
    if (_scrollController.position.pixels <= _scrollController.position.minScrollExtent + 50) {
      // Could load more pages here
    }
  }

  void _sendMessage() {
    final body = _messageController.text.trim();
    if (body.isEmpty) return;

    ref.read(bossChatProvider.notifier).send(
      body,
      caseId: _attachCaseId,
      clientId: _attachClientId,
    );

    _messageController.clear();
    setState(() {
      _attachCaseId = null;
      _attachClientId = null;
    });
  }

  void _showAttachDialog() {
    showModalBottomSheet(
      context: context,
      builder: (ctx) => Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Attach Reference', style: Theme.of(ctx).textTheme.titleMedium),
            const SizedBox(height: 16),
            ListTile(
              leading: const Icon(Icons.folder),
              title: const Text('Attach Case'),
              subtitle: const Text('Link a case to this message'),
              onTap: () {
                Navigator.pop(ctx);
                _showCaseIdInput();
              },
            ),
            ListTile(
              leading: const Icon(Icons.person),
              title: const Text('Attach Client'),
              subtitle: const Text('Link a client to this message'),
              onTap: () {
                Navigator.pop(ctx);
                _showClientIdInput();
              },
            ),
          ],
        ),
      ),
    );
  }

  void _showCaseIdInput() {
    final controller = TextEditingController();
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Enter Case ID'),
        content: TextField(
          controller: controller,
          keyboardType: TextInputType.number,
          decoration: const InputDecoration(hintText: 'Case ID'),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancel')),
          FilledButton(
            onPressed: () {
              final id = int.tryParse(controller.text);
              if (id != null) {
                setState(() => _attachCaseId = id);
              }
              Navigator.pop(ctx);
            },
            child: const Text('Attach'),
          ),
        ],
      ),
    );
  }

  void _showClientIdInput() {
    final controller = TextEditingController();
    showDialog(
      context: context,
      builder: (ctx) => AlertDialog(
        title: const Text('Enter Client ID'),
        content: TextField(
          controller: controller,
          keyboardType: TextInputType.number,
          decoration: const InputDecoration(hintText: 'Client ID'),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(ctx), child: const Text('Cancel')),
          FilledButton(
            onPressed: () {
              final id = int.tryParse(controller.text);
              if (id != null) {
                setState(() => _attachClientId = id);
              }
              Navigator.pop(ctx);
            },
            child: const Text('Attach'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(bossChatProvider);
    final authState = ref.watch(authProvider);
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            if (state.boss != null) ...[
              CircleAvatar(
                radius: 16,
                backgroundImage: state.boss!.avatarUrl != null
                    ? NetworkImage(state.boss!.avatarUrl!)
                    : null,
                child: state.boss!.avatarUrl == null
                    ? Text(
                        state.boss!.name.isNotEmpty ? state.boss!.name[0] : 'B',
                        style: const TextStyle(fontSize: 14),
                      )
                    : null,
              ),
              const SizedBox(width: 8),
            ],
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    state.boss?.name ?? 'Boss',
                    style: theme.textTheme.titleMedium,
                  ),
                  Text(
                    state.boss?.role ?? 'Administrator',
                    style: theme.textTheme.bodySmall?.copyWith(
                      color: colorScheme.onSurfaceVariant,
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
        actions: [
          // Encrypted badge
          Tooltip(
            message: 'End-to-end encrypted',
            child: Padding(
              padding: const EdgeInsets.only(right: 12),
              child: Icon(Icons.lock, size: 18, color: Colors.green.shade600),
            ),
          ),
        ],
      ),
      body: Column(
        children: [
          // --- Messages List ---
          Expanded(
            child: state.isLoading && state.messages.isEmpty
                ? const Center(child: CircularProgressIndicator())
                : state.messages.isEmpty
                    ? Center(
                        child: Column(
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Icon(Icons.chat_bubble_outline, size: 64, color: colorScheme.outline),
                            const SizedBox(height: 16),
                            Text('No messages yet', style: theme.textTheme.titleMedium),
                            const SizedBox(height: 4),
                            Text(
                              'Start a conversation with your boss',
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
                          final isMe = msg.senderName == (authState.userName ?? '');
                          final showAvatar = !isMe && (index == state.messages.length - 1 ||
                              state.messages[index + 1].senderId != msg.senderId);

                          return _ChatBubble(
                            message: msg,
                            isMe: isMe,
                            showAvatar: showAvatar,
                            bossAvatar: state.boss?.avatarUrl,
                            bossName: state.boss?.name ?? 'Boss',
                          );
                        },
                      ),
          ),

          // --- Attachment indicator ---
          if (_attachCaseId != null || _attachClientId != null)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
              color: colorScheme.surfaceContainerHighest,
              child: Row(
                children: [
                  Icon(Icons.link, size: 16, color: colorScheme.primary),
                  const SizedBox(width: 8),
                  if (_attachCaseId != null)
                    Chip(
                      label: Text('Case #$_attachCaseId', style: const TextStyle(fontSize: 11)),
                      deleteIcon: const Icon(Icons.close, size: 14),
                      onDeleted: () => setState(() => _attachCaseId = null),
                      visualDensity: VisualDensity.compact,
                    ),
                  if (_attachClientId != null) ...[
                    const SizedBox(width: 4),
                    Chip(
                      label: Text('Client #$_attachClientId', style: const TextStyle(fontSize: 11)),
                      deleteIcon: const Icon(Icons.close, size: 14),
                      onDeleted: () => setState(() => _attachClientId = null),
                      visualDensity: VisualDensity.compact,
                    ),
                  ],
                ],
              ),
            ),

          // --- Input Field ---
          Container(
            padding: EdgeInsets.only(
              left: 8,
              right: 8,
              top: 8,
              bottom: MediaQuery.of(context).padding.bottom + 8,
            ),
            decoration: BoxDecoration(
              color: colorScheme.surface,
              border: Border(top: BorderSide(color: colorScheme.outlineVariant, width: 0.5)),
            ),
            child: Row(
              children: [
                IconButton(
                  icon: const Icon(Icons.attach_file),
                  onPressed: _showAttachDialog,
                  tooltip: 'Attach reference',
                ),
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
    );
  }
}

// =====================================================
// CHAT BUBBLE
// =====================================================

class _ChatBubble extends StatelessWidget {
  final ChatMessage message;
  final bool isMe;
  final bool showAvatar;
  final String? bossAvatar;
  final String bossName;

  const _ChatBubble({
    required this.message,
    required this.isMe,
    required this.showAvatar,
    this.bossAvatar,
    required this.bossName,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    return Padding(
      padding: const EdgeInsets.only(bottom: 4),
      child: Row(
        mainAxisAlignment: isMe ? MainAxisAlignment.end : MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.end,
        children: [
          // Boss avatar
          if (!isMe) ...[
            if (showAvatar)
              CircleAvatar(
                radius: 14,
                backgroundImage: bossAvatar != null ? NetworkImage(bossAvatar!) : null,
                child: bossAvatar == null
                    ? Text(bossName.isNotEmpty ? bossName[0] : 'B', style: const TextStyle(fontSize: 11))
                    : null,
              )
            else
              const SizedBox(width: 28),
            const SizedBox(width: 6),
          ],

          // Message bubble
          Flexible(
            child: Container(
              constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.75),
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 10),
              decoration: BoxDecoration(
                color: isMe ? colorScheme.primary : colorScheme.surfaceContainerHighest,
                borderRadius: BorderRadius.only(
                  topLeft: const Radius.circular(16),
                  topRight: const Radius.circular(16),
                  bottomLeft: Radius.circular(isMe ? 16 : 4),
                  bottomRight: Radius.circular(isMe ? 4 : 16),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Case/Client reference
                  if (message.caseId != null || message.clientId != null)
                    Padding(
                      padding: const EdgeInsets.only(bottom: 4),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Icon(
                            Icons.link,
                            size: 12,
                            color: isMe ? colorScheme.onPrimary.withOpacity(0.7) : colorScheme.primary,
                          ),
                          const SizedBox(width: 4),
                          if (message.caseId != null)
                            Text(
                              'Case #${message.caseId}',
                              style: TextStyle(
                                fontSize: 10,
                                color: isMe ? colorScheme.onPrimary.withOpacity(0.7) : colorScheme.primary,
                              ),
                            ),
                          if (message.clientId != null) ...[
                            if (message.caseId != null) const SizedBox(width: 4),
                            Text(
                              'Client #${message.clientId}',
                              style: TextStyle(
                                fontSize: 10,
                                color: isMe ? colorScheme.onPrimary.withOpacity(0.7) : colorScheme.primary,
                              ),
                            ),
                          ],
                        ],
                      ),
                    ),

                  // Body
                  Text(
                    message.body,
                    style: theme.textTheme.bodyMedium?.copyWith(
                      color: isMe ? colorScheme.onPrimary : colorScheme.onSurface,
                    ),
                  ),

                  // Time + encrypted
                  const SizedBox(height: 4),
                  Row(
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(
                        _formatTime(message.createdAt),
                        style: TextStyle(
                          fontSize: 10,
                          color: isMe
                              ? colorScheme.onPrimary.withOpacity(0.6)
                              : colorScheme.outline,
                        ),
                      ),
                      if (message.isEncrypted) ...[
                        const SizedBox(width: 4),
                        Icon(
                          Icons.lock,
                          size: 10,
                          color: isMe
                              ? colorScheme.onPrimary.withOpacity(0.6)
                              : Colors.green.shade600,
                        ),
                      ],
                      if (isMe) ...[
                        const SizedBox(width: 4),
                        Icon(
                          message.isRead ? Icons.done_all : Icons.done,
                          size: 14,
                          color: message.isRead
                              ? Colors.lightBlueAccent
                              : colorScheme.onPrimary.withOpacity(0.6),
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

  String _formatTime(DateTime dt) {
    return '${dt.hour.toString().padLeft(2, '0')}:${dt.minute.toString().padLeft(2, '0')}';
  }
}
