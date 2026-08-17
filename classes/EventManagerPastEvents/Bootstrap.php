<?php

namespace EventManagerPastEvents;

use Elgg\DefaultPluginBootstrap;
use Elgg\Event;
use Elgg\Menu\MenuItems;

class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * Resources handled by this plugin that must be constrained to past events.
	 */
	const PAST_RESOURCES = ['past', 'group_past'];

	public function init() {
		// Add the "Past Events" tab to the event filter menu.
		elgg_register_event_handler('register', 'menu:filter:events', [$this, 'addPastEventsFilterTab']);

		// The list view honours the metadata filter set in our resource views, but the
		// calendar and map views fetch their data over AJAX from event_manager's own
		// controller/action, keyed only on the resource name. Those default to a
		// month-window (calendar) or to upcoming events (map), so without these two
		// handlers the Past tab's calendar/map would show future events too. Both are
		// documented extension points in event_manager.
		elgg_register_event_handler('calendar_data:options', 'event_manager', [$this, 'filterPastData']);
		elgg_register_event_handler('maps_data:options', 'event_manager', [$this, 'filterPastData']);
	}

	/**
	 * Add the "Past Events" filter tab.
	 *
	 * Note: the handler MUST return the (modified) menu items. In Elgg 7 the
	 * events service only propagates a value that a handler returns; a handler
	 * that only calls $event->setValue() and returns void has its change
	 * discarded when it runs after the event_manager handlers (which it does,
	 * because this plugin loads after event_manager). This mirrors
	 * \ColdTrick\EventManager\Menus\Filter::registerEventsList().
	 *
	 * @param \Elgg\Event $event 'register', 'menu:filter:events'
	 *
	 * @return MenuItems
	 */
	public function addPastEventsFilterTab(Event $event): MenuItems {
		$returnvalue = $event->getValue();

		// Set up the route parameters for the Past Events tab
		$route_params = [
			'list_type' => get_input('list_type'),
			'tag' => get_input('tag'),
		];

		// Check for group context and adjust the URL accordingly
		$page_owner = elgg_get_page_owner_entity();
		if ($page_owner instanceof \ElggGroup) {
			// For group context, use the group_past route with the {guid} placeholder
			$route_params['guid'] = $page_owner->guid;
			$url = elgg_generate_url('collection:object:event:group_past', $route_params);
		} else {
			// For general context, use the standard past route
			$url = elgg_generate_url('collection:object:event:past', $route_params);
		}

		$selected = $event->getParam('filter_value');

		// Add the "Past Events" tab. Priority 50 keeps the original ordering
		// (Past shown before Live/Upcoming). Use 250 to place it after Upcoming.
		$returnvalue[] = \ElggMenuItem::factory([
			'name' => 'past',
			'text' => elgg_echo('event_manager:list:navigation:past'),
			'href' => $url,
			'rel' => 'list',
			'selected' => $selected === 'past',
			'priority' => 50,
		]);

		return $returnvalue;
	}

	/**
	 * Constrain calendar/map data to events that have already ended when the
	 * request comes from one of this plugin's past resources.
	 *
	 * Handles both:
	 *  - 'calendar_data:options', 'event_manager' (\ColdTrick\EventManager\Controllers\Calendar)
	 *  - 'maps_data:options', 'event_manager'     (action event_manager/maps/data)
	 *
	 * @param \Elgg\Event $event the triggered event, value is the elgg_get_entities() options
	 *
	 * @return array|null the (possibly) modified options
	 */
	public function filterPastData(Event $event): ?array {
		$options = $event->getValue();
		if (!is_array($options)) {
			return null;
		}

		$resource = (string) $event->getParam('resource');
		if (!in_array($resource, self::PAST_RESOURCES, true)) {
			// not our resource, leave the options untouched
			return $options;
		}

		if (!isset($options['metadata_name_value_pairs']) || !is_array($options['metadata_name_value_pairs'])) {
			$options['metadata_name_value_pairs'] = [];
		}

		// The map action defaults to showing upcoming events (event_start >= now).
		// Drop that so past events can be returned.
		unset($options['metadata_name_value_pairs']['upcoming']);

		// Only events that have already ended.
		$options['metadata_name_value_pairs'][] = [
			'name' => 'event_end',
			'value' => time(),
			'operand' => '<',
		];

		return $options;
	}
}
