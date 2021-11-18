<?php
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;

// Create a events manager
$eventsManager = new EventsManager();

// Bind the events manager to the app
$app->setEventsManager($eventsManager);
