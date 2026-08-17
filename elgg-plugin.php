<?php

return [
    'plugin' => [
        'name' => 'Event Manager Past Events',
        'id' => 'event_manager_past_events',
        'version' => '2.0.0',
        'elgg_version' => '>=7.0',
        'author' => 'Jan Storms',
        'license' => 'GPL-2.0-only',
        'description' => 'Adds a "Past Events" tab to the Event Manager list view to show past events.',
        'dependencies' => [
            'event_manager' => [
                'position' => 'after',
                'must_be_active' => true,
                'version' => '>=21.0',
            ],
        ],
    ],
    'bootstrap' => \EventManagerPastEvents\Bootstrap::class,
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
