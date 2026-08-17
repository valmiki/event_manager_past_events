# Changelog

## 2.0.0

Compatibility release for **Elgg 7.x** and **Event Manager 21.x** (tested against
event_manager v21.1). This is a breaking release: support for Elgg 5/6 and
event_manager < 21 is dropped.

### Fixed
- **Past Events tab no longer disappears on Elgg 7.** The filter-menu handler now
  *returns* the modified menu items instead of only calling `$event->setValue()`.
  Elgg 7's events service only propagates a value that a handler returns, and this
  plugin's handler runs after event_manager's, so the previous approach dropped the
  tab from the final menu.
- **Sorting no longer relies on raw SQL.** Both the site and group past-event
  resources previously ordered results with a hand-written SQL sub-query that
  referenced the `elgg_metadata` table and the `e` entity alias directly. That
  breaks on installs whose database table prefix isn't `elgg_` and is fragile
  across Elgg query-builder changes. Ordering now uses the supported
  `sort_by` option (`event_start`, descending, `signed`), matching
  event_manager's own Live/Upcoming resources.
- **Site "Past" listing now matches Live/Upcoming.** `resources/event/past.php`
  now renders through `event_manager/listing/{list_type}`, so the list/calendar/map
  view switcher and styling work the same as the other tabs (previously it was a
  plain 10-item entity list).
- **Calendar and map views on the Past tab no longer show future events.** Those
  two view types don't read the resource's entity options; they fetch data over
  AJAX from event_manager's own calendar controller (month-window based) and maps
  action (defaults to *upcoming* events). The plugin now hooks the documented
  `calendar_data:options` and `maps_data:options` events to constrain the `past`
  and `group_past` resources to events that have already ended.

### Changed
- `elgg_version` requirement raised to `>= 7.0`.
- `event_manager` dependency raised to `>= 21.0`.
- composer: `require` now targets `coldtrick/event_manager: ^21.0`; Elgg is
  constrained via `conflict: elgg/elgg: <7.0` (matching ColdTrick's own plugins)
  instead of a direct `require`.
- SPDX license identifier corrected to `GPL-2.0-only`.

### Housekeeping
- Removed `.DS_Store` / `__MACOSX` artefacts and added a `.gitignore`.

## 1.0.0
- Initial release (Elgg 5.1+, event_manager 12+).
