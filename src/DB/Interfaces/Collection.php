<?php

namespace DB\Interfaces;

interface Collection extends \ArrayAccess, \IteratorAggregate, \Countable
{

    /**
     * Фильтрация коллекции.
     * Обходит каждый объект коллекции,
     * передавая его в callback-функцию.
     * Если callback-функция возвращает true,
     * данный объект из текущей коллекции попадает в результирующую коллекцию.
     * @param callable $function
     * @return static
     */
    public function filter(callable $function);

    /**
     * Перебор всех объектов коллекции.
     * Возвращает новую коллекцию,
     * содержащую объекты после их обработки callback-функцией.
     * @param callable $function
     * @return static
     */
    public function map(callable $function);

    /**
     * Итеративно уменьшает коллекцию к единственному значению
     * @param callable $function
     * @param mixed $initial
     * @return mixed
     */
    public function reduce(callable $function, $initial = null);

    /**
     * Сортировка коллекции
     * @param callable $function
     */
    public function sort(callable $function);

    /**
     * Поиск объекта по значению свойства
     * @param $property
     * @param $value
     * @return mixed
     */
    public function search($property, $value);

    /**
     * Преобразовать коллекцию в массив
     * @return array
     */
    public function toArray();
}