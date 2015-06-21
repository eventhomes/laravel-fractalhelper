# Laravel 5 Fractal Api Controller
A simple api controller helper utilizing league fractal.

## Installation
```composer require eventhomes/laravel-apicontroller```

## Basic Usage
```php
...
use EventHomes\Api\FractalHelper;

class MyController extends Controller {

    use FractalHelper;

    public function __construct(Manager $manager, Request $request)
    {
        $this->setFractal($manager)->parseIncludes($request->get('includes', ''));
    }
}
```

## Customize Fractal

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