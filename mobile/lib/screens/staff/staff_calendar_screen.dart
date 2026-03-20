// =====================================================
// WINCASE CRM -- Staff Calendar Screen
// Month view grid, color-coded events, tap day for details
// =====================================================

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../providers/staff_provider.dart';
import '../../models/staff_models.dart';
import '../../providers/auth_provider.dart';

class StaffCalendarScreen extends ConsumerStatefulWidget {
  const StaffCalendarScreen({super.key});

  @override
  ConsumerState<StaffCalendarScreen> createState() => _StaffCalendarScreenState();
}

class _StaffCalendarScreenState extends ConsumerState<StaffCalendarScreen> {
  late DateTime _currentMonth;
  DateTime? _selectedDay;

  @override
  void initState() {
    super.initState();
    _currentMonth = DateTime(DateTime.now().year, DateTime.now().month);
    _selectedDay = DateTime.now();
    Future.microtask(() => _loadMonth());
  }

  void _loadMonth() {
    final monthStr = '${_currentMonth.year}-${_currentMonth.month.toString().padLeft(2, '0')}';
    ref.read(calendarProvider.notifier).load(month: monthStr);
  }

  void _previousMonth() {
    setState(() {
      _currentMonth = DateTime(_currentMonth.year, _currentMonth.month - 1);
      _selectedDay = null;
    });
    _loadMonth();
  }

  void _nextMonth() {
    setState(() {
      _currentMonth = DateTime(_currentMonth.year, _currentMonth.month + 1);
      _selectedDay = null;
    });
    _loadMonth();
  }

  void _goToToday() {
    setState(() {
      _currentMonth = DateTime(DateTime.now().year, DateTime.now().month);
      _selectedDay = DateTime.now();
    });
    _loadMonth();
  }

  void _selectDay(DateTime day) {
    setState(() => _selectedDay = day);
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(calendarProvider);
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;

    final selectedEvents = _selectedDay != null ? state.eventsForDay(_selectedDay!) : <CalendarEvent>[];

    return Scaffold(
      appBar: AppBar(
        title: const Text('Calendar'),
        actions: [
          TextButton(
            onPressed: _goToToday,
            child: const Text('Today'),
          ),
        ],
      ),
      body: state.isLoading
          ? const Center(child: CircularProgressIndicator())
          : Column(
              children: [
                // --- Month Navigation ---
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      IconButton(
                        icon: const Icon(Icons.chevron_left),
                        onPressed: _previousMonth,
                      ),
                      Text(
                        _monthLabel(_currentMonth),
                        style: theme.textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
                      ),
                      IconButton(
                        icon: const Icon(Icons.chevron_right),
                        onPressed: _nextMonth,
                      ),
                    ],
                  ),
                ),

                // --- Weekday Headers ---
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  child: Row(
                    children: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                        .map((d) => Expanded(
                              child: Center(
                                child: Text(
                                  d,
                                  style: theme.textTheme.labelSmall?.copyWith(
                                    color: colorScheme.outline,
                                    fontWeight: FontWeight.w600,
                                  ),
                                ),
                              ),
                            ))
                        .toList(),
                  ),
                ),
                const SizedBox(height: 4),

                // --- Month Grid ---
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  child: _MonthGrid(
                    month: _currentMonth,
                    selectedDay: _selectedDay,
                    events: state.events,
                    onDaySelected: _selectDay,
                  ),
                ),

                const Divider(height: 24),

                // --- Events for selected day ---
                Expanded(
                  child: _selectedDay == null
                      ? Center(
                          child: Text(
                            'Select a day to see events',
                            style: theme.textTheme.bodyMedium?.copyWith(color: colorScheme.outline),
                          ),
                        )
                      : selectedEvents.isEmpty
                          ? Center(
                              child: Column(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  Icon(Icons.event_available, size: 40, color: colorScheme.outline),
                                  const SizedBox(height: 8),
                                  Text(
                                    'No events on ${_selectedDay!.day}.${_selectedDay!.month}.${_selectedDay!.year}',
                                    style: theme.textTheme.bodyMedium?.copyWith(color: colorScheme.outline),
                                  ),
                                ],
                              ),
                            )
                          : ListView.builder(
                              padding: const EdgeInsets.symmetric(horizontal: 16),
                              itemCount: selectedEvents.length,
                              itemBuilder: (context, index) => _EventCard(event: selectedEvents[index]),
                            ),
                ),
              ],
            ),
    );
  }

  String _monthLabel(DateTime date) {
    const months = [
      'January', 'February', 'March', 'April', 'May', 'June',
      'July', 'August', 'September', 'October', 'November', 'December',
    ];
    return '${months[date.month - 1]} ${date.year}';
  }
}

// =====================================================
// MONTH GRID
// =====================================================

class _MonthGrid extends StatelessWidget {
  final DateTime month;
  final DateTime? selectedDay;
  final List<CalendarEvent> events;
  final ValueChanged<DateTime> onDaySelected;

  const _MonthGrid({
    required this.month,
    this.selectedDay,
    required this.events,
    required this.onDaySelected,
  });

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final colorScheme = theme.colorScheme;
    final now = DateTime.now();

    final firstDay = DateTime(month.year, month.month, 1);
    final lastDay = DateTime(month.year, month.month + 1, 0);
    // Monday = 1, so offset = (weekday - 1)
    final startOffset = (firstDay.weekday - 1) % 7;
    final totalCells = startOffset + lastDay.day;
    final rows = (totalCells / 7).ceil();

