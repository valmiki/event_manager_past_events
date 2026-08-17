# Event Manager Past Events
![Elgg](https://img.shields.io/badge/Elgg-7.0-green)
![Stable](https://img.shields.io/badge/stable-v2.0.0-blue)
![License](https://img.shields.io/badge/license-GPL--2.0--only-lightgrey)

Adds a **Past** tab to [ColdTrick's Event Manager](https://github.com/ColdTrick/event_manager)
list view, alongside Live and Upcoming, listing events that have already ended
(most recent first). Works site-wide and within group event listings, without
modifying any event_manager core files.

## Why is this useful?
Say you use the event_manager for a series of lessons and students need to be able to view past lessons. It is possible to find them via the Calendar view, but most often – when there are many lessons – it is far easier to locate them in the List View under the tab "Past".

## Requirements
- Elgg **>= 7.0**
- Event Manager **>= 21.0**

## Installation
Composer (recommended):

```
composer require <your-vendor>/event_manager_past_events
```

…then enable **Event Manager Past Events** in the Elgg admin plugins screen
(it must be ordered *after* event_manager; the plugin declares this dependency
automatically).

Or copy the `event_manager_past_events/` folder into your Elgg `mod/` directory
and enable it.

## How it works
- Registers a handler on `register`/`menu:filter:events` that appends the **Past**
  tab (see `classes/EventManagerPastEvents/Bootstrap.php`).
- Adds two routes, `/event/past` and `/event/past/{guid}`, resolved by the
  `resources/event/past.php` and `resources/event/group_past.php` views, which
  reuse event_manager's own `event_manager/listing/{list_type}` view.

## License
GPL-2.0-only.
