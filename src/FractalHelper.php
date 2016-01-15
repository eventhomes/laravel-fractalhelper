<?php

namespace EventHomes\Api;

use EventHomes\Api\ApiController;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;

trait FractalHelper
{

    use ApiController;

    /**
     * @var Manager
     */
    protected $fractal;

    /**
     * @return Manager $fractal
     */
    public function getFractal()
    {
        if (!isset($this->fractal)) {
            $this->fractal = new Manager;
            $this->fractal->setSerializer(new ArraySerializer());
        }

        return $this->fractal;
    }

    /**
     * @param Manager $fractal
     *
     * @return $this
     */
    public function setFractal(Manager $fractal)
    {
        $this->fractal = $fractal;

        return $this;
    }

    /**
     * @param $includes
     *
     * @return $this
     *
     */
    public function parseIncludes($includes = '')
    {
        if (is_null($includes)) {
            $includes = '';
        }

        $this->getFractal()->parseIncludes($includes);

        return $this;
    }

    /**
     * @param $transformer
     *
     * @return array
     */
    public function getEagerLoadsForTransformer($transformer)
    {
        $requested_includes = $this->getFractal()->getRequestedIncludes();
        $available_includes = $transformer->getAvailableIncludes();
        $default_includes = $transformer->getDefaultIncludes();

        $available_requested_includes = array_intersect($available_includes, $requested_includes);

        $final_includes = array_unique(array_merge($available_requested_includes, $default_includes));

        return $final_includes;
    }

    /**
     * @param $item
     * @param $callback
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);

        $rootScope = $this->getFractal()->createData($resource);

        return $this->respond($rootScope->toArray());
    }

    /**
     * @param $collection
     * @param $transformer
     * @param $key [resource key]
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    protected function respondWithCollection($collection, $transformer, $key = null)
    {
        $paginator = null;

        if (get_class($collection) !== 'Illuminate\Database\Eloquent\Collection') {
            $paginator = new IlluminatePaginatorAdapter($collection);
            $collection = $collection->getCollection();
        }

        $resource = new Collection($collection, $transformer, $key);

        if ($paginator) {
            $resource->setPaginator($paginator);
        }

        $rootScope = $this->getFractal()->createData($resource);

        return $this->respond($rootScope->toArray());
    }
}
