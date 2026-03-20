// =====================================================
// WINCASE CRM -- Staff Clients Screen
// Search, filter, client cards with contact actions
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../providers/staff_provider.dart';
import '../../models/staff_models.dart';
import '../../providers/auth_provider.dart';

class StaffClientsScreen extends ConsumerStatefulWidget {
  const StaffClientsScreen({super.key});

  @override
  ConsumerState<StaffClientsScreen> createState() => _StaffClientsScreenState();
}

class _StaffClientsScreenState extends ConsumerState<StaffClientsScreen> {
  final _searchController = TextEditingController();
  String _selectedFilter = 'all';

  static const _filters = ['all', 'active', 'pending', 'completed'];

  @override
  void initState() {
    super.initState();
    Future.microtask(() => ref.read(staffClientsProvider.notifier).load());
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  void _onFilterChanged(String filter) {
    setState(() => _selectedFilter = filter);
    ref.read(staffClientsProvider.notifier).load(
      search: _searchController.text.isEmpty ? null : _searchController.text,
      status: filter == 'all' ? null : filter,
    );
  }

  void _onSearch(String query) {
    ref.read(staffClientsProvider.notifier).load(
      search: query.isEmpty ? null : query,
      status: _selectedFilter == 'all' ? null : _selectedFilter,
    );
  }

  void _showClientDetail(StaffClient client) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      useSafeArea: true,
      builder: (ctx) => _ClientDetailSheet(client: client),
    );
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(staffClientsProvider);
    final theme = Theme.of(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('My Clients'),
        bottom: PreferredSize(
          preferredSize: const Size.fromHeight(108),
          child: Column(
            children: [
              // --- Search Bar ---
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                child: SearchBar(
                  controller: _searchController,
                  hintText: 'Search clients...',
                  leading: const Icon(Icons.search, size: 20),
                  trailing: [
                    if (_searchController.text.isNotEmpty)
                      IconButton(
                        icon: const Icon(Icons.clear, size: 18),
                        onPressed: () {
                          _searchController.clear();
                          _onSearch('');
                        },
                      ),
                  ],
                  onChanged: _onSearch,
                  elevation: WidgetStateProperty.all(0),
                  padding: WidgetStateProperty.all(
                    const EdgeInsets.symmetric(horizontal: 12),
                  ),
                ),
              ),
              // --- Filter Chips ---
              SizedBox(
                height: 44,
                child: ListView(
                  scrollDirection: Axis.horizontal,
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  children: _filters.map((filter) {
                    final isSelected = _selectedFilter == filter;
                    return Padding(
                      padding: const EdgeInsets.only(right: 8),
                      child: FilterChip(
                        label: Text(_filterLabel(filter)),
                        selected: isSelected,
                        onSelected: (_) => _onFilterChanged(filter),
                      ),
                    );
                  }).toList(),
                ),
              ),
            ],
          ),
        ),
      ),
      body: _buildBody(state, theme),
    );
  }

  Widget _buildBody(ClientsState state, ThemeData theme) {
    if (state.isLoading) {
      return const Center(child: CircularProgressIndicator());
    }

    if (state.error != null) {
      return Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.error_outline, size: 48, color: theme.colorScheme.error),
            const SizedBox(height: 8),
            Text(state.error!, style: theme.textTheme.bodyMedium),
            const SizedBox(height: 8),
            FilledButton.tonal(
              onPressed: () => ref.read(staffClientsProvider.notifier).load(),
              child: const Text('Retry'),
            ),
          ],
        ),
      );
    }

    if (state.clients.isEmpty) {
      return Center(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(Icons.people_outline, size: 64, color: theme.colorScheme.outline),
            const SizedBox(height: 16),
            Text('No clients found', style: theme.textTheme.titleMedium),
          ],
        ),
      );
    }

    return RefreshIndicator(
      onRefresh: () => ref.read(staffClientsProvider.notifier).load(
        search: _searchController.text.isEmpty ? null : _searchController.text,
        status: _selectedFilter == 'all' ? null : _selectedFilter,
      ),
      child: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: state.clients.length,
        itemBuilder: (context, index) => _ClientCard(
          client: state.clients[index],
          onTap: () => _showClientDetail(state.clients[index]),
        ),
      ),
    );
  }

  String _filterLabel(String filter) => switch (filter) {
    'all' => 'All',
    'active' => 'Active',
    'pending' => 'Pending',
    'completed' => 'Completed',
    _ => filter,
  };
}

