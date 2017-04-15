<?php

namespace DB;

class Container implements \ArrayAccess
{

    protected $type;
    protected $objects;
    protected $map;

    public function __construct($type)
    {
        $this->type = $type;
        $this->objects = new \ArrayObject();
        $this->map = new \SplObjectStorage();
    }

    /**
     * Проверка типа
     * @param $object
     * @throws Exception
     */
    protected function checkType($object)
    {
        if (!$object instanceof $this->type) {
            throw new Exception('Неверный тип объекта');
        }
    }

    /**
     * Наличие объекта в контейнере
     * @param $object
     * @return bool
     */
    public function contains($object)
    {
        $this->checkType($object);
        return $this->map->contains($object);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->objects->offsetExists($offset);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->objects->offsetExists($offset)
            ? $this->objects->offsetGet($offset)
            : null;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        if ($this->contains($value)) {
            return;
        }
        $this->map->offsetSet($value);
        $this->objects->offsetSet($offset, $value);
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        if ($this->objects->offsetExists($offset)) {
            $this->objects->offsetUnset($offset);
        }
    }

}