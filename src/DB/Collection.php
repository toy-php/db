<?php

namespace DB;

use DB\Interfaces\Collection as CollectionInterface;
use DB\Interfaces\Entity as EntityInterface;
use DB\Interfaces\Model as ModelInterface;

class Collection extends Container implements CollectionInterface
{

    public function __construct($type, array $objects = [])
    {
        parent::__construct($type);
        foreach ($objects as $offset => $object) {
            $this->offsetSet($offset, $object);
        }
    }

    /**
     * Фильтрация коллекции.
     * Обходит каждый объект коллекции,
     * передавая его в callback-функцию.
     * Если callback-функция возвращает true,
     * данный объект из текущей коллекции попадает в результирующую коллекцию.
     * @param callable $function
     * @return static
     */
    public function filter(callable $function)
    {
        $new_collection = clone $this;
        $new_collection->objects = new \ArrayObject(
            array_filter($this->objects->getArrayCopy(), $function)
        );
        return $new_collection;
    }

    /**
     * Перебор всех объектов коллекции.
     * Возвращает новую коллекцию,
     * содержащую объекты после их обработки callback-функцией.
     * @param callable $function
     * @return static
     */
    public function map(callable $function)
    {
        $new_collection = clone $this;
        $new_collection->objects = new \ArrayObject(
            array_map($function, $this->objects->getArrayCopy())
        );
        return $new_collection;
    }

    /**
     * Итеративно уменьшает коллекцию к единственному значению
     * @param callable $function
     * @param mixed $initial
     * @return mixed
     */
    public function reduce(callable $function, $initial = null)
    {
        return array_reduce($this->objects->getArrayCopy(), $function, $initial);
    }

    /**
     * Сортировка коллекции
     * @param callable $function
     */
    public function sort(callable $function)
    {
        $this->objects->uasort($function);
    }

    /**
     * Поиск объекта по значению свойства
     * @param $property
     * @param $value
     * @return mixed
     */
    public function search($property, $value)
    {
        $offset = array_search($value, array_column($this->objects->getArrayCopy(), $property));
        if($offset !== false and $offset >= 0){
            return $this->offsetGet($offset);
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return $this->objects->getIterator();
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return $this->objects->count();
    }

    /**
     * @inheritdoc
     */
    public function toArray()
    {
        $result = $this->objects->getArrayCopy();
        foreach ($result as $key => $object) {
            if($object instanceof EntityInterface
               or $object instanceof ModelInterface){
                $result[$key] = $object->toArray();
            }
        }
        return $result;
    }
}