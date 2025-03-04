<?php

return [
    'plugin' => [
        'name' => 'Event Manager Past Events',
        'id' => 'event_manager_past_events',
        'version' => '1.0.0',
        'elgg_version' => '>=5.0',
        'author' => 'Your Name',
        'license' => 'GPL-2.0',
        'description' => 'Adds a "Past Events" tab to the Event Manager list view to show previous events.',
        'dependencies' => [
            'event_manager' => [
                'position' => 'after',
                'must_be_active' => true,
                'version' => '>=12.0',
            ],
        ],
    ],
    'bootstrap' => \EventManagerPastEvents\Bootstrap::class,  // Ensure this matches your namespace and class name
    'routes' => [
	    'collection:object:event:past' => [
	        'path' => '/event/past',
	        'resource' => 'event/past',
	        'middleware' => [
	            \Elgg\Router\Middleware\Gatekeeper::class,
	        ],
	    ],
        'collection:object:event:group_past' => [
            'path' => '/event/past/{guid}',
            'resource' => 'event/group_past',
            'middleware' => [
                \Elgg\Router\Middleware\Gatekeeper::class,
            ],
        ],
    ],
];