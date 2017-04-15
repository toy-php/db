<?php

namespace DB\Interfaces;

interface Entity
{
    /**
     * Entity constructor.
     * @param array $data
     */
    public function __construct(array $data = []);

    /**
     * Получить идентификатор сущности
     * @return mixed
     */
    public function getId();

    /**
     * Получить экземпляр сущности с идентификатором
     * @param $id
     * @return static
     */
    public function withId($id);

    /**
     * Получить сущность в виде массива
     * @return array
     */
    public function toArray();
}