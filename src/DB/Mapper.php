<?php

namespace DB;

use DB\Interfaces\Adapter;
use DB\Interfaces\Entity;
use DB\Interfaces\Mapper as MapperInterface;

class Mapper implements MapperInterface
{

    protected $entityClass;
    protected $adapter;
    protected $table;
    protected $primaryKey;

    public function __construct($entityClass, Adapter $adapter, $table, $primaryKey = 'id')
    {
        $this->entityClass = $entityClass;
        $this->adapter = $adapter;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    /**
     * Получить преобразователь с внешним классом сущности
     * @param $entityClass
     * @return $this|Mapper
     */
    public function withEntityClass($entityClass)
    {
        if($this->entityClass === $entityClass){
            return $this;
        }
        $instance = clone $this;
        $instance->entityClass = $entityClass;
        return $instance;
    }

    /**
     * Получить класс сущности
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Получить имя первичного ключа
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * Проверка типа
     * @param $entity
     * @throws Exception
     */
    protected function checkType($entity)
    {
        if (!$entity instanceof Entity
            and !$entity instanceof $this->entityClass
        ) {
            throw new Exception('Неверный тип сущности');
        }
    }

    /**
     * Создать сущность
     * @param array $data
     * @return Entity
     */
    protected function createEntity(array $data)
    {
        $entityClass = $this->getEntityClass();
        $entity = new $entityClass($data, $this->primaryKey);
        $this->checkType($entity);
        return $entity;
    }

    /**
     * Получить поля для выборки
     * @param array $criteria
     * @return array|mixed
     */
    protected function getColumns(array $criteria)
    {
        $tableMeta = $this->adapter->getMeta($this->table);
        $columns = isset($criteria['COLUMNS'])
            ? $criteria['COLUMNS']
            : array_column($tableMeta, 'Field');
        return !empty($columns) ? $columns : '*';
    }

    /**
     * Поиск сущности согласно критериям
     * @param array $criteria
     * @return Entity
     */
    public function find(array $criteria)
    {
        $criteria['COLUMNS'] = $this->getColumns($criteria);
        $stmt = $this->adapter->select($this->table, $criteria);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (empty($row)) {
            return null;
        }
        return $this->createEntity($row);
    }

    /**
     * Поиск массива сущностей согласно критериям
     * @param array $criteria
     * @return array
     */
    public function findAll(array $criteria)
    {
        $criteria['COLUMNS'] = $this->getColumns($criteria);
        $stmt = $this->adapter->select($this->table, $criteria);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $collection = [];
        foreach ($rows as $row) {
            $collection[] = $this->createEntity($row);
        }
        return $collection;
    }

    /**
     * Фильтрация входных данных
     * @param array $data
     * @return array
     */
    protected function filterData(array $data)
    {
        $tableMeta = $this->adapter->getMeta($this->table);
        $fields = array_column($tableMeta, 'Field');
        return array_filter($data, function ($key) use ($fields) {
            return in_array($key, $fields);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Сохранить сущность
     * @param Entity $entity
     * @return Entity
     * @throws Exception
     */
    public function save(Entity $entity)
    {
        $this->checkType($entity);
        $id = $entity->getId();
        $data = $this->filterData($entity->toArray());
        if (empty($id)) {
            $result = $this->adapter->transaction(function (Adapter $adapter) use($data){
                return $adapter->insert($this->table, $data);
            });
            if (!$result) {
                throw new Exception('Возникла ошибка при сохранении');
            }
            $id = $this->adapter->lastInsertId($this->getPrimaryKey());
            return $entity->withId($id);
        }
        $result = $this->adapter->transaction(function (Adapter $adapter) use($data, $id){
            return $adapter->update($this->table, $data, [$this->getPrimaryKey() => $id]);
        });
        if (!$result) {
            throw new Exception('Возникла ошибка при сохранении');
        }
        return $entity;
    }

    /**
     * Удалить сущность
     * @param Entity $entity
     * @return boolean
     * @throws Exception
     */
    public function delete(Entity $entity)
    {
        $this->checkType($entity);
        $id = $entity->getId();
        if (!empty($id)) {
            $result = $this->adapter->transaction(function (Adapter $adapter) use($id){
                return $adapter->delete($this->table, [$this->getPrimaryKey() => $id]);
            });
            if (!$result) {
                throw new Exception('Возникла ошибка при удалении');
            }
            return $result;
        }
        return false;
    }

    /**
     * Количество строк данных удовлетворяющих критерию
     * @param array $criteria
     * @param string|array $join
     * @return integer
     */
    public function count(array $criteria, $join = null)
    {
        $criteria['COLUMNS'] = 'count(' . $this->getPrimaryKey() . ')';
        $stmt = $this->adapter->select(
            $this->table,
            $criteria
        );
        $num = $stmt->fetch(\PDO::FETCH_COLUMN);
        return filter_var($num, FILTER_VALIDATE_INT);
    }
}