    return Column(
      children: List.generate(rows, (row) {
        return Row(
          children: List.generate(7, (col) {
            final cellIndex = row * 7 + col;
            final dayNum = cellIndex - startOffset + 1;

            if (dayNum < 1 || dayNum > lastDay.day) {
              return const Expanded(child: SizedBox(height: 44));
            }

            final day = DateTime(month.year, month.month, dayNum);
            final isToday = day.year == now.year && day.month == now.month && day.day == now.day;
            final isSelected = selectedDay != null &&
                day.year == selectedDay!.year &&
                day.month == selectedDay!.month &&
                day.day == selectedDay!.day;
            final dayEvents = events.where((e) =>
                e.date.year == day.year &&
                e.date.month == day.month &&
                e.date.day == day.day).toList();

            return Expanded(
              child: GestureDetector(
                onTap: () => onDaySelected(day),
                child: Container(
                  height: 44,
                  margin: const EdgeInsets.all(1),
                  decoration: BoxDecoration(
                    color: isSelected
                        ? colorScheme.primary
                        : isToday
                            ? colorScheme.primaryContainer
                            : null,
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(
                        '$dayNum',
                        style: theme.textTheme.bodySmall?.copyWith(
                          fontWeight: isToday || isSelected ? FontWeight.bold : null,
                          color: isSelected
                              ? colorScheme.onPrimary
                              : isToday
                                  ? colorScheme.onPrimaryContainer
                                  : null,
                        ),
                      ),
                      if (dayEvents.isNotEmpty)
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: dayEvents.take(3).map((e) {
                            return Container(
                              width: 5,
                              height: 5,
                              margin: const EdgeInsets.symmetric(horizontal: 1),
                              decoration: BoxDecoration(
                                color: _eventColor(e.type, isSelected ? colorScheme.onPrimary : null),
                                shape: BoxShape.circle,
                              ),
                            );
                          }).toList(),
                        ),
                    ],
                  ),
                ),
              ),
            );
          }),
        );
      }),
    );
  }

  Color _eventColor(String type, [Color? overrideColor]) {
    if (overrideColor != null) return overrideColor;
    return switch (type) {
      'task'     => Colors.blue,
      'deadline' => Colors.red,
      'hearing'  => Colors.purple,
      _          => Colors.grey,
    };
  }
}

// =====================================================
// EVENT CARD
// =====================================================

class _EventCard extends StatelessWidget {
  final CalendarEvent event;

  const _EventCard({required this.event});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final eventColor = _eventColor(event.type);

    return Card(
      elevation: 0,
      margin: const EdgeInsets.only(bottom: 8),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(10),
        side: BorderSide(color: eventColor.withOpacity(0.3)),
      ),
      child: Padding(
        padding: const EdgeInsets.all(12),
        child: Row(
          children: [
            Container(
              width: 4,
              height: 40,
              decoration: BoxDecoration(
                color: eventColor,
                borderRadius: BorderRadius.circular(2),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 1),
                        decoration: BoxDecoration(
                          color: eventColor.withOpacity(0.1),
                          borderRadius: BorderRadius.circular(3),
                        ),
                        child: Text(
                          event.type[0].toUpperCase() + event.type.substring(1),
                          style: TextStyle(fontSize: 10, fontWeight: FontWeight.w700, color: eventColor),
                        ),
                      ),
                      if (event.time != null) ...[
                        const SizedBox(width: 8),
                        Text(event.time!, style: theme.textTheme.labelSmall),
                      ],
                    ],
                  ),
                  const SizedBox(height: 4),
                  Text(
                    event.title,
                    style: theme.textTheme.bodyMedium?.copyWith(fontWeight: FontWeight.w600),
                  ),
                  if (event.clientName != null || event.caseNumber != null)
                    Padding(
                      padding: const EdgeInsets.only(top: 2),
                      child: Row(
                        children: [
                          if (event.clientName != null) ...[
                            Icon(Icons.person, size: 12, color: theme.colorScheme.outline),
                            const SizedBox(width: 2),
                            Text(
                              event.clientName!,
                              style: theme.textTheme.labelSmall?.copyWith(color: theme.colorScheme.outline),
                            ),
                          ],
                          if (event.caseNumber != null) ...[
                            const SizedBox(width: 8),
                            Icon(Icons.folder, size: 12, color: theme.colorScheme.outline),
                            const SizedBox(width: 2),
                            Text(
                              event.caseNumber!,
                              style: theme.textTheme.labelSmall?.copyWith(color: theme.colorScheme.outline),
                            ),
                          ],
                        ],
                      ),
                    ),
                  if (event.location != null)
                    Padding(
                      padding: const EdgeInsets.only(top: 2),
                      child: Row(
                        children: [
                          Icon(Icons.location_on, size: 12, color: theme.colorScheme.outline),
                          const SizedBox(width: 2),
                          Text(
                            event.location!,
                            style: theme.textTheme.labelSmall?.copyWith(color: theme.colorScheme.outline),
                          ),
                        ],
                      ),
                    ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Color _eventColor(String type) => switch (type) {
    'task'     => Colors.blue,
    'deadline' => Colors.red,
    'hearing'  => Colors.purple,
    _          => Colors.grey,
  };
}
