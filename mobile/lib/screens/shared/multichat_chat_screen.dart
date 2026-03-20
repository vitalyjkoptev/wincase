import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../models/boss_models.dart';
import '../../providers/boss_provider.dart';
import '../../main.dart';
import '../boss/boss_multichat_screen.dart' show channels;

// =====================================================
// WinCase brand accent (navy + info blue)
// =====================================================
const _accent = WC.navy;         // #015EA7
const _accentLight = Color(0xFF3B82F6); // info blue

/// Unified 3-panel multichat — admin panel copy.
/// LEFT:  Conversations — HIDDEN by default (toggle via edge button)
/// CENTER: Chat header + messages + input (always visible)
/// RIGHT: Client info — HIDDEN by default (toggle via edge button)
class UnifiedMultichatScreen extends ConsumerStatefulWidget {
  final bool isBoss;
  const UnifiedMultichatScreen({super.key, required this.isBoss});

  @override
  ConsumerState<UnifiedMultichatScreen> createState() => _UnifiedMultichatScreenState();
}

class _UnifiedMultichatScreenState extends ConsumerState<UnifiedMultichatScreen> {
  final _msgCtrl = TextEditingController();
  final _scrollCtrl = ScrollController();
  final _searchCtrl = TextEditingController();

  MultichatConversation? _selectedConv;
  bool _showLeftPanel = false;
  bool _showRightPanel = false;
  String _replyChannel = 'portal';
  String _activeTab = 'all';

  StateNotifierProvider<MultichatNotifier, MultichatState> get _listProv =>
      widget.isBoss ? bossMultichatProvider : staffMultichatProvider;

