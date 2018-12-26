# DDD project

This project is intended to help developers in the implementation of DDD approach with PHP programming language. There is a lot of theoretical information and a few examples.

DDD-mappers-project has all that yo u need to create a commercial product in the short term and high quality (high level of maintainability means)

## Overview

Our team doesn't want to emphasize technical things that's why we're using well-known solutions like Symfony components where it's possible.

Besides, we've created a lot of different useful features that can significantly accelerate the development.

## Quick start

All that you need is to run a command ```composer create-project antonyan/ddd-mappers-project nameOfYourProject```
[Setup DB](https://github.com/Antonyan/ddd-mappers-infrastructure) See Db interaction -> connection

## Request

To validate request data we created annotations that you can specify in presentation service (controller). 
Presentation services are usually based in ```/src/app/Services```

Example: 

```
/** 
     * @Validation(name="phone", type="string", required=true, maxLength=50)
     * @Validation(name="email", type="string", required=true, maxLength=100)
     * @Validation(name="firstName", type="string", required=true, maxLength=100)
     * @Validation(name="lastName", type="string", required=true, maxLength=100)
     * @Validation(name="status", type="string", required=true, maxLength=30)
     * @Validation(name="roles", type="array", required=true)
     *
     * @param Request $request
     * @return CreateEntityJsonResponse
     */
    public function create(Request $request): CreateEntityJsonResponse
    {
        return new CreateEntityJsonResponse($this->getUserService()->create($request->request->all())->toArray());
    }
```

In this case, our validator checks all specified fields and remove any additional from a request.

Available validation types:

```
'array', 'bool', 'callable', 'float', 'double', 'int', 'integer', 'iterable', 'long', 'null', 'numeric', 
'object', 'real', 'resource', 'scalar', 'string'
```


## Routing

Config for request you can find by path ```/src/app/config/restRoutes.php```
The project uses ```Symfony\Component\Routing\RouteCollection``` under the hood but we did several improvements that make easy to add a route.

If you need to create a route for a specific case you can use addGET, addPOST, addDELETE or addPUT.
In case if you are creating data-centric module you can use addCRUD and project creates all urls with mapping to the presentation service (controller).

````
$routesCollectionBuilder = new RouteCollectionBuilder();

$routesCollectionBuilder->addCRUD('/restaurants', Restaurant::class);

$routesCollectionBuilder->addGET('you/url', 'YouService', 'youMethod');

return $routesCollectionBuilder->build();

````

## Context

The main idea of this project to allow a developer to create architecture which will consist of contexts and modules.
Easiest way to create context is generate it with  ```vendor/bin/generate context -n ContextName``` command.
It'll be created in ```/srs/contexts/``` and consists of config, Services folders and Contract.

#### config

Folder consists of two files config.php and container.php
In config.php you can specify all data that can be changed but should be available in Service (e.x. time to live for token, 
number of attempts etc.)

Container is used to specify dependencies (Dependency Injection). In this file you should register all module services.

Example: 
``` $containerBuilder->register('UserService', Contexts\User\UserModule\Services\UserService::class);```

#### Services 

In this folder, you can see context service which orchestrates interactions with all services from module level.
It can be simple method call 
```
    public function create(array $data): UserAggregate
    {
        return $this->getUserAggregateService()->create($data);
    } 
```
or it can receive some information from one service and send it to another.

#### Contract

It's a context contract and services outside context can use it only for interaction with a module.

Example:
To use UserService you should specify in presentation service (controller)
```
    private function getUserService(): UserContract
    {
        return $this->container()->get('UserService');
    }
```

## Module

If you want to create a Module in a quick way you should create Model in the root of the context ``` /src/contexts/SomeContext```

Example:

```
<?php

namespace Contexts\Test;

use Infrastructure\Models\ArraySerializable;

class SomeModel implements ArraySerializable
{
    public const ID = 'id';
    public const PHONE = 'phone';
    public const EMAIL= 'email';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $email;

    /**
     * SomeModel constructor.
     * @param int $id
     * @param string $phone
     * @param string $email
     */
    public function __construct(
        int $id,
        string $phone,
        string $email
    )
    {
        $this->id = $id;
        $this->phone = $phone;
        $this->email = $email;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::ID => $this->id,
            self::EMAIL => $this->email,
            self::PHONE => $this->phone,
        ];
    }
}
```

And generate module ```vendor/bin/generate module -n Contexts\\Test\\SomeModel```. You should specify namespace 
of the created model.

In context will be created Module (in example SomeModelModule) which will consist such folders:
```
SomeModelModule
|-config
|-Factories
|-Mappers
|-Models
|-Services

```

#### config

This folder almost the same as in the case of Context, but config consists DB mapping for Model

Example: 

```
'SomeModelDbTranslator' => [
        'table' => 'someModels',
        'columns' => [
			'id' => 'someModels.id',
			'phone' => 'someModels.phone',
			'email' => 'someModels.email',
        ],
        'create' => 'id',
        'update' => ['id'],
    ],
```

SomeModelDbTranslator is metadata class for SomeModel. 

* table - it's a table name from db
* columns - mapping between class Model properties and database fields
* create - there you should specify a name of the identifier of the Model
* update - there you should specify a name of the identifier(s) of the Model for update

For the general case (if you need CRUD only), all data for Module will be generated automatically and you shouldn't 
care about anything.

But you can rewrite or create you own solutions in other cases.

#### Factories

There you can see simple factory which creates Model

#### Mappers

There you can see mapper for Model. Was created for typization only.

#### Services 

It's stateless classes for business logic implementation. In case of simple data-centric application can seems like 
proxy from Mapper to ContextService.

## App Container

In src/app/config in appContainer attach your own listeners to events and override specific reserved services <br/>

Ex. customize error handling which triggered by exception listener.<br/>
```$containerBuilder->register('application.error.handler', \App\Services\Error::class);```

To attach subscriber or listener:<br/>
```$containerBuilder->register('test_subscriber', TestSubscriber::class)->addTag('kernel.event_subscriber');``` <br/>
```$containerBuilder->register('test_subscriber', TestSubscriber::class)->addTag('kernel.event_listener',["event" => "<event_to_listen>", "method" => "<your_method>"]);```