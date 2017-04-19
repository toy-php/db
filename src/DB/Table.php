<?php

namespace DB;

use DB\Interfaces\Entity;
use DB\Interfaces\Mapper;
use DB\Interfaces\Collection as CollectionInterface;
use DB\Interfaces\Table as TableInterface;

class Table extends Container implements TableInterface
{

    protected $mapper;
    protected $relations;

    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
        parent::__construct($this->mapper->getEntityClass());
        $this->relations = new Relations(function($entity){
            return $entity;
        });
    }

    /**
     * Создать коллекцию
     * @param array $objects
     * @return CollectionInterface
     */
    protected function createCollection(array $objects)
    {
        return new Collection($this->type, $objects);
    }

    /**
     * Построить отношения
     * @param Entity $entity
     * @return Entity
     */
    protected function buildEntityRelations(Entity $entity){
        $relations = $this->relations;
        $entity = $relations($entity);
        $this->checkType($entity);
        return $entity;
    }

    /**
     * Добавить функцию отношений таблиц
     * @param callable $relation
     * @return $this|Table
     */
    public function withRelation($relation)
    {
        $instance = clone $this;
        $instance->relations->add($relation);
        return $instance;
    }

    /**
     * Получить экземпляр с внешним преобразователем
     * @param Mapper $mapper
     * @return $this|Table
     */
    public function withMapper(Mapper $mapper)
    {
        if($this->mapper === $mapper){
            return $this;
        }
        $instance = clone $this;
        $instance->mapper = $mapper;
        return $instance;
    }

    /**
     * @inheritdoc
     */
    public function withPrimaryKey($primaryKey)
    {
        $mapper = $this->mapper->withPrimaryKey($primaryKey);
        return $this->withMapper($mapper);
    }

    /**
     * @inheritdoc
     */
    public function withEntityClass($entityClass)
    {
        $mapper = $this->mapper->withEntityClass($entityClass);
        return $this->withMapper($mapper);
    }

    /**
     * Получить последний элемент
     * @return Entity|null
     */
    public function getLast()
    {
        $objects = $this->objects->getArrayCopy();
        return end($objects);
    }

    /**
     * Поиск сущности согласно критериям
     * @param array $criteria
     * @return Interfaces\Entity|null
     */
    public function find(array $criteria)
    {
        $entity = $this->mapper->find($criteria);
        if(empty($entity)){
            return null;
        }
        if(parent::offsetExists($entity->getId())){
            return parent::offsetGet($entity->getId());
        }
        $entity = $this->buildEntityRelations($entity);
        parent::offsetSet($entity->getId(), $entity);
        return $entity;
    }

    /**
     * Поиск коллекции сущностей
     * @param array $criteria
     * @return CollectionInterface
     */
    public function findAll(array $criteria)
    {
        $entities = $this->mapper->findAll($criteria);
        $collection = $this->createCollection([]);
        /** @var Entity $entity */
        foreach ($entities as $entity) {
            if(!parent::offsetExists($entity->getId())){
                $entity = $this->buildEntityRelations($entity);
                parent::offsetSet($entity->getId(), $entity);
            }
            $collection[] = parent::offsetGet($entity->getId());
        }
        return $collection;
    }

    /**
     * Количество сущностей удовлетворяющих критерию
     * @param array $criteria
     * @return integer
     */
    public function count(array $criteria = [])
    {
        return $this->mapper->count($criteria);
    }

    /**
     * Получить сущность по идентификатору
     * @param mixed $offset
     * @return Entity|null
     */
    public function offsetGet($offset)
    {
        if(parent::offsetExists($offset)){
            return parent::offsetGet($offset);
        }
        return $this->find(['WHERE' => [$this->mapper->getPrimaryKey() => $offset]]);
    }

    /**
     * Сохранить сущность
     * @param mixed $offset
     * @param Entity $entity
     */
    public function offsetSet($offset, $entity)
    {
        $entity = $this->mapper->save($entity);
        parent::offsetSet($entity->getId(), $entity);
    }

    /**
     * Проверить наличие сущности
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        if( parent::offsetExists($offset)){
            return true;
        }
        $entity = $this->offsetGet($offset);
        return !empty($entity);
    }

    /**
     * Удалить сущность
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $entity = $this->offsetGet($offset);
        if(!empty($entity)){
            parent::offsetUnset($offset);
            $this->mapper->delete($entity);
        }
    }
}