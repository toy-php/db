<?php

namespace DB\Interfaces;

interface Mapper
{

    /**
     * Получить преобразователь с внешним классом сущности
     * @param $entityClass
     * @return $this|Mapper
     */
    public function withEntityClass($entityClass);

    /**
     * Получить преобразователь с отличным именем первичного ключа
     * @param $primaryKey
     * @return $this|Mapper
     */
    public function withPrimaryKey($primaryKey);

    /**
     * Получить класс сущности
     * @return string
     */
    public function getEntityClass();

    /**
     * Получить имя первичного ключа
     * @return string
     */
    public function getPrimaryKey();

    /**
     * Поиск сущности согласно критериям
     * @param array $criteria
     * @return Entity
     */
    public function find(array $criteria);

    /**
     * Поиск массива сущностей согласно критериям
     * @param array $criteria
     * @return array
     */
    public function findAll(array $criteria);

    /**
     * Количество строк данных удовлетворяющих критерию
     * @param array $criteria
     * @return integer
     */
    public function count(array $criteria);

    /**
     * Сохранить сущность
     * @param Entity $entity
     * @return Entity
     */
    public function save(Entity $entity);

    /**
     * Удалить сущность
     * @param Entity $entity
     * @return boolean
     */
    public function delete(Entity $entity);

}