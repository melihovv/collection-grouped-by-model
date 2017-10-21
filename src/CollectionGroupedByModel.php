<?php

declare(strict_types=1);

namespace Melihovv\CollectionGroupedByModel;

use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use BadMethodCallException;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;

/**
 * @mixin Collection
 */
class CollectionGroupedByModel implements ArrayAccess, Countable, Arrayable, IteratorAggregate
{
    /**
     * @var mixed
     */
    private $model;

    /**
     * @var Collection
     */
    private $collection;

    /**
     * CollectionGroupedByModel constructor.
     *
     * @param mixed $items
     */
    public function __construct($items = [])
    {
        $this->collection = collect($items);
    }

    /**
     * @return mixed
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     * @return $this
     */
    protected function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    /**
     * Group a collection using a callback.
     *
     * @param callable $groupBy
     * @param callable $getModel
     * @return static
     */
    public function groupByModel(callable $groupBy, callable $getModel)
    {
        $results = [];

        foreach ($this->collection->all() as $key => $value) {
            $groupKeys = $groupBy($value, $key);

            if (! is_array($groupKeys)) {
                $groupKeys = [$groupKeys];
            }

            foreach ($groupKeys as $groupKey) {
                $groupKey = is_bool($groupKey) ? (int) $groupKey : $groupKey;

                if (! array_key_exists($groupKey, $results)) {
                    $results[$groupKey] = (new static)->setModel($getModel($value));
                }

                $results[$groupKey]->offsetSet(null, $value);
            }
        }

        return (new static($results))->setModel($this->model);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (! method_exists($this->collection, $method)) {
            throw new BadMethodCallException("Method {$method} does not exist.");
        }

        return call_user_func_array([$this->collection, $method], $parameters);
    }

    /**
     * Proxy to underlying collection.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->collection->offsetExists($offset);
    }

    /**
     * Proxy to underlying collection.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->collection->offsetGet($offset);
    }

    /**
     * Proxy to underlying collection.
     *
     * @param mixed $offset
     */
    public function offsetSet($offset, $value)
    {
        $this->collection->offsetSet($offset, $value);
    }

    /**
     * Proxy to underlying collection.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $this->collection->offsetUnset($offset);
    }

    /**
     * Count the number of items in the underlying collection.
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->collection->toArray();
    }

    /**
     * Get an iterator for the underlying collection.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }
}
