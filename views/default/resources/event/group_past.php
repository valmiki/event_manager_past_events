<?php

$page_owner = elgg_get_page_owner_entity() ?: null;

if ($page_owner instanceof \ElggGroup) {
	elgg_entity_gatekeeper($page_owner->guid, 'group');
	elgg_group_tool_gatekeeper('event_manager');
	
	elgg_push_collection_breadcrumbs('object', \Event::SUBTYPE, $page_owner);
} else {
	$page_owner = null;
	elgg_set_page_owner_guid(0);
}

elgg_push_context('event_manager');

// Use the same container and view elements as "Live" and "Upcoming"
$list_type = get_input('list_type', 'list');

// Set up the view options specifically for past events
$content = elgg_view("event_manager/listing/{$list_type}", [
	'options' => [
		'container_guid' => ($page_owner instanceof ElggGroup) ? $page_owner->guid : ELGG_ENTITIES_ANY_VALUE,
		'metadata_name_value_pairs' => [
			[
				'name' => 'event_end',
				'value' => time(),
				'operand' => '<',  // Past events have ended before the current time
			],
		],
	    'order_by' => "(SELECT md.value FROM elgg_metadata md WHERE md.entity_guid = e.guid AND md.name = 'event_start') DESC",
	],
	'resource' => 'group_past',  // Set the resource as group_past
	'page_owner' => $page_owner,
]);

// Display the page using a consistent structure
echo elgg_view_page(elgg_echo('event_manager:list:past'), [
	'content' => $content,
	'filter_id' => 'events',
	'filter_value' => 'past',
]);

