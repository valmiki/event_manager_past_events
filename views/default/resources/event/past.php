<?php
/**
 * Site-wide "Past Events" listing.
 *
 * Mirrors event_manager's own resources/event/upcoming.php so the Past tab
 * renders identically (list / calendar / map view types, styling, breadcrumbs),
 * filtered to events that have already ended and sorted most-recent first.
 */

$page_owner = elgg_get_page_owner_entity() ?: null;
if ($page_owner instanceof \ElggGroup) {
	elgg_entity_gatekeeper($page_owner->guid, 'group');
	elgg_group_tool_gatekeeper('event_manager');

	elgg_push_collection_breadcrumbs('object', \Event::SUBTYPE, $page_owner);
} else {
	$page_owner = null;
	elgg_set_page_owner_guid(0);
}

$list_type = get_input('list_type', 'list');

$content = elgg_view("event_manager/listing/{$list_type}", [
	'options' => [
		'container_guid' => ($page_owner instanceof \ElggGroup) ? $page_owner->guid : ELGG_ENTITIES_ANY_VALUE,
		'metadata_name_value_pairs' => [
			[
				'name' => 'event_end',
				'value' => time(),
				'operand' => '<', // events that have already ended
			],
		],
		'sort_by' => [
			'property' => 'event_start',
			'direction' => 'DESC', // most recent past events first
			'signed' => true,
		],
	],
	'resource' => 'past',
	'page_owner' => $page_owner,
]);

echo elgg_view_page(elgg_echo('event_manager:list:past'), [
	'content' => $content,
	'filter_id' => 'events',
	'filter_value' => 'past',
]);
