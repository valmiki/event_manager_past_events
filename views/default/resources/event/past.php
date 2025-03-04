<?php

elgg_push_breadcrumb(elgg_echo('event_manager:list:past'));

// Define options for retrieving past events
$options = [
    'type' => 'object',
    'subtype' => 'event',
    'metadata_name_value_pairs' => [
        [
            'name' => 'event_end',
            'value' => time(),
            'operand' => '<',
        ],
    ],
    'order_by' => "(SELECT md.value FROM elgg_metadata md WHERE md.entity_guid = e.guid AND md.name = 'event_start') DESC",
    'limit' => 10,
];

// Get the list of past events
$content = elgg_list_entities($options);

if (empty($content)) {
    $content = elgg_echo('event_manager:no_past_events');
}

// Render the page using a standard layout
echo elgg_view_page(elgg_echo('event_manager:list:past'), [
    'content' => $content,
    'filter_id' => 'events',
    'filter_value' => 'past',
]);