// =====================================================
// CLIENT CARD
// =====================================================

class _ClientCard extends StatelessWidget {
  final StaffClient client;
  final VoidCallback onTap;

  const _ClientCard({required this.client, required this.onTap});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;
    final casesCount = client.cases?.length ?? 0;

    return Card(
      elevation: 0,
      margin: const EdgeInsets.only(bottom: 8),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Row(
            children: [
              // Avatar / Initials
              CircleAvatar(
                radius: 24,
                backgroundColor: colorScheme.primaryContainer,
                child: Text(
                  client.initials,
                  style: theme.textTheme.titleMedium?.copyWith(
                    color: colorScheme.onPrimaryContainer,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              const SizedBox(width: 12),
              // Info
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Expanded(
                          child: Text(
                            client.displayName,
                            style: theme.textTheme.titleSmall?.copyWith(fontWeight: FontWeight.w600),
                            maxLines: 1,
                            overflow: TextOverflow.ellipsis,
                          ),
                        ),
                        if (client.nationality != null)
                          Text(
                            _nationalityFlag(client.nationality!),
                            style: const TextStyle(fontSize: 18),
                          ),
                      ],
                    ),
                    const SizedBox(height: 4),
                    if (client.phone != null)
                      Row(
                        children: [
                          Icon(Icons.phone, size: 14, color: colorScheme.outline),
                          const SizedBox(width: 4),
                          Text(
                            client.phone!,
                            style: theme.textTheme.bodySmall?.copyWith(color: colorScheme.outline),
                          ),
                        ],
                      ),
                    const SizedBox(height: 4),
                    Row(
                      children: [
                        _StatusChip(status: client.status),
                        const SizedBox(width: 8),
                        if (casesCount > 0)
                          Text(
                            '$casesCount case${casesCount > 1 ? 's' : ''}',
                            style: theme.textTheme.labelSmall?.copyWith(color: colorScheme.outline),
                          ),
                      ],
                    ),
                  ],
                ),
              ),
              // Contact actions
              Column(
                children: [
                  IconButton(
                    icon: Icon(Icons.phone, color: Colors.green.shade700, size: 20),
                    onPressed: client.phone != null
                        ? () => launchUrl(Uri.parse('tel:${client.phone}'))
                        : null,
                    constraints: const BoxConstraints(minWidth: 36, minHeight: 36),
                    padding: EdgeInsets.zero,
                    tooltip: 'Call',
                  ),
                  IconButton(
                    icon: Icon(Icons.chat, color: colorScheme.primary, size: 20),
                    onPressed: () => context.push('/clients/${client.id}/chat'),
                    constraints: const BoxConstraints(minWidth: 36, minHeight: 36),
                    padding: EdgeInsets.zero,
                    tooltip: 'Message',
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  String _nationalityFlag(String nationality) {
    final flags = <String, String>{
      'UA' : '\u{1F1FA}\u{1F1E6}', 'PL' : '\u{1F1F5}\u{1F1F1}',
      'BY' : '\u{1F1E7}\u{1F1FE}', 'RU' : '\u{1F1F7}\u{1F1FA}',
      'GE' : '\u{1F1EC}\u{1F1EA}', 'IN' : '\u{1F1EE}\u{1F1F3}',
      'BD' : '\u{1F1E7}\u{1F1E9}', 'NP' : '\u{1F1F3}\u{1F1F5}',
      'PH' : '\u{1F1F5}\u{1F1ED}', 'UZ' : '\u{1F1FA}\u{1F1FF}',
    };
    return flags[nationality.toUpperCase()] ?? '\u{1F3F3}';
  }
}

// =====================================================
// STATUS CHIP
// =====================================================

class _StatusChip extends StatelessWidget {
  final String status;

  const _StatusChip({required this.status});

  @override
  Widget build(BuildContext context) {
    final (Color bg, Color fg) = switch (status) {
      'active'    => (Colors.green.shade50, Colors.green.shade800),
      'pending'   => (Colors.orange.shade50, Colors.orange.shade800),
      'completed' => (Colors.blue.shade50, Colors.blue.shade800),
      _           => (Colors.grey.shade100, Colors.grey.shade800),
    };

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
      decoration: BoxDecoration(
        color: bg,
        borderRadius: BorderRadius.circular(4),
      ),
      child: Text(
        status[0].toUpperCase() + status.substring(1),
        style: TextStyle(fontSize: 10, fontWeight: FontWeight.w600, color: fg),
      ),
    );
  }
}

// =====================================================
// CLIENT DETAIL BOTTOM SHEET
// =====================================================

class _ClientDetailSheet extends StatelessWidget {
  final StaffClient client;

  const _ClientDetailSheet({required this.client});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    return DraggableScrollableSheet(
      expand: false,
      initialChildSize: 0.65,
      minChildSize: 0.4,
      maxChildSize: 0.9,
      builder: (context, scrollController) {
        return SingleChildScrollView(
          controller: scrollController,
          padding: const EdgeInsets.all(24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Handle
              Center(
                child: Container(
                  width: 40,
                  height: 4,
                  decoration: BoxDecoration(
                    color: colorScheme.outlineVariant,
                    borderRadius: BorderRadius.circular(2),
                  ),
                ),
              ),
              const SizedBox(height: 24),

              // Avatar + Name
              Row(
                children: [
                  CircleAvatar(
                    radius: 32,
                    backgroundColor: colorScheme.primaryContainer,
                    child: Text(
                      client.initials,
                      style: theme.textTheme.headlineSmall?.copyWith(
                        color: colorScheme.onPrimaryContainer,
                      ),
                    ),
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          client.displayName,
                          style: theme.textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
                        ),
                        _StatusChip(status: client.status),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 24),

              // Contact Buttons
              Row(
                children: [
                  Expanded(
                    child: FilledButton.icon(
                      onPressed: client.phone != null
                          ? () => launchUrl(Uri.parse('tel:${client.phone}'))
                          : null,
                      icon: const Icon(Icons.phone, size: 18),
                      label: const Text('Call'),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: FilledButton.tonalIcon(
                      onPressed: () {},
                      icon: const Icon(Icons.chat, size: 18),
                      label: const Text('Message'),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: OutlinedButton.icon(
                      onPressed: client.phone != null
                          ? () => launchUrl(Uri.parse('https://wa.me/${client.phone?.replaceAll(RegExp(r'[^0-9]'), '')}'))
                          : null,
                      icon: const Icon(Icons.message, size: 18),
                      label: const Text('WA'),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 24),

              // Details
              _DetailRow(label: 'Email', value: client.email),
              _DetailRow(label: 'Phone', value: client.phone),
              _DetailRow(label: 'Nationality', value: client.nationality),
              _DetailRow(label: 'City', value: client.city),
              _DetailRow(label: 'Language', value: client.preferredLanguage),
              const SizedBox(height: 16),

              // Cases
              if (client.cases != null && client.cases!.isNotEmpty) ...[
                Text('Cases', style: theme.textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold)),
                const SizedBox(height: 8),
                ...client.cases!.map((c) => Card(
                  elevation: 0,
                  margin: const EdgeInsets.only(bottom: 8),
                  child: ListTile(
                    leading: Icon(Icons.folder, color: colorScheme.primary),
                    title: Text(c.caseNumber),
                    subtitle: Text(c.serviceLabel),
                    trailing: _StatusChip(status: c.status),
                    dense: true,
                  ),
                )),
              ],
            ],
          ),
        );
      },
    );
  }
}

class _DetailRow extends StatelessWidget {
  final String label;
  final String? value;

  const _DetailRow({required this.label, this.value});

  @override
  Widget build(BuildContext context) {
    if (value == null || value!.isEmpty) return const SizedBox.shrink();
    final theme = Theme.of(context);
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 100,
            child: Text(label, style: theme.textTheme.bodySmall?.copyWith(color: theme.colorScheme.outline)),
          ),
          Expanded(child: Text(value!, style: theme.textTheme.bodyMedium)),
        ],
      ),
    );
  }
}
