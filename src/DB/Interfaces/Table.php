<?php

namespace DB\Interfaces;

interface Table extends \ArrayAccess
{

    /**
     * Поиск сущности согласно критериям
     * @param array $criteria
     * @return Entity|null
     */
    public function find(array $criteria);

    /**
     * Поиск коллекции сущностей
     * @param array $criteria
     * @return Collection
     */
    public function findAll(array $criteria);

    /**
     * Количество сущностей удовлетворяющих критерию
     * @param array $criteria
     * @return integer
     */
    public function count(array $criteria = []);

    /**
     * Получить последний элемент
     * @return Entity|null
     */
    public function getLast();
}