# Laravel 5 Fractal Api Controller
A simple api controller helper utilizing league fractal.

## Installation
```composer require eventhomes/laravel-fractalhelper```

## Basic Usage
By default, this helper will use ArraySerializer(), no setup required. You may, however, need to parse the GET includes.
```php
...
use EventHomes\Api\FractalHelper;

class MyController extends Controller {

    use FractalHelper;

    public function __construct(Request $request)
    {
        $this->parseIncludes($request->get('includes', ''));
    }
}
```

## Customize Fractal
If you need to change the default ArraySerializer(), you can modify.
```php
...
use EventHomes\Api\FractalHelper;

class MyController extends Controller {

    use FractalHelper;

    public function __construct(Manager $manager, Request $request)
    {
        $manager->setSerializer(new JsonApiSerializer);
        $this->setFractal($manager)->parseIncludes($request->get('includes', ''));
    }
}
```

## Respond with item
```php
public function show($id) {
    $user = User::find($id);
    return $this->respondWithItem($user);
}
```

## Respond with collection
```php
public function show() {
    $users = User::all();
    return $this->respondWithCollection($users);
}
```

## Respond with collection, paginated
```php
public function show() {
    $users = User::paginate(10);
    return $this->respondWithCollection($users);
}
```