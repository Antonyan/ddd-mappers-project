# ddd-skeleton

The project was created to help developers in DDD principle implementation. 

#Request

````
This part will contain description about validation and filtering capabilities
````  

#Routing

Use syntax sugar for routing rules

````
$routesCollectionBuilder = new RouteCollectionBuilder();

$routesCollectionBuilder->addCRUD('/restaurants', Restaurant::class);

$routesCollectionBuilder->addGET('you/url', 'YouService', 'youMethod');

return $routesCollectionBuilder->build();

````

#App Container

In src/app/config in appContainer attach your own listeners to events and override specific reserved services <br/>

Ex. customize error handling which triggered by exception listener.<br/>
```$containerBuilder->register('application.error.handler', \App\Services\Error::class);```

To attach subscriber or listener:<br/>
```$containerBuilder->register('test_subscriber', TestSubscriber::class)->addTag('kernel.event_subscriber');``` <br/>
```$containerBuilder->register('test_subscriber', TestSubscriber::class)->addTag('kernel.event_listener',["event" => "<event_to_listen>", "method" => "<your_method>"]);```