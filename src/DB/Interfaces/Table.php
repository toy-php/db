<?php

namespace DB\Interfaces;

interface Table extends \ArrayAccess
{

    /**
     * Получить экземпляр с внешним преобразователем
     * @param Mapper $mapper
     * @return $this|Table
     */
    public function withMapper(Mapper $mapper);

    /**
     * Добавить функцию отношений таблиц
     * @param callable $relation
     * @return $this|Table
     */
    public function withRelation($relation);

    /**
     * Получить экземпляр с отличным именем первичного ключа
     * @param $primaryKey
     * @return $this|Table
     */
    public function withPrimaryKey($primaryKey);

    /**
     * Получить экземпляр таблицы с внешним классом сущности
     * @param $entityClass
     * @return $this|Table
     */
    public function withEntityClass($entityClass);

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