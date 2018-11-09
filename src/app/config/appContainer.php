<?php

$containerBuilder = new \Infrastructure\Models\ContainerBuilder();

// This is example to save your time of reading docs how to attach subscriber & listeners
// Subscriber ex.
// $containerBuilder->register('test_subscriber', TestSubscriber::class)->addTag('kernel.event_subscriber');
// to attach listener use "kernel.event_listener" in addTag, and pass in attributes  ["event" => "<event_to_listen>", "method" => "<your_method>"]


// application.error.handler is reserved service to override to handle api fail response on exception
// please override depending on your needs or disable it. On disable(comment out) it will handle by default error handler.Throw exception.
$containerBuilder->register('application.error.handler', \App\Services\Error::class);

return $containerBuilder;

