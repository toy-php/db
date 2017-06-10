<?php

namespace DB;

use DB\Interfaces\DataBase as DataBaseInterface;

class DataBase implements DataBaseInterface
{

    protected $adapter;
    protected $tables;

    function __construct($dsn, $username, $passwd, $options)
    {
        $this->adapter = new ExtPDO($dsn, $username, $passwd, $options);
        $this->tables = new \ArrayObject();
    }

    /**
     * Получить адаптер
     * @return ExtPDO
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Получить класс сущности
     * @return string
     */
    protected function getEntityClass()
    {
        return Entity::class;
    }

    /**
     * Создать объект преобразователя
     * @param $entityClass
     * @param $tableName
     * @param string $primaryKey
     * @return Mapper
     */
    protected function createMapper($entityClass, $tableName, $primaryKey = 'id')
    {
        return new Mapper($entityClass, $this->getAdapter(), $tableName, $primaryKey);
    }

    /**
     * Создать объект таблицы
     * @param $tableName
     * @return Table
     */
    protected function createTable($tableName)
    {
        return new Table(
            $this->createMapper(
                $this->getEntityClass(),
                $tableName
            )
        );
    }

    /**
     * Наличие таблицы
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        $offset = $this->getAdapter()->quote($offset);
        $result = $this->getAdapter()
            ->query(sprintf('SHOW TABLES LIKE %s', $offset))
            ->fetch(\PDO::FETCH_COLUMN);
        return !empty($result);
    }

    /**
     * Получить объект таблицы
     * @param mixed $offset
     * @return Table
     * @throws Exception
     */
    public function offsetGet($offset)
    {
        if (isset($this->tables[$offset])) {
            return $this->tables[$offset];
        }
        if (!$this->offsetExists($offset)) {
            throw new Exception(
                sprintf('В базе данных отсутствует таблица "%s"', $offset)
            );
        }
        return $this->tables[$offset] = $this->createTable($offset);
    }

    /**
     * Создать таблицу
     * @param null $offset
     * @param callable $value
     * @throws Exception
     */
    public function offsetSet($offset, $value)
    {
        // @todo Создание таблицы
    }

    /**
     * Удалить таблицу
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $offset = $this->getAdapter()->quote($offset);
        $this->getAdapter()->exec(
            sprintf(/** @lang text */
            'DROP TABLE IF EXISTS %s', $offset)
        );
    }
}