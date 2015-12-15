<?php

namespace ArsThanea\PageMediaSetBundle\Service;

class FormatRepository implements \IteratorAggregate
{
    /**
     * @var MediaFormat[]
     */
    private $items = [];

    public function all()
    {
        return $this->items;
    }

    public function keys()
    {
        return array_keys($this->items);
    }

    public function add(MediaFormat $item)
    {
        $this->items[$item->getName()] = $item;
    }

    public function get($name)
    {
        return isset($this->items[$name]) ? $this->items[$name] : null;
    }

    public function has($name)
    {
        return isset($this->items[$name]);
    }

    public function remove($name)
    {
        unset($this->items[$name]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    public function count()
    {
        return sizeof($this->items);
    }

}