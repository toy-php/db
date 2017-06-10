<?php

namespace DB\Interfaces;

interface Model
{
    /**
     * Получить сущность модели
     * @return Entity|null
     */
    public function getEntity();
}