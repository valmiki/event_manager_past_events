<?php

namespace EventManagerPastEvents;

use Elgg\DefaultPluginBootstrap;
use Elgg\Event;

class Bootstrap extends DefaultPluginBootstrap {

    public function init() {
        // Register the event handler to add the "Past Events" tab to the event filters
        elgg_register_event_handler('register', 'menu:filter:events', [$this, 'addPastEventsFilterTab']);
    }

    /**
     * Add the "Past Events" filter tab.
     *
     * @param \Elgg\Event $event The triggered event
     * @return void|\ElggMenuItem[]
     */
	public function addPastEventsFilterTab(Event $event) {
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
	
	    // Add the "Past Events" tab
	    $returnvalue[] = \ElggMenuItem::factory([
	        'name' => 'past',
	        'text' => elgg_echo('event_manager:list:navigation:past'),
	        'href' => $url,
	        'rel' => 'list',
	        'selected' => $selected === 'past',
	        'priority' => 50,
	    ]);
	
	    $event->setValue($returnvalue);
	}
}