  StateNotifierProvider<ChatStreamNotifier, ChatStreamState> get _chatProv =>
      widget.isBoss ? bossChatStreamProvider : staffChatStreamProvider;

  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      ref.read(_listProv.notifier).load();
      // Load general stream — all messages
      ref.read(_chatProv.notifier).loadMessages(0);
    });
  }

  @override
  void dispose() {
    _msgCtrl.dispose();
    _scrollCtrl.dispose();
    _searchCtrl.dispose();
    super.dispose();
  }

  void _selectConversation(MultichatConversation conv) {
    setState(() {
      _selectedConv = conv;
      _replyChannel = conv.activeChannels.isNotEmpty ? conv.activeChannels.first : 'portal';
      _showLeftPanel = false;
    });
    ref.read(_chatProv.notifier).loadMessages(conv.clientId);
  }

  @override
  Widget build(BuildContext context) {
    final listSt = ref.watch(_listProv);

    return Scaffold(
      backgroundColor: Colors.white,
      body: Stack(
        children: [
          // CENTER — always visible: stats + header + messages + input
          Column(
            children: [
              SizedBox(height: MediaQuery.of(context).padding.top + 8),
              _buildStats(listSt),
              _buildChatHeader(),
              Expanded(child: _buildMessagesArea()),
              _buildInputArea(),
            ],
          ),

          // LEFT toggle (edge pill)
          if (!_showLeftPanel)
            Positioned(
              left: 0, top: MediaQuery.of(context).size.height * 0.45,
              child: _edgeBtn(Icons.chevron_right, () => setState(() => _showLeftPanel = true)),
            ),

          // RIGHT toggle (edge pill) — only when client selected
          if (!_showRightPanel && _selectedConv != null)
            Positioned(
              right: 0, top: MediaQuery.of(context).size.height * 0.45,
              child: _edgeBtn(Icons.chevron_left, () => setState(() => _showRightPanel = true), isRight: true),
            ),

          // LEFT PANEL overlay
          if (_showLeftPanel) ...[
            GestureDetector(
              onTap: () => setState(() => _showLeftPanel = false),
              child: Container(color: Colors.black.withOpacity(0.3)),
            ),
            Positioned(
              left: 0, top: 0, bottom: 0,
              width: MediaQuery.of(context).size.width * 0.85,
              child: _buildLeftPanel(),
            ),
          ],

          // RIGHT PANEL overlay
          if (_showRightPanel && _selectedConv != null) ...[
            GestureDetector(
              onTap: () => setState(() => _showRightPanel = false),
              child: Container(color: Colors.black.withOpacity(0.3)),
            ),
            Positioned(
              right: 0, top: 0, bottom: 0,
              width: MediaQuery.of(context).size.width * 0.82,
              child: _buildRightPanel(),
            ),
          ],
        ],
      ),
    );
  }

  // Edge toggle button (admin mc-toggle style)
  Widget _edgeBtn(IconData icon, VoidCallback onTap, {bool isRight = false}) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: 24, height: 48,
        decoration: BoxDecoration(
          color: Colors.white,
          border: Border.all(color: Colors.black.withOpacity(0.1)),
          borderRadius: isRight
              ? const BorderRadius.horizontal(left: Radius.circular(6))
              : const BorderRadius.horizontal(right: Radius.circular(6)),
          boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.08), blurRadius: 4)],
        ),
        child: Icon(icon, size: 16, color: const Color(0xFF6C757D)),
      ),
    );
  }

  // =====================================================
  // STATS ROW (admin panel SLA cards)
  // =====================================================
  Widget _buildStats(MultichatState listSt) {
    final convs = listSt.conversations;
    final unread = convs.where((c) => c.hasUnread).length;

    final List<_Stat> stats = widget.isBoss
        ? [
            _Stat(Icons.chat_bubble_outline, 'Conversations', '${convs.length}', _accent),
            _Stat(Icons.flash_on, 'Active Today', '${convs.length > 3 ? convs.length - 3 : convs.length}', _accentLight),
            _Stat(Icons.schedule, 'Awaiting Reply', '$unread', const Color(0xFFFFC107)),
            _Stat(Icons.warning_amber, 'Overdue', '${unread > 2 ? unread - 2 : 0}', WC.danger),
          ]
        : [
            _Stat(Icons.people_outline, 'My Clients', '${convs.length}', _accent),
            _Stat(Icons.chat, 'Active Chats', '${convs.length > 2 ? convs.length - 2 : convs.length}', _accentLight),
            _Stat(Icons.schedule, 'Pending Reply', '$unread', const Color(0xFFFFC107)),
            _Stat(Icons.warning_amber, 'Overdue', '${unread > 1 ? unread - 1 : 0}', WC.danger),
          ];

    return Container(
      padding: const EdgeInsets.fromLTRB(10, 8, 10, 6),
      decoration: BoxDecoration(
        color: Colors.white,
        border: Border(bottom: BorderSide(color: Colors.black.withOpacity(0.06))),
      ),
      child: Row(
        children: stats.map((s) {
          return Expanded(
            child: Container(
              margin: const EdgeInsets.symmetric(horizontal: 3),
              padding: const EdgeInsets.symmetric(vertical: 6, horizontal: 6),
              decoration: BoxDecoration(
                color: s.color.withOpacity(0.06),
                borderRadius: BorderRadius.circular(6),
                border: Border.all(color: s.color.withOpacity(0.15)),
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(s.icon, size: 14, color: s.color),
                  const SizedBox(height: 2),
                  Text(s.value, style: TextStyle(fontSize: 14, fontWeight: FontWeight.w800, color: s.color)),
                  Text(s.label, style: const TextStyle(fontSize: 8, color: Color(0xFF6C757D), fontWeight: FontWeight.w600), textAlign: TextAlign.center, maxLines: 1, overflow: TextOverflow.ellipsis),
                ],
              ),
            ),
          );
        }).toList(),
      ),
    );
  }

  // =====================================================
  // CHAT HEADER
  // =====================================================
  Widget _buildChatHeader() {
    return Container(
      padding: const EdgeInsets.fromLTRB(12, 8, 12, 8),
      decoration: BoxDecoration(color: Colors.white, border: Border(bottom: BorderSide(color: Colors.black.withOpacity(0.08)))),
      constraints: const BoxConstraints(minHeight: 48),
      child: _selectedConv != null
          ? Row(
              children: [
                CircleAvatar(
                  radius: 20,
                  backgroundColor: _accent.withOpacity(0.12),
                  child: Text(_selectedConv!.initials, style: const TextStyle(fontSize: 13, fontWeight: FontWeight.w700, color: _accent)),
                ),
                const SizedBox(width: 10),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisSize: MainAxisSize.min,
                    children: [
                      Text(_selectedConv!.clientName, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w700)),
                      Text(
                        'Case ${_selectedConv!.caseNumber ?? '#WC-2026'} • ${widget.isBoss ? (_selectedConv!.assignedWorkerName ?? '') : ''}',
                        style: const TextStyle(fontSize: 11, color: Color(0xFF888888)),
                      ),
                    ],
                  ),
                ),
                ..._selectedConv!.activeChannels.take(5).map((ch) {
                  final c = channels.firstWhere((d) => d.key == ch, orElse: () => channels[0]);
                  return Padding(
                    padding: const EdgeInsets.only(left: 2),
                    child: Container(
                      width: 22, height: 22,
                      decoration: BoxDecoration(color: c.color, borderRadius: BorderRadius.circular(4)),
                      child: Icon(c.icon, size: 12, color: Colors.white),
                    ),
                  );
                }),
                const SizedBox(width: 4),
                _headerAction(Icons.phone),
                const SizedBox(width: 4),
                _headerAction(Icons.videocam),
              ],
            )
          : Row(
              children: [
                const Icon(Icons.chat_bubble_outline, size: 20, color: Color(0xFF6C757D)),
                const SizedBox(width: 8),
                Text(
                  widget.isBoss ? 'Multichat — All Clients' : 'Multichat — My Clients',
                  style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w700, color: Color(0xFF6C757D)),
                ),
              ],
            ),
    );
  }

  Widget _headerAction(IconData icon) {
    return Container(
      width: 30, height: 30,
      decoration: BoxDecoration(color: _accent.withOpacity(0.08), borderRadius: BorderRadius.circular(6)),
      child: Icon(icon, size: 16, color: _accent),
    );
  }

  // =====================================================
  // MESSAGES AREA
  // =====================================================
  Widget _buildMessagesArea() {
    final st = ref.watch(_chatProv);
    if (st.isLoading) return Container(color: WC.background, child: const Center(child: CircularProgressIndicator(color: _accent)));
    if (st.messages.isEmpty) {
      return Container(color: WC.background, child: const Center(child: Text('No messages yet', style: TextStyle(color: Color(0xFF888888)))));
    }

    return Container(
      color: WC.background,
      child: ListView.builder(
        controller: _scrollCtrl,
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        itemCount: st.messages.length,
        itemBuilder: (_, i) {
          final msg = st.messages[i];
          // In general stream — show client name badge above each message
          if (_selectedConv == null) {
            return Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                if (i == 0 || st.messages[i].senderName != st.messages[i > 0 ? i - 1 : 0].senderName)
                  Padding(
                    padding: const EdgeInsets.only(top: 6, bottom: 2),
                    child: Text(msg.senderName ?? 'Unknown', style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: _accent)),
                  ),
                _messageBubble(msg),
              ],
            );
          }
          return _messageBubble(msg);
        },
      ),
    );
  }

  // =====================================================
  // MESSAGE BUBBLE
  // =====================================================
  Widget _messageBubble(MultichatMessage msg) {
    final isOut = !msg.isInbound;
    final chDef = channels.firstWhere((c) => c.key == msg.channel, orElse: () => channels[0]);

    return Align(
      alignment: isOut ? Alignment.centerRight : Alignment.centerLeft,
      child: Container(
        margin: const EdgeInsets.only(bottom: 8),
        constraints: BoxConstraints(maxWidth: MediaQuery.of(context).size.width * 0.70),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        decoration: BoxDecoration(
          color: isOut ? _accent : const Color(0xFFF0F2F5),
          borderRadius: BorderRadius.only(
            topLeft: const Radius.circular(12),
            topRight: const Radius.circular(12),
            bottomLeft: Radius.circular(isOut ? 12 : 2),
            bottomRight: Radius.circular(isOut ? 2 : 12),
          ),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (msg.senderName != null)
              Text(msg.senderName!, style: TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: isOut ? Colors.white.withOpacity(0.8) : _accent)),
            Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Icon(chDef.icon, size: 10, color: isOut ? Colors.white.withOpacity(0.6) : chDef.color),
                const SizedBox(width: 3),
                Text(chDef.label, style: TextStyle(fontSize: 10, fontWeight: FontWeight.w600, color: isOut ? Colors.white.withOpacity(0.6) : chDef.color)),
              ],
            ),
            const SizedBox(height: 3),
            Text(msg.body, style: TextStyle(fontSize: 13, color: isOut ? Colors.white : WC.textPrimary, height: 1.45)),
            const SizedBox(height: 2),
            Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(_fmtTime(msg.createdAt), style: TextStyle(fontSize: 10, color: isOut ? Colors.white.withOpacity(0.5) : const Color(0xFF888888))),
                if (isOut && msg.isRead) ...[
                  const SizedBox(width: 4),
                  Icon(Icons.done_all, size: 12, color: Colors.white.withOpacity(0.7)),
                ],
              ],
            ),
          ],
        ),
      ),
    );
  }

  // =====================================================
  // INPUT AREA
  // =====================================================
  Widget _buildInputArea() {
    final bool hasConv = _selectedConv != null;
    return Container(
      decoration: BoxDecoration(color: Colors.white, border: Border(top: BorderSide(color: Colors.black.withOpacity(0.08)))),
      padding: const EdgeInsets.only(bottom: 6),
      child: SafeArea(
        top: false,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            // Reply via channel selector
            if (hasConv)
              Container(
                padding: const EdgeInsets.fromLTRB(12, 8, 12, 4),
                child: SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    children: [
                      const Text('Reply via:', style: TextStyle(fontSize: 11, color: Color(0xFF888888))),
                      const SizedBox(width: 6),
                      ..._selectedConv!.activeChannels.map((ch) {
                        final c = channels.firstWhere((d) => d.key == ch, orElse: () => channels[0]);
                        final sel = _replyChannel == ch;
                        return GestureDetector(
                          onTap: () => setState(() => _replyChannel = ch),
                          child: Container(
                            margin: const EdgeInsets.only(right: 4),
                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                            decoration: BoxDecoration(
                              color: sel ? _accent : Colors.transparent,
                              borderRadius: BorderRadius.circular(4),
                              border: Border.all(color: sel ? _accent : Colors.black.withOpacity(0.1)),
                            ),
                            child: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                Icon(c.icon, size: 11, color: sel ? Colors.white : c.color),
                                const SizedBox(width: 3),
                                Text(c.label, style: TextStyle(fontSize: 10, fontWeight: FontWeight.w600, color: sel ? Colors.white : WC.textPrimary)),
                              ],
                            ),
                          ),
                        );
                      }),
                    ],
                  ),
                ),
              ),

            // Textarea + send + attach
            Padding(
              padding: const EdgeInsets.fromLTRB(8, 6, 8, 6),
              child: Row(
                crossAxisAlignment: CrossAxisAlignment.end,
                children: [
                  _inputIcon(Icons.attach_file, hasConv),
                  const SizedBox(width: 6),
                  Expanded(
                    child: TextField(
                      controller: _msgCtrl,
                      enabled: hasConv,
                      decoration: InputDecoration(
                        hintText: hasConv ? 'Type a message as ${widget.isBoss ? "Director" : "Worker"}...' : 'Select a conversation...',
                        hintStyle: const TextStyle(fontSize: 13, color: Color(0xFF999999)),
                        filled: true, fillColor: WC.background,
                        contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(6), borderSide: BorderSide(color: Colors.black.withOpacity(0.1))),
                        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(6), borderSide: BorderSide(color: Colors.black.withOpacity(0.1))),
                        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(6), borderSide: const BorderSide(color: _accent)),
                      ),
                      maxLines: 4, minLines: 1,
                      style: const TextStyle(fontSize: 13),
                      textInputAction: TextInputAction.send,
                      onSubmitted: (_) => _send(),
                    ),
                  ),
                  const SizedBox(width: 6),
                  GestureDetector(
                    onTap: hasConv ? _send : null,
                    child: Container(
                      width: 36, height: 36,
                      decoration: BoxDecoration(color: hasConv ? _accent : _accent.withOpacity(0.3), borderRadius: BorderRadius.circular(6)),
                      child: const Icon(Icons.send, color: Colors.white, size: 16),
                    ),
                  ),
                ],
              ),
            ),

            // Quick action buttons
            if (hasConv)
              Padding(
                padding: const EdgeInsets.fromLTRB(12, 0, 12, 8),
                child: SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    children: widget.isBoss
                        ? [_qBtn(Icons.check, 'Approve'), _qBtn(Icons.person_add, 'Reassign'), _qBtn(Icons.visibility, 'Reviewed'), _qBtn(Icons.notifications, 'Remind'), _qBtn(Icons.flag, 'Urgent')]
                        : [_qBtn(Icons.help_outline, 'Ask Boss'), _qBtn(Icons.arrow_upward, 'Escalate'), _qBtn(Icons.copy, 'Template'), _qBtn(Icons.schedule, 'Remind'), _qBtn(Icons.flag, 'Urgent')],
                  ),
                ),
              ),
          ],
        ),
      ),
    );
  }

  Widget _inputIcon(IconData icon, bool enabled) {
    return Container(
      width: 36, height: 36,
      decoration: BoxDecoration(color: WC.background, borderRadius: BorderRadius.circular(6)),
      child: Icon(icon, size: 18, color: enabled ? const Color(0xFF6C757D) : const Color(0xFFCCCCCC)),
    );
  }

  Widget _qBtn(IconData icon, String label) {
    return Container(
      margin: const EdgeInsets.only(right: 6),
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      decoration: BoxDecoration(borderRadius: BorderRadius.circular(12), border: Border.all(color: _accent.withOpacity(0.25))),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 12, color: _accent),
          const SizedBox(width: 3),
          Text(label, style: const TextStyle(fontSize: 10, color: _accent, fontWeight: FontWeight.w600)),
        ],
      ),
    );
  }

  void _send() {
    final text = _msgCtrl.text.trim();
    if (text.isEmpty || _selectedConv == null) return;
    ref.read(_chatProv.notifier).sendMessage(_selectedConv!.clientId, text, _replyChannel);
    _msgCtrl.clear();
  }

  // =====================================================
  // LEFT PANEL — Conversations
  // =====================================================
  Widget _buildLeftPanel() {
    final st = ref.watch(_listProv);
    return Material(
      elevation: 8,
      child: Container(
        color: Colors.white,
        child: Column(
          children: [
            Container(
              padding: EdgeInsets.fromLTRB(12, MediaQuery.of(context).padding.top + 10, 4, 10),
              decoration: BoxDecoration(border: Border(bottom: BorderSide(color: Colors.black.withOpacity(0.06)))),
              child: Row(
                children: [
                  Icon(Icons.chat, size: 18, color: _accentLight),
                  const SizedBox(width: 8),
                  Expanded(child: Text(widget.isBoss ? 'CLIENTS' : 'MY CLIENTS', style: const TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: Color(0xFF6C757D), letterSpacing: 0.5))),
                  IconButton(icon: const Icon(Icons.chevron_left, size: 18, color: Color(0xFF6C757D)), onPressed: () => setState(() => _showLeftPanel = false), padding: EdgeInsets.zero, constraints: const BoxConstraints(minWidth: 32, minHeight: 32)),
                ],
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(10),
              child: TextField(
                controller: _searchCtrl,
                decoration: InputDecoration(
                  hintText: widget.isBoss ? 'Search clients...' : 'Search my clients...',
                  hintStyle: const TextStyle(fontSize: 12, color: Color(0xFF999999)),
                  prefixIcon: const Icon(Icons.search, size: 16, color: Color(0xFF999999)),
                  filled: true, fillColor: WC.background,
                  contentPadding: const EdgeInsets.symmetric(vertical: 8),
                  border: OutlineInputBorder(borderRadius: BorderRadius.circular(6), borderSide: BorderSide.none),
                ),
                style: const TextStyle(fontSize: 12),
                onSubmitted: (v) => ref.read(_listProv.notifier).search(v),
              ),
            ),
            // Tabs
            Container(
              decoration: BoxDecoration(border: Border(bottom: BorderSide(color: Colors.black.withOpacity(0.06)))),
              child: Row(children: [
                _tab('All', 'all', st.conversations.length),
                _tab('Unread', 'unread', st.conversations.where((c) => c.hasUnread).length),
                _tab('Overdue', 'overdue', 0),
              ]),
            ),
            Expanded(
              child: st.isLoading
                  ? const Center(child: CircularProgressIndicator(color: _accent))
                  : st.conversations.isEmpty
                      ? const Center(child: Text('No conversations', style: TextStyle(color: Color(0xFF999999), fontSize: 12)))
                      : ListView.builder(
                          itemCount: st.conversations.length,
                          itemBuilder: (_, i) {
                            final conv = st.conversations[i];
                            if (_activeTab == 'unread' && !conv.hasUnread) return const SizedBox.shrink();
                            return _convTile(conv);
                          },
                        ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _tab(String label, String key, int count) {
    final sel = _activeTab == key;
    return Expanded(
      child: GestureDetector(
        onTap: () => setState(() => _activeTab = key),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 8),
          decoration: BoxDecoration(border: Border(bottom: BorderSide(color: sel ? _accent : Colors.transparent, width: 2))),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text(label, style: TextStyle(fontSize: 11, fontWeight: FontWeight.w600, color: sel ? _accent : const Color(0xFF999999))),
              const SizedBox(width: 4),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 1),
                decoration: BoxDecoration(color: key == 'unread' ? WC.danger : key == 'overdue' ? const Color(0xFFFFC107) : const Color(0xFF6C757D), borderRadius: BorderRadius.circular(8)),
                child: Text('$count', style: const TextStyle(fontSize: 9, color: Colors.white, fontWeight: FontWeight.w700)),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _convTile(MultichatConversation conv) {
    final chDef = channels.firstWhere((c) => c.key == conv.lastChannel, orElse: () => channels[0]);
    final now = DateTime.now();
    final diff = now.difference(conv.lastMessageAt);
    final timeStr = diff.inMinutes < 60 ? '${diff.inMinutes}m' : diff.inHours < 24 ? '${diff.inHours}h' : '${diff.inDays}d';
    final isSelected = _selectedConv?.clientId == conv.clientId;

    return InkWell(
      onTap: () => _selectConversation(conv),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 9),
        decoration: BoxDecoration(
          color: isSelected ? _accent.withOpacity(0.1) : conv.hasUnread ? _accent.withOpacity(0.03) : Colors.white,
          border: Border(
            bottom: BorderSide(color: Colors.black.withOpacity(0.03)),
            left: isSelected ? const BorderSide(color: _accent, width: 3) : BorderSide.none,
          ),
        ),
        child: Row(
          children: [
            Stack(
              clipBehavior: Clip.none,
              children: [
                CircleAvatar(radius: 19, backgroundColor: _accent.withOpacity(0.12), child: Text(conv.initials, style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w700, color: _accent))),
                Positioned(
                  bottom: -2, right: -4,
                  child: Row(
                    children: conv.activeChannels.take(3).map((ch) {
                      final c = channels.firstWhere((d) => d.key == ch, orElse: () => channels[0]);
                      return Container(width: 10, height: 10, margin: const EdgeInsets.only(right: 1), decoration: BoxDecoration(color: c.color, shape: BoxShape.circle, border: Border.all(color: Colors.white, width: 1.5)));
                    }).toList(),
                  ),
                ),
              ],
            ),
            const SizedBox(width: 10),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(children: [
                    Expanded(child: Text(conv.clientName, style: TextStyle(fontSize: 12, fontWeight: conv.hasUnread ? FontWeight.w700 : FontWeight.w600), overflow: TextOverflow.ellipsis)),
                    Text(timeStr, style: TextStyle(fontSize: 10, color: conv.hasUnread ? _accent : const Color(0xFFAAAAAA))),
                  ]),
                  Text(conv.lastMessage, style: TextStyle(fontSize: 11, color: conv.hasUnread ? WC.textPrimary : const Color(0xFF888888)), overflow: TextOverflow.ellipsis, maxLines: 1),
                  const SizedBox(height: 2),
                  Row(children: [
                    ...conv.activeChannels.take(4).map((ch) {
                      final c = channels.firstWhere((d) => d.key == ch, orElse: () => channels[0]);
                      return Container(margin: const EdgeInsets.only(right: 3), padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 1), decoration: BoxDecoration(color: c.color, borderRadius: BorderRadius.circular(3)), child: Text(c.label, style: const TextStyle(fontSize: 8, color: Colors.white, fontWeight: FontWeight.w700)));
                    }),
                    if (widget.isBoss && conv.assignedWorkerName != null) ...[const Spacer(), Text(conv.assignedWorkerName!, style: const TextStyle(fontSize: 9, color: Color(0xFF999999)))],
                    if (conv.hasUnread) ...[
                      const Spacer(),
                      Container(padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 1), decoration: BoxDecoration(color: WC.danger, borderRadius: BorderRadius.circular(10)), child: Text('${conv.unreadCount}', style: const TextStyle(fontSize: 9, color: Colors.white, fontWeight: FontWeight.w700))),
                    ],
                  ]),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  // =====================================================
  // RIGHT PANEL — Client Info
  // =====================================================
  Widget _buildRightPanel() {
    final conv = _selectedConv!;
    return Material(
      elevation: 8,
      child: Container(
        color: Colors.white,
        child: Column(
          children: [
            Container(
              padding: EdgeInsets.fromLTRB(12, MediaQuery.of(context).padding.top + 10, 4, 10),
              decoration: BoxDecoration(border: Border(bottom: BorderSide(color: Colors.black.withOpacity(0.06)))),
              child: Row(
                children: [
                  Icon(Icons.person, size: 18, color: _accentLight),
                  const SizedBox(width: 8),
                  const Expanded(child: Text('CLIENT INFO', style: TextStyle(fontSize: 12, fontWeight: FontWeight.w700, color: Color(0xFF6C757D), letterSpacing: 0.3))),
                  IconButton(icon: const Icon(Icons.chevron_right, size: 18, color: Color(0xFF6C757D)), onPressed: () => setState(() => _showRightPanel = false), padding: EdgeInsets.zero, constraints: const BoxConstraints(minWidth: 32, minHeight: 32)),
                ],
              ),
            ),
            Expanded(
              child: ListView(
                padding: const EdgeInsets.all(10),
                children: [
                  _card('Contact', Icons.person, [
                    _cardRow('Name', conv.clientName, bold: true),
                    _cardRow('Phone', conv.clientPhone ?? '+48 5XX XXX'),
                    _cardRow('Email', conv.clientEmail ?? 'client@email.com'),
                  ]),
                  _card('Case', Icons.work, [
                    _cardRow('Number', conv.caseNumber ?? '#WC-2026-0XXX', bold: true),
                    _cardRow('Type', 'TRP'),
                    _cardRowBadge('Stage', conv.caseStatus ?? 'In Review', const Color(0xFFFFC107)),
                    if (widget.isBoss && conv.assignedWorkerName != null) _cardRow('Manager', conv.assignedWorkerName!, valueColor: _accentLight),
                  ]),
                  _card('Channels (${conv.activeChannels.length})', Icons.chat, [
                    ...conv.activeChannels.map((ch) {
                      final c = channels.firstWhere((d) => d.key == ch, orElse: () => channels[0]);
                      return Padding(
                        padding: const EdgeInsets.symmetric(vertical: 3),
                        child: Row(children: [
                          Container(padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 2), decoration: BoxDecoration(color: c.color, borderRadius: BorderRadius.circular(3)), child: Text(c.label, style: const TextStyle(fontSize: 8, color: Colors.white, fontWeight: FontWeight.w700))),
                          const SizedBox(width: 6),
                          Text(ch[0].toUpperCase() + ch.substring(1), style: const TextStyle(fontSize: 11)),
                          const Spacer(),
                          const Text('msgs', style: TextStyle(fontSize: 10, color: Color(0xFF999999))),
                        ]),
                      );
                    }),
                  ]),
                  _card('Response', Icons.timer, [
                    _cardRow('Avg reply', '12 min', bold: true),
                    _cardRow('Last reply', '09:52'),
                    _cardRowBadge('SLA', 'OK', _accentLight),
                  ]),
                  _card('Shared Files', Icons.folder, [
                    _fileRow('bank_feb.pdf', '1.2MB', Icons.picture_as_pdf, WC.danger),
                    _fileRow('passport.jpg', '3.4MB', Icons.image, _accentLight),
                    _fileRow('contract.pdf', '0.8MB', Icons.picture_as_pdf, WC.danger),
                  ]),
                  const SizedBox(height: 16),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _card(String title, IconData icon, List<Widget> rows) {
    return Container(
      margin: const EdgeInsets.only(bottom: 8),
      padding: const EdgeInsets.all(10),
      decoration: BoxDecoration(color: const Color(0xFFF8F9FA), borderRadius: BorderRadius.circular(8)),
      child: Column(crossAxisAlignment: CrossAxisAlignment.start, children: [
        Row(children: [Icon(icon, size: 13, color: const Color(0xFF6C757D)), const SizedBox(width: 5), Text(title, style: const TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: Color(0xFF6C757D), letterSpacing: 0.3))]),
        const SizedBox(height: 6),
        ...rows,
      ]),
    );
  }

  Widget _cardRow(String label, String value, {bool bold = false, Color? valueColor}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 3),
      child: Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
        Text(label, style: const TextStyle(fontSize: 11, color: Color(0xFF888888))),
        Flexible(child: Text(value, style: TextStyle(fontSize: 11, fontWeight: bold ? FontWeight.w700 : FontWeight.w500, color: valueColor ?? WC.textPrimary), overflow: TextOverflow.ellipsis)),
      ]),
    );
  }

  Widget _cardRowBadge(String label, String badge, Color color) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 3),
      child: Row(mainAxisAlignment: MainAxisAlignment.spaceBetween, children: [
        Text(label, style: const TextStyle(fontSize: 11, color: Color(0xFF888888))),
        Container(padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2), decoration: BoxDecoration(color: color, borderRadius: BorderRadius.circular(4)), child: Text(badge, style: const TextStyle(fontSize: 9, color: Colors.white, fontWeight: FontWeight.w700))),
      ]),
    );
  }

  Widget _fileRow(String name, String size, IconData icon, Color color) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 3),
      child: Row(children: [Icon(icon, size: 14, color: color), const SizedBox(width: 6), Expanded(child: Text(name, style: const TextStyle(fontSize: 11), overflow: TextOverflow.ellipsis)), Text(size, style: const TextStyle(fontSize: 10, color: Color(0xFF999999)))]),
    );
  }

  String _fmtTime(DateTime dt) => '${dt.hour.toString().padLeft(2, '0')}:${dt.minute.toString().padLeft(2, '0')}';
}

class _Stat {
  final IconData icon;
  final String label;
  final String value;
  final Color color;
  const _Stat(this.icon, this.label, this.value, this.color);
}
