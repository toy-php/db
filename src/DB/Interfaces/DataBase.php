<?php

namespace DB\Interfaces;

interface DataBase extends \ArrayAccess
{

    /**
     * Наличие таблицы
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset);

    /**
     * Получить объект таблицы
     * @param mixed $offset
     * @return Table
     */
    public function offsetGet($offset);

    /**
     * Создать таблицу
     * @param null $offset
     * @param callable $value
     */
    public function offsetSet($offset, $value);

    /**
     * Удалить таблицу
     * @param void $offset
     */
    public function offsetUnset($offset);
}