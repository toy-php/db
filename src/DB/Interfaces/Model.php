<?php

namespace DB\Interfaces;

interface Model
{
    /**
     * Получить сущность модели
     * @return Entity|null
     */
    public function getEntity();

    /**
     * Получить данные в виде массива
     * @return array
     */
    public function toArray